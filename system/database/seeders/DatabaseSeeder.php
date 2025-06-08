<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Petugas;
use App\Models\Perangkat;
use App\Models\LogPetugas;
use App\Models\LogPerangkat;
use App\Models\Insiden;
use App\Models\InsidenDetail;
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
        $this->seedInsiden();
        $this->seedInsidenDetails();
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
                'status' => $i <= 9 ? 'Aktif': 'Tidak Aktif',
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

        while ($currentCount < $targetCount && count($usedCombinations) < (count($perangkatIds) * count($petugasIds))) {
            $perangkatId = $perangkatIds[array_rand($perangkatIds)];
            $petugasId = $petugasIds[array_rand($petugasIds)];
            $combination = $perangkatId . '-' . $petugasId;

            if (!in_array($combination, $usedCombinations)) {
                LogPetugas::create([
                    'perangkat_id' => $perangkatId,
                    'petugas_id' => $petugasId,    
                    'status' => 0,
                    'created_at' => now()->subDays(rand(1, 7)),
                    'updated_at' => now()->subDays(rand(0, 3)),
                ]);
                
                $usedCombinations[] = $combination;
                $this->perangkatWithInactiveLog[] = $perangkatId;
                $currentCount++;
            }
        }

        $activeCount = min(3, count($perangkatIds));
        for ($i = 0; $i < $activeCount; $i++) {
            $perangkatId = $perangkatIds[$i];
            $petugasId = $petugasIds[$i];
            $combination = $perangkatId . '-' . $petugasId;

            if (!in_array($combination, $usedCombinations)) {
                LogPetugas::create([
                    'perangkat_id' => $perangkatId,
                    'petugas_id' => $petugasId,
                    'status' => 1,
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
        $perangkatIdsInLog = LogPetugas::pluck('perangkat_id')->unique()->toArray();

        foreach ($perangkatIdsInLog as $perangkatId) {
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
        $baseLat = -1.8467;
        $baseLong = 109.9719;
        
        $latVariation = (rand(-50, 50) / 1000);
        $longVariation = (rand(-50, 50) / 1000);

        $kualitasUdara = rand(30, 200);
        $status ='Aktif';

        $baseTemp = 27;
        if ($hour) {
            if ($hour >= 6 && $hour < 10) $tempVariation = rand(-2, 1);
            elseif ($hour >= 10 && $hour < 15) $tempVariation = rand(3, 8);
            elseif ($hour >= 15 && $hour < 18) $tempVariation = rand(1, 5);
            else $tempVariation = rand(-3, 2);
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
     * Seed insiden data
     */
    private function seedInsiden(): void 
    {
        $namaInsiden = [
            'Kebakaran Hutan',
            'Banjir',
            'Tanah Longsor',
            'Angin Puting Beliung',
            'Gempa Bumi'
        ];

        $lokasi = [
            'Kecamatan Delta Pawan',
            'Kecamatan Benua Kayong', 
            'Kecamatan Muara Pawan',
            'Kecamatan Kendawangan',
            'Kecamatan Matan Hilir Utara'
        ];

        for ($i = 0; $i < 10; $i++) {
            $createdAt = now()->subDays(rand(1, 30));
            
            Insiden::create([
                'nama_insiden' => $namaInsiden[array_rand($namaInsiden)],
                'lokasi' => $lokasi[array_rand($lokasi)],
                'keterangan' => 'Keterangan insiden ' . ($i + 1) . ' yang terjadi di wilayah Ketapang',
                'created_at' => $createdAt,
                'updated_at' => $createdAt->addHours(rand(1, 48))
            ]);
        }
    }
    /**
     * Seed insiden details data
     */
    private function seedInsidenDetails(): void
    {
        $insidenIds = Insiden::pluck('id')->toArray();
        $logPetugasIds = LogPetugas::where('status', 1)->pluck('id')->toArray();

        // Setiap insiden memiliki 1-3 detail
        foreach ($insidenIds as $insidenId) {
            $detailCount = rand(1, 3);
            
            for ($i = 0; $i < $detailCount; $i++) {
                if (count($logPetugasIds) > 0) {
                    $logPetugasId = $logPetugasIds[array_rand($logPetugasIds)];
                    
                    InsidenDetail::create([
                        'insiden_id' => $insidenId,
                        'LogPetugas_id' => $logPetugasId,
                        'deleted_at' => null,
                        'created_at' => now()->subDays(rand(0, 7)),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
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