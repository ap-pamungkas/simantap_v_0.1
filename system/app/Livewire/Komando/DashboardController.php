<?php

namespace App\Livewire\Komando;

use App\Models\LogPetugas;
use App\Models\LogPerangkat;
use App\Models\Perangkat;
use App\Models\Petugas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

class DashboardController extends Component
{
    public $qrcode = null;
    public $selectedDevice = null;
    public $deviceStatus = null;
    public $selectedPetugas = null;
    public $errorMessage = null;
    public $petugasList = [];
    public $deviceLogs = [];

    public function mount()
    {
        $this->loadPetugasList();
    }

    /** Event listener saat barcode discan */
    public function barcodeScanned($qrcode)
    {
        $this->processqrcode($qrcode);
    }

    /** Proses QR Code: mencari perangkat, ambil status & log */
    public function processqrcode($qrcode)
    {
        if (!is_string($qrcode)) {
            Log::error('qrcodeScanned received invalid type: ' . json_encode($qrcode));
            return;
        }

        $this->qrcode = $qrcode;
        Log::info('qrcode scanned: ' . $qrcode);

        $this->selectedDevice = Perangkat::where('no_seri', $qrcode)->first();

        if (!$this->selectedDevice) {
            $this->errorMessage = "Perangkat dengan No. Seri: {$qrcode} tidak ditemukan!";
            $this->deviceStatus = null;
            $this->deviceLogs = [];
            $this->dispatch('resetScanner'); // Reset scanner on error
            return;
        }

        $this->errorMessage = null;
        $this->deviceStatus = $this->selectedDevice->status;
        $this->selectedPetugas = null;

        $this->loadDeviceLogs();
    }

    /** Konfirmasi perangkat ke petugas */
    public function confirmDevice()
    {
        if (!$this->selectedDevice || !$this->selectedPetugas) {
            session()->flash('error', 'Perangkat dan petugas harus dipilih!');
            return;
        }

        try {
            DB::beginTransaction();

            LogPetugas::create([
                'id_perangkat' => $this->selectedDevice->id,
                'id_petugas' => $this->selectedPetugas,
            ]);

            DB::commit();

            session()->flash('message', 'Perangkat berhasil dikonfirmasi oleh petugas!');

            // Full reset of form state
            $this->reset(['qrcode', 'selectedDevice', 'deviceStatus', 'selectedPetugas', 'deviceLogs', 'errorMessage']);
            $this->dispatch('resetScanner'); // Dispatch event to reset scanner

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan konfirmasi perangkat: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat konfirmasi perangkat!');
            $this->dispatch('resetScanner'); // Reset scanner on error
        }
    }

    /** Ambil daftar petugas aktif */
    private function loadPetugasList()
    {
        $this->petugasList = Petugas::where('status', 'Aktif')
            ->pluck('nama', 'id')
            ->toArray();
    }

    /** Ambil log perangkat saat ini */
    private function loadDeviceLogs()
    {
        if (!$this->selectedDevice) {
            $this->deviceLogs = [];
            return;
        }

        $this->deviceLogs = LogPerangkat::where('id_perangkat', $this->selectedDevice->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /** Getter untuk perangkat yang sudah dikonfirmasi */
    public function getConfirmedDevicesProperty()
    {
        return LogPetugas::with([
            'perangkat',
            'petugas',
            'logPerangkat' => fn($query) => $query->orderByDesc('created_at')->limit(1)
        ])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($log) {
                $latestLog = $log->logPerangkat->first();

                return [
                    'no_seri' => $log->perangkat->no_seri ?? '-',
                    'status' => $log->perangkat->status ?? '-',
                    'nama_petugas' => $log->petugas->nama ?? '-',
                    'kualitas_udara' => $latestLog->kualitas_udara ?? '-',
                    'suhu' => $latestLog->suhu ?? '-',
                    'foto' => $log->petugas->foto ? url('storage/' . $log->petugas->foto) : 'https://via.placeholder.com/100x100',
                ];
            })
            ->toArray();
    }

    #[Layout('components.layouts.komando')]
    public function render()
    {
        return view('livewire.komando.dashboard', [
            'confirmedDevices' => $this->confirmedDevices,
        ]);
    }
}
