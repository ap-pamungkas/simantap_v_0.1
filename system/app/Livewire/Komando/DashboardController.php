<?php

namespace App\Livewire\Komando;

use App\Models\Insiden;
use App\Models\LogPerangkat;
use App\Models\LogPetugas;
use App\Models\Perangkat;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class DashboardController extends Component
{
    #[Title("Dashboard Komando")]
    #[Layout('components.layouts.komando')]
    public $staffOnDuty; // Petugas bertugas (LogPetugas status 0)
    public $devicesUsed; // Perangkat aktif
    public $perangkat; // Total perangkat aktif
    public $mapTheme = 'light'; // Default base layer

    public $temperatureData = [];
    public $co2Data = [];
    public $incidentData = [];

    public function mount()
    {
        // Hitung petugas bertugas (LogPetugas dengan status 0)
        $this->staffOnDuty = LogPetugas::where('status', 0)->count();

        // Hitung perangkat aktif
        $this->devicesUsed = Perangkat::where('status', 'Aktif')->count();
        $this->perangkat = $this->devicesUsed;

        // Ambil data suhu dan kualitas udara dari LogPerangkat (7 hari terakhir)
        $logPerangkat = LogPerangkat::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('AVG(suhu) as avg_suhu'),
            DB::raw('AVG(kualitas_udara) as avg_kualitas_udara'),
            DB::raw('AVG(latitude) as avg_latitude'),
            DB::raw('AVG(longitude) as avg_longitude')
        )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

           

        // Data untuk grafik dan heatmap suhu
        $this->temperatureData = [
            'labels' => $logPerangkat->pluck('date')->map(fn($date) => date('Y-m-d', strtotime($date)))->toArray(),
            'data' => $logPerangkat->pluck('avg_suhu')->map(fn($suhu) => round($suhu, 1))->toArray(),
            'coords' => $logPerangkat->map(fn($log) => [
                'lat' => round($log->avg_latitude, 6),
                'lng' => round($log->avg_longitude, 6),
                'intensity' => round($log->avg_suhu, 1) / 40,
            ])->toArray(),
        ];

        // Data untuk grafik dan heatmap kualitas udara
        $this->co2Data = [
            'labels' => $logPerangkat->pluck('date')->map(fn($date) => date('Y-m-d', strtotime($date)))->toArray(),
            'data' => $logPerangkat->pluck('avg_kualitas_udara')->map(fn($kualitas) => round($kualitas, 1))->toArray(),
            'coords' => $logPerangkat->map(fn($log) => [
                'lat' => round($log->avg_latitude, 6),
                'lng' => round($log->avg_longitude, 6),
                'intensity' => round($log->avg_kualitas_udara, 1) / 500,
            ])->toArray(),
        ];

        // Ambil data petugas bertugas, suhu, dan kualitas udara (7 hari terakhir)
        $logPetugas = LogPetugas::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('status', 0)
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->incidentData = [
            'labels' => $logPerangkat->pluck('date')->map(fn($date) => date('Y-m-d', strtotime($date)))->toArray(),
            'petugas' => [],
            'suhu' => $logPerangkat->pluck('avg_suhu')->map(fn($suhu) => round($suhu, 1))->toArray(),
            'kualitas_udara' => $logPerangkat->pluck('avg_kualitas_udara')->map(fn($kualitas) => round($kualitas, 1))->toArray(),
        ];

        // Map petugas count to match dates
        foreach ($this->incidentData['labels'] as $date) {
            $petugasCount = $logPetugas->firstWhere('date', $date)?->count ?? 0;
            $this->incidentData['petugas'][] = $petugasCount;
        }

        // Fallback jika data kosong
        if (empty($this->temperatureData['labels'])) {
            $this->temperatureData = [
                'labels' => [now()->format('Y-m-d')],
                'data' => [0],
                'coords' => [['lat' => -1.8467, 'lng' => 109.9719, 'intensity' => 0]],
            ];
        }
        if (empty($this->co2Data['labels'])) {
            $this->co2Data = [
                'labels' => [now()->format('Y-m-d')],
                'data' => [0],
                'coords' => [['lat' => -1.8467, 'lng' => 109.9719, 'intensity' => 0]],
            ];
        }
        if (empty($this->incidentData['labels'])) {
            $this->incidentData = [
                'labels' => [now()->format('Y-m-d')],
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
        return view('livewire.komando.dashboard');
    }
}
