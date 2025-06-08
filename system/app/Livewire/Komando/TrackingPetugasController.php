<?php

namespace App\Livewire\Komando;

use App\Models\LogPetugas;
use App\Models\Petugas;
use App\Models\Perangkat;
use App\Models\LogPerangkat;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class TrackingPetugasController extends Component
{
    #[Layout('components.layouts.komando')]
    #[Title("Pemantauan Petugas")]
    
    public $inactivePetugas = [];
    
    public function mount()
    {
        // Mengambil data log petugas dengan status 0
        $logPetugasData = LogPetugas::with([
                'perangkat', 
                'petugas'
            ])
            ->where('status', 0)
            ->orderByDesc('created_at')
            ->get();

        // Format data for easier frontend access
        $this->inactivePetugas = $logPetugasData->map(function($log) {
            // Ambil log perangkat terakhir untuk setiap perangkat
            $lastLogPerangkat = LogPerangkat::where('perangkat_id', $log->perangkat_id)
                ->orderByDesc('created_at')
                ->first();
            
            return [
                'id' => $log->id,
                'petugas' => [
                    'id' => $log->petugas->id,
                    'nama' => $log->petugas->nama,
                ],
                'perangkat' => [
                    'id' => $log->perangkat->id,
                    'no_seri' => $log->perangkat->no_seri,
                ],
                'last_log' => $lastLogPerangkat ? [
                    'latitude' => $lastLogPerangkat->latitude,
                    'longitude' => $lastLogPerangkat->longitude,
                    'timestamp' => $lastLogPerangkat->created_at->format('Y-m-d H:i:s'),
                    'kualitas_udara' => $lastLogPerangkat->kualitas_udara,
                    'suhu' => $lastLogPerangkat->suhu,
                ] : null,
                'status' => $log->perangkat->status, // Perbaikan: hapus first()
                'created_at' => $log->created_at,
                'updated_at' => $log->updated_at
            ];
        })
        ->filter(function($item) {
            // Filter hanya yang memiliki last_log data
            return $item['last_log'] !== null;
        })
        ->values() // Reset array keys
        ->toArray();
    }
    
    public function render()
    {
        return view('livewire.komando.tracking-petugas', [
            'inactivePetugas' => $this->inactivePetugas
        ]);
    }
}