<?php

namespace App\Services;
use App\Models\LogPetugas;
use App\Models\LogPerangkat;
use App\Models\Perangkat;

class DeviceService
{
    public function findDeviceBySerial(string $noSeri): ?Perangkat
    {
        return Perangkat::where('no_seri', $noSeri)->first();
    }

    public function isAlreadyConfirmed(int $perangkatId): bool
    {
        return LogPetugas::where('id_perangkat', $perangkatId)
            ->where('status', 0)
            ->exists();
    }

    public function createLogPetugas(int $perangkatId, int $petugasId): void
    {
        LogPetugas::create([
            'id_perangkat' => $perangkatId,
            'id_petugas' => $petugasId,
            'status' => 0,
        ]);
    }

    public function getDeviceLogs(int $perangkatId)
    {
        return LogPerangkat::where('perangkat_id', $perangkatId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getExistingConfirmation(int $perangkatId)
    {
        return LogPetugas::with('petugas')
            ->where('id_perangkat', $perangkatId)
            ->where('status', 0)
            ->orderByDesc('created_at')
            ->first();
    }
}
