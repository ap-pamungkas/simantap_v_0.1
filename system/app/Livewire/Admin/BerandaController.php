<?php

namespace App\Livewire\Admin;

use App\Models\Perangkat;
use App\Models\LogPetugas;
use App\Models\LogPerangkat;
use App\Models\Petugas;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class BerandaController extends Component
{
    #[Title("Beranda")]
    public $staffActive; // Petugas bertugas (LogPetugas status 0)
    public $devices; // Perangkat aktif
    public $damagedDevices; // Total perangkat aktif
    public $mapTheme = 'light'; // Default base layer

    public $temperatureData = [];
    public $co2Data = [];
    public $incidentData = [];

    public function mount()
    {
        // Hitung petugas bertugas (LogPetugas dengan status 0)
        $this->staffActive = Petugas::where('status', 'Aktif')->count('id');

        // Hitung perangkat aktif
        $this->devices = Perangkat::count('id');

        $this->damagedDevices = Perangkat::where('status', 'Rusak')->count('id');

        // Ambil data suhu dan kualitas udara dari LogPerangkat (7 hari terakhir)
        $logPerangkat = LogPerangkat::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('AVG(suhu) as avg_suhu'),
            DB::raw('AVG(kualitas_udara) as avg_kualitas_udara'),
            DB::raw('AVG(latitude) as avg_latitude'),
            DB::raw('AVG(longitude) as avg_longitude')
        )
            ->where('created_at', '>=', now()->subDays(7))
            ->whereNotNull('suhu')
            ->whereNotNull('kualitas_udara')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Data untuk grafik dan heatmap suhu
        $this->temperatureData = [
            'labels' => $logPerangkat->pluck('date')->map(fn($date) => date('Y-m-d', strtotime($date)))->toArray(),
            'data' => $logPerangkat->pluck('avg_suhu')->map(fn($suhu) => round((float)$suhu, 1))->toArray(),
            'coords' => $logPerangkat->filter(function($log) {
                return $log->avg_latitude && $log->avg_longitude && $log->avg_suhu;
            })->map(fn($log) => [
                'lat' => round((float)$log->avg_latitude, 6),
                'lng' => round((float)$log->avg_longitude, 6),
                'intensity' => max(0, min(1, round((float)$log->avg_suhu, 1) / 40)),
            ])->values()->toArray(),
        ];

        // Data untuk grafik dan heatmap kualitas udara
        $this->co2Data = [
            'labels' => $logPerangkat->pluck('date')->map(fn($date) => date('Y-m-d', strtotime($date)))->toArray(),
            'data' => $logPerangkat->pluck('avg_kualitas_udara')->map(fn($kualitas) => round((float)$kualitas, 1))->toArray(),
            'coords' => $logPerangkat->filter(function($log) {
                return $log->avg_latitude && $log->avg_longitude && $log->avg_kualitas_udara;
            })->map(fn($log) => [
                'lat' => round((float)$log->avg_latitude, 6),
                'lng' => round((float)$log->avg_longitude, 6),
                'intensity' => max(0, min(1, round((float)$log->avg_kualitas_udara, 1) / 500)),
            ])->values()->toArray(),
        ];

        // Ambil data petugas bertugas (7 hari terakhir)
        $logPetugas = LogPetugas::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('status', 0)
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Create a complete date range for the last 7 days
        $dateRange = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dateRange->push(now()->subDays($i)->format('Y-m-d'));
        }

        $this->incidentData = [
            'labels' => $dateRange->toArray(),
            'petugas' => [],
            'suhu' => [],
            'kualitas_udara' => [],
        ];

        // Map data to complete date range
        foreach ($dateRange as $date) {
            // Petugas count
            $petugasCount = $logPetugas->firstWhere('date', $date)?->count ?? 0;
            $this->incidentData['petugas'][] = $petugasCount;

            // Suhu data
            $suhuData = $logPerangkat->firstWhere('date', $date);
            $this->incidentData['suhu'][] = $suhuData ? round((float)$suhuData->avg_suhu, 1) : 0;

            // Kualitas udara data
            $this->incidentData['kualitas_udara'][] = $suhuData ? round((float)$suhuData->avg_kualitas_udara, 1) : 0;
        }

        // Fallback jika data kosong
        if (empty($this->temperatureData['labels'])) {
            $defaultDate = now()->format('Y-m-d');
            $this->temperatureData = [
                'labels' => [$defaultDate],
                'data' => [0],
                'coords' => [['lat' => -1.8467, 'lng' => 109.9719, 'intensity' => 0]],
            ];
        }
        if (empty($this->co2Data['labels'])) {
            $defaultDate = now()->format('Y-m-d');
            $this->co2Data = [
                'labels' => [$defaultDate],
                'data' => [0],
                'coords' => [['lat' => -1.8467, 'lng' => 109.9719, 'intensity' => 0]],
            ];
        }
        if (empty($this->incidentData['labels'])) {
            $defaultDate = now()->format('Y-m-d');
            $this->incidentData = [
                'labels' => [$defaultDate],
                'petugas' => [0],
                'suhu' => [0],
                'kualitas_udara' => [0],
            ];
        }
    }

    public function updateMapTheme($theme)
    {
        $validThemes = ['light', 'dark', 'satellite'];
        if (in_array($theme, $validThemes)) {
            $this->mapTheme = $theme;
        }
    }

    public function render()
    {
        return view('livewire.admin.beranda');
    }
}