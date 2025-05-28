<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perangkat;
use App\Repositories\PerangkatRepository;
use App\Traits\Message;
use Illuminate\Http\Request;

class PerangkatController extends Controller
{
    use Message;

    private  $perangkatRepository;


    public function __construct()
    {
        $this->perangkatRepository = new PerangkatRepository();
    }

    public function regisDevices(Request $request, $devicesId)
    {
        try {
            if (!$devicesId) {
                return response()->json(['status' => 'error', 'message' => 'Device ID kosong'], 400);
            }

            $device = Perangkat::find($devicesId);

            if ($device) {
                $device->update([
                    'status' => 'Aktif',
                    'updated_at' => now(), // penting: pastikan waktu diperbarui
                ]);
            } else {
                $data = [
                    'id' => $devicesId,
                    'status' => 'Aktif',
                    'kondisi' => 'Baik',
                ];
                $device = $this->perangkatRepository->regisDevices($data);
            }
            $this->dispatchSuccesMassage('Data berhasil disimpan');
            return response()->json([
                'status' => 'success',
                'message' => 'Perangkat berhasil diperbarui',
                'data' => $device,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

}
