<?php

namespace App\Repositories;

use App\Models\Perangkat;
use App\Repositories\Repository;
use App\Traits\QueryHelper;
use Illuminate\Support\Facades\Storage;

class PerangkatRepository extends Repository
{
    use QueryHelper;



    public function getDevices($search, $perPage, $sortField = null, $sortDirection = null)
{
    // Tandai perangkat sebagai tidak aktif jika tidak update dalam 30 detik
    $cutoff = now()->subSeconds(30);
    Perangkat::where('updated_at', '<', $cutoff)
        ->where('status', 'aktif')
        ->update(['status' => 'tidak aktif']);

    // Ambil data perangkat dengan filter dan sorting
    return Perangkat::where('no_seri', 'like', "%{$search}%")
        ->orderBy($sortField ?? 'id', $sortDirection ?? 'asc')
        ->paginate($perPage);
}




    public function regisDevices($data)
    {
        // Generate nomor seri
        $data['no_seri'] = Perangkat::generateNoSeri();

        // Buat instance tanpa menyimpan
        $perangkat = new Perangkat($data);

        // Generate barcode dan simpan path-nya ke instance
        $perangkat->qr_code = $perangkat->generateBarcode();

        // Simpan ke database
        $perangkat->save();

        return $perangkat;
    }


    public function updateConditions($devicesId){
        $devices = Perangkat::find($devicesId);

        if(!$devices) {
            return false;
        }

        $devices->kondisi = $this->conditions($devices->kondisi);
        $devices->save();

        return $devices;


    }




    private function conditions($currentCondition)
    {
        // Toggle status between 'Aktif' and 'Tidak Aktif'
       $currentCondition = $currentCondition === 'Baik' ? 'Rusak' : 'Baik';

        return $currentCondition;
    }


    public function deleteDevices($id)
    {
        $devices = Perangkat::findOrFail($id);
        if ($devices->qr_code && Storage::disk('public')->exists($devices->qr_code)) {
            Storage::disk('public')->delete($devices->qr_code);
        }
        return $devices->delete();
    }

    public function updateStatus($perangkatId)
    {
        $devices = Perangkat::find($perangkatId);

        if (!$devices) {
            return false;
        }
        // Toggle status between 'Aktif' and 'Tidak Aktif'
        $devices->status = $devices->kondisi === 'Baik' ? 'Rusak' : 'Baik';
        $devices->save();

        return $devices; // Kembalikan objek Petugas setelah diperbarui
    }
}
