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


    public function barcodeScanned($qrcode)
    {
        $this->processQRCode($qrcode);
    }


    public function processQRCode($qrcode)
    {
        // Validasi QR Code format dan konten
        if (!$this->isValidQRCode($qrcode)) {
            return;
        }

        $this->qrcode = $qrcode;
        Log::info('QR Code scanned: ' . $qrcode);

        // Cari perangkat berdasarkan no_seri
        $this->selectedDevice = Perangkat::where('no_seri', $qrcode)->first();

        if (!$this->selectedDevice) {
            $this->handleDeviceNotFound($qrcode);
            return;
        }

        // Validasi apakah perangkat sudah dikonfirmasi dengan status log_petugas = 0
        if ($this->isDeviceAlreadyConfirmed($this->selectedDevice->id)) {
            $this->handleDuplicateDevice($this->selectedDevice);
            return;
        }

        $this->initializeDeviceStatus();
        $this->loadDeviceLogs();
    }


    public function confirmDevice()
    {
        if (!$this->isDeviceAndPetugasSelected()) {
            session()->flash('error', 'Perangkat dan petugas harus dipilih!');
            return;
        }

        try {
            DB::beginTransaction();
            $this->createLogPetugas();
            DB::commit();
            session()->flash('message', 'Perangkat berhasil dikonfirmasi oleh petugas!');
            $this->resetFormState();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleConfirmationError($e);
        }
    }


    private function loadPetugasList()
    {
        $this->petugasList = Petugas::where('status', 'Aktif')
            ->pluck('nama', 'id')
            ->toArray();
    }


    private function loadDeviceLogs()
    {
        if (!$this->selectedDevice) {
            $this->deviceLogs = [];
            return;
        }

        $this->deviceLogs = LogPerangkat::where('perangkat_id', $this->selectedDevice->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }


    public function getConfirmedDevicesProperty()
    {
        return LogPetugas::with(['perangkat', 'petugas', 'logPerangkat' => fn($query) => $query->orderByDesc('created_at')->limit(1)])
            ->where('status', 0)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($log) => $this->mapConfirmedDevice($log))
            ->toArray();
    }


    #[Layout('components.layouts.komando')]
    public function render()
    {
        return view('livewire.komando.dashboard', [
            'confirmedDevices' => $this->confirmedDevices,
        ]);
    }


    /**
     * Validasi QR Code - Enhanced version
     */
    private function isValidQRCode($qrcode)
    {
        // Validasi tipe data
        if (!is_string($qrcode)) {
            Log::error('Invalid QR Code type: ' . json_encode($qrcode));
            $this->errorMessage = "Format QR Code tidak valid!";
            $this->dispatch('resetScanner');
            return false;
        }

        // Validasi QR Code tidak kosong
        $qrcode = trim($qrcode);
        if (empty($qrcode)) {
            Log::error('Empty QR Code detected');
            $this->errorMessage = "QR Code tidak boleh kosong!";
            $this->dispatch('resetScanner');
            return false;
        }

        // Validasi panjang QR Code (asumsi no_seri tidak boleh terlalu pendek atau panjang)
        if (strlen($qrcode) < 3) {
            Log::error('QR Code too short: ' . $qrcode);
            $this->errorMessage = "QR Code terlalu pendek! Minimal 3 karakter.";
            $this->dispatch('resetScanner');
            return false;
        }

        if (strlen($qrcode) > 50) {
            Log::error('QR Code too long: ' . $qrcode);
            $this->errorMessage = "QR Code terlalu panjang! Maksimal 50 karakter.";
            $this->dispatch('resetScanner');
            return false;
        }

        // Validasi karakter yang diperbolehkan (alfanumerik, dash, underscore)
        if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $qrcode)) {
            Log::error('Invalid QR Code format: ' . $qrcode);
            $this->errorMessage = "Format QR Code tidak valid! Hanya diperbolehkan huruf, angka, dash (-), dan underscore (_).";
            $this->dispatch('resetScanner');
            return false;
        }

        return true;
    }


    /**
     * Validasi apakah perangkat sudah dikonfirmasi dengan status log_petugas = 0
     */
    private function isDeviceAlreadyConfirmed($perangkatId)
    {
        return LogPetugas::where('id_perangkat', $perangkatId)
            ->where('status', 0)
            ->exists();
    }


    /**
     * Handle ketika perangkat sudah dikonfirmasi sebelumnya
     */
    private function handleDuplicateDevice($device)
    {
        // Ambil data log petugas yang sudah ada
        $existingLog = LogPetugas::with('petugas')
            ->where('id_perangkat', $device->id)
            ->where('status', 0)
            ->orderBy('created_at', 'desc')
            ->first();

        $petugasName = $existingLog && $existingLog->petugas
            ? $existingLog->petugas->nama
            : 'Tidak diketahui';

        $confirmDate = $existingLog
            ? $existingLog->created_at->format('d/m/Y H:i')
            : 'Tidak diketahui';

        $this->errorMessage = "Perangkat dengan No. Seri: {$device->no_seri} sudah dikonfirmasi oleh {$petugasName} pada tanggal {$confirmDate}";

        $this->selectedDevice = null;
        $this->deviceStatus = null;
        $this->deviceLogs = [];

        Log::warning("Duplicate device confirmation attempt: {$device->no_seri}");
        $this->dispatch('resetScanner');
    }


    private function handleDeviceNotFound($qrcode)
    {
        $this->errorMessage = "Perangkat dengan No. Seri: {$qrcode} tidak ditemukan dalam database!";
        $this->deviceStatus = null;
        $this->deviceLogs = [];
        $this->selectedDevice = null;

        Log::warning("Device not found: {$qrcode}");
        $this->dispatch('resetScanner');
    }


    private function initializeDeviceStatus()
    {
        $this->errorMessage = null;
        $this->deviceStatus = $this->selectedDevice->status;
        $this->selectedPetugas = null;
    }


    private function isDeviceAndPetugasSelected()
    {
        return $this->selectedDevice && $this->selectedPetugas;
    }


    private function createLogPetugas()
    {
        LogPetugas::create([
            'id_perangkat' => $this->selectedDevice->id,
            'id_petugas' => $this->selectedPetugas,
            'status' => 0, // Pastikan status diset ke 0 saat konfirmasi
        ]);
    }


    private function resetFormState()
    {
        $this->reset(['qrcode', 'selectedDevice', 'deviceStatus', 'selectedPetugas', 'deviceLogs', 'errorMessage']);
        $this->dispatch('resetScanner');
    }


    private function handleConfirmationError($e)
    {
        Log::error('Failed to save device confirmation: ' . $e->getMessage());
        session()->flash('error', 'Terjadi kesalahan saat konfirmasi perangkat!');
        $this->dispatch('resetScanner');
    }


    private function mapConfirmedDevice($log)
    {
        $latestLog = $log->logPerangkat->first();

        return [
            'no_seri' => $log->perangkat->no_seri ?? '-',
            'status' => $log->perangkat->status ?? '-',
            'nama_petugas' => $log->petugas->nama ?? '-',
            'kualitas_udara' => $latestLog->kualitas_udara ?? '-',
            'suhu' => $latestLog->suhu ?? '-',
            'foto' => $log->petugas->foto ? url('system/storage/app/public/' . $log->petugas->foto) : 'https://via.placeholder.com/100x100',
        ];
    }
}
