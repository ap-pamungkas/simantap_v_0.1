<?php

namespace App\Livewire\Komando;

use App\Models\LogPetugas;
use App\Models\Petugas;
use App\Services\{QRCodeService, DeviceService};

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class RegistrasiPetugasController extends Component
{
   #[Title("Registrasi Petugas")]
    public $qrcode = null;
    public $selectedDevice = null;
    public $deviceStatus = null;
    public $selectedPetugas = null;
    public $errorMessage = null;
    public $petugasList = [];
    public $deviceLogs = [];

    private $qrCodeService;
    private $deviceService;

    public function __construct()
    {
        $this->qrCodeService = new QRCodeService();
        $this->deviceService = new DeviceService();
    }
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
        $validation = $this->qrCodeService->validate($qrcode);
        if ($validation !== true) {
            $this->errorMessage = $validation;
            $this->dispatch('resetScanner');
            return;
        }

        $this->qrcode = $qrcode;
        Log::info('QR Code scanned: ' . $qrcode);

        $this->selectedDevice = $this->deviceService->findDeviceBySerial($qrcode);

        if (!$this->selectedDevice) {
            $this->handleDeviceNotFound($qrcode);
            return;
        }

        if ($this->deviceService->isAlreadyConfirmed($this->selectedDevice->id)) {
            $this->handleDuplicateDevice($this->selectedDevice);
            return;
        }

        $this->initializeDeviceStatus();
        $this->deviceLogs = $this->deviceService->getDeviceLogs($this->selectedDevice->id);
    }

    public function confirmDevice()
    {
        if (!$this->isDeviceAndPetugasSelected()) {
            session()->flash('error', 'Perangkat dan petugas harus dipilih!');
            return;
        }

        try {
            DB::beginTransaction();
            $this->deviceService->createLogPetugas($this->selectedDevice->id, $this->selectedPetugas);
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
        return view('livewire.komando.registrasi-petugas', [
            'confirmedDevices' => $this->confirmedDevices,
        ]);
    }

    private function handleDuplicateDevice($device)
    {
        $existingLog = $this->deviceService->getExistingConfirmation($device->id);

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
        $this->errorMessage = "Perangkat dengan No. Seri: {$qrcode} tidak ditemukan!";
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
