<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Petugas;
use App\Models\Perangkat;
use App\Models\LogPetugas;
use App\Models\LogPerangkat;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    private $perangkatIds = [];
    private $perangkatWithInactiveLog = [];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedJabatan();
        $this->seedPetugas();
        $this->seedPerangkat();
        $this->seedLogPetugas();
        $this->seedLogPerangkat();
        $this->seedAdminUser();
    }

    /**
     * Seed jabatan data.
     */
    private function seedJabatan(): void
    {
        $jabatan = [
            ['nama_jabatan' => 'Kepala Tim'],
            ['nama_jabatan' => 'Petugas Lapangan'],
            ['nama_jabatan' => 'Teknisi'],
            ['nama_jabatan' => 'Operator'],
            ['nama_jabatan' => 'Koordinator Area'],
        ];

        foreach ($jabatan as $jbt) {
            Jabatan::create($jbt);
        }
    }

    /**
     * Seed petugas data.
     */
    private function seedPetugas(): void
    {
        $petugas = [
            ['nama' => 'Budi Santoso', 'alamat' => 'Jl. MT Haryono No. 123, Ketapang', 'jabatan_id' => 1, 'tgl_lahir' => '1985-05-15', 'jenis_kelamin' => 'Laki-laki', 'foto' => 'default.jpg', 'status' => 'Aktif'],
            ['nama' => 'Dewi Lestari', 'alamat' => 'Jl. Gajah Mada No. 45, Ketapang', 'jabatan_id' => 2, 'tgl_lahir' => '1990-08-21', 'jenis_kelamin' => 'Perempuan', 'foto' => 'default.jpg', 'status' => 'Aktif'],
            ['nama' => 'Ahmad Rizki', 'alamat' => 'Jl. Rahadi Usman No. 78, Ketapang', 'jabatan_id' => 3, 'tgl_lahir' => '1988-11-10', 'jenis_kelamin' => 'Laki-laki', 'foto' => 'default.jpg', 'status' => 'Aktif'],
            ['nama' => 'Siti Nurhaliza', 'alamat' => 'Jl. Suprapto No. 32, Ketapang', 'jabatan_id' => 4, 'tgl_lahir' => '1992-03-25', 'jenis_kelamin' => 'Perempuan', 'foto' => 'default.jpg', 'status' => 'Aktif'],
            ['nama' => 'Rudi Hermawan', 'alamat' => 'Jl. Imam Bonjol No. 56, Ketapang', 'jabatan_id' => 5, 'tgl_lahir' => '1987-07-18', 'jenis_kelamin' => 'Laki-laki', 'foto' => 'default.jpg', 'status' => 'Tidak Aktif'],
            ['nama' => 'Anita Wijaya', 'alamat' => 'Jl. Kartini No. 87, Ketapang', 'jabatan_id' => 2, 'tgl_lahir' => '1991-12-05', 'jenis_kelamin' => 'Perempuan', 'foto' => 'default.jpg', 'status' => 'Aktif'],
            ['nama' => 'Doni Pratama', 'alamat' => 'Jl. Veteran No. 112, Ketapang', 'jabatan_id' => 3, 'tgl_lahir' => '1989-04-17', 'jenis_kelamin' => 'Laki-laki', 'foto' => 'default.jpg', 'status' => 'Aktif'],
            ['nama' => 'Rina Sari', 'alamat' => 'Jl. Diponegoro No. 89, Ketapang', 'jabatan_id' => 4, 'tgl_lahir' => '1993-06-12', 'jenis_kelamin' => 'Perempuan', 'foto' => 'default.jpg', 'status' => 'Aktif'],
            ['nama' => 'Yudi Prasetyo', 'alamat' => 'Jl. Sudirman No. 134, Ketapang', 'jabatan_id' => 1, 'tgl_lahir' => '1986-09-30', 'jenis_kelamin' => 'Laki-laki', 'foto' => 'default.jpg', 'status' => 'Aktif'],
        ];

        foreach ($petugas as $ptg) {
            Petugas::create($ptg);
        }
    }

    /**
     * Seed perangkat data.
     */
    private function seedPerangkat(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $uuid = Str::uuid()->toString();
            $yearMonth = date('Ym');
            $randomCode = strtoupper(Str::random(6));
            $noSeri = "SN-{$yearMonth}-{$randomCode}";

            Perangkat::create([
                'id' => $uuid,
                'no_seri' => $noSeri,
                'qr_code' => $noSeri,
                'status' => $i <= 9 ? 'Aktif': 'Tidak Aktif', // 1 untuk aktif, 0 untuk tidak aktif
                'kondisi' => $i <= 9 ? 'Baik' : 'Rusak',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Seed log petugas data - PERBAIKAN NAMA KOLOM
     */
    private function seedLogPetugas(): void
    {
        $perangkatIds = Perangkat::where('status', 1)->pluck('id')->toArray();
        $petugasIds = Petugas::where('status', 'Aktif')->pluck('id')->toArray();

        // Create 9 inactive log entries (status = 0) for tracking
        $usedCombinations = [];
        $targetCount = 9;
        $currentCount = 0;

        // Pastikan kita punya cukup data
        while ($currentCount < $targetCount && count($usedCombinations) < (count($perangkatIds) * count($petugasIds))) {
            $perangkatId = $perangkatIds[array_rand($perangkatIds)];
            $petugasId = $petugasIds[array_rand($petugasIds)];
            $combination = $perangkatId . '-' . $petugasId;

            if (!in_array($combination, $usedCombinations)) {
                LogPetugas::create([
                    'perangkat_id' => $perangkatId, // PERBAIKAN: gunakan perangkat_id
                    'petugas_id' => $petugasId,     // PERBAIKAN: gunakan petugas_id
                    'status' => 0, // 0 untuk inactive (yang akan ditampilkan di tracking)
                    'created_at' => now()->subDays(rand(1, 7)),
                    'updated_at' => now()->subDays(rand(0, 3)),
                ]);
                
                $usedCombinations[] = $combination;
                $this->perangkatWithInactiveLog[] = $perangkatId;
                $currentCount++;
            }
        }

        // Create some active log entries (status = 1)
        $activeCount = min(3, count($perangkatIds));
        for ($i = 0; $i < $activeCount; $i++) {
            $perangkatId = $perangkatIds[$i];
            $petugasId = $petugasIds[$i];
            $combination = $perangkatId . '-' . $petugasId;

            // Pastikan tidak duplikat dengan inactive log
            if (!in_array($combination, $usedCombinations)) {
                LogPetugas::create([
                    'perangkat_id' => $perangkatId,
                    'petugas_id' => $petugasId,
                    'status' => 1, // 1 untuk active
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->perangkatIds = $perangkatIds;
        $this->perangkatWithInactiveLog = array_unique($this->perangkatWithInactiveLog);
    }

    /**
     * Seed log perangkat data (sensor data for perangkat).
     */
    private function seedLogPerangkat(): void
    {
        // Buat log perangkat untuk semua perangkat yang ada di log_petugas
        $perangkatIdsInLog = LogPetugas::pluck('perangkat_id')->unique()->toArray();

        foreach ($perangkatIdsInLog as $perangkatId) {
            // Buat beberapa log untuk setiap perangkat
            for ($day = 0; $day < 7; $day++) {
                $timePoints = [8, 12, 16, 20];
                foreach ($timePoints as $hour) {
                    $createdAt = now()->subDays($day)->setHour($hour)->setMinute(rand(0, 59));
                    $updatedAt = $createdAt->copy()->addMinutes(rand(0, 30));
                    $this->createLogPerangkat($perangkatId, $createdAt, $updatedAt, $hour);
                }
            }
        }
    }

    /**
     * Create a single log perangkat entry.
     */
    private function createLogPerangkat(string $perangkatId, $createdAt, $updatedAt, ?int $hour = null): void
    {
        // Koordinat dasar untuk Ketapang
        $baseLat = -1.8467;
        $baseLong = 109.9719;
        
        // Variasi lokasi dalam radius yang wajar
        $latVariation = (rand(-50, 50) / 1000); // Variasi ±0.05 derajat
        $longVariation = (rand(-50, 50) / 1000);

        $kualitasUdara = rand(30, 200);
        $status ='Aktif';

        // Simulasi suhu berdasarkan jam
        $baseTemp = 27; // Suhu dasar Ketapang
        if ($hour) {
            if ($hour >= 6 && $hour < 10) $tempVariation = rand(-2, 1); // Pagi sejuk
            elseif ($hour >= 10 && $hour < 15) $tempVariation = rand(3, 8); // Siang panas
            elseif ($hour >= 15 && $hour < 18) $tempVariation = rand(1, 5); // Sore
            else $tempVariation = rand(-3, 2); // Malam
        } else {
            $tempVariation = rand(-3, 8);
        }
        
        $suhu = $baseTemp + $tempVariation + (rand(0, 100) / 100);

        LogPerangkat::create([
            'perangkat_id' => $perangkatId,
            'latitude' => round($baseLat + $latVariation, 6),
            'longitude' => round($baseLong + $longVariation, 6),
            'kualitas_udara' => round($kualitasUdara, 1),
            'suhu' => round($suhu, 1),
            'status' => "Aktif",
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ]);
    }

    /**
     * Seed admin user.
     */
    private function seedAdminUser(): void
    {
        User::create([
            'name' => 'Admin',
            'username' => 'admin123',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}