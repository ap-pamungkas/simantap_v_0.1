<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LogPerangkat;
use App\Models\LogPetugas;
use App\Models\Perangkat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LogPerangkatController extends Controller
{
     /**
     * Menyimpan data log perangkat dari ESP8266
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

     public function index(){
        $data = LogPerangkat::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data log perangkat berhasil diambil',
            'data' => $data
        ]);
     }
    public function storeLogPerangkat(Request $request)
    {
        try {
            // Validasi request
            $validator = Validator::make($request->all(), [
                'id_perangkat' => 'required|string|exists:perangkat,id',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'kualitas_udara' => 'nullable|numeric',
                'suhu' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Cek apakah perangkat terdaftar dan aktif
            $perangkat = Perangkat::find($request->id_perangkat);
            if (!$perangkat) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Perangkat tidak terdaftar'
                ], 404);
            }

            if ($perangkat->status !== 'Aktif') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Perangkat tidak aktif'
                ], 403);
            }

            // Buat log perangkat baru
            $logPerangkat = LogPerangkat::create([
                'id_perangkat' => $request->id_perangkat,
                'status'=> $perangkat->status,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'kualitas_udara' => $request->kualitas_udara,
                'suhu' => $request->suhu,
            ]);

            // Update last activity perangkat jika diperlukan
            $perangkat->update([
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data log perangkat berhasil disimpan',
                'data' => $logPerangkat
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil data log perangkat berdasarkan ID perangkat
     *
     * @param string $idPerangkat
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogsByDeviceId($idPerangkat)
    {
        try {
            // Cek apakah perangkat ada
            $perangkat = Perangkat::find($idPerangkat);
            if (!$perangkat) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Perangkat tidak ditemukan'
                ], 404);
            }

            // Ambil data log perangkat
            $logs = LogPerangkat::where('id_perangkat', $idPerangkat)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data log perangkat berhasil diambil',
                'data' => [
                    'perangkat' => $perangkat,
                    'logs' => $logs
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil data log perangkat terbaru
     *
     * @param string $idPerangkat
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLatestLogByDeviceId($idPerangkat)
    {
        try {
            // Cek apakah perangkat ada
            $perangkat = Perangkat::find($idPerangkat);
            if (!$perangkat) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Perangkat tidak ditemukan'
                ], 404);
            }

            // Ambil data log perangkat terbaru
            $latestLog = LogPerangkat::where('id_perangkat', $idPerangkat)
                ->latest()
                ->first();

            if (!$latestLog) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Belum ada data log untuk perangkat ini',
                    'data' => null
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data log perangkat terbaru berhasil diambil',
                'data' => $latestLog
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }


    public function getData(){
        $logPetugas = LogPetugas::with('perangkat.logPerangkat')->latest()->first();

// Ambil semua log perangkat berdasarkan perangkat yang dipakai
    $logsSensor = $logPetugas->perangkat->logPerangkat ?? collect();

return response()->json([
    'status' => 'success',
    'message' => 'Data log perangkat berhasil diambil',
    'data' => $logPetugas
]);
    }
}
