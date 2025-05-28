<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Perangkat extends Model
{
    protected  $table = 'perangkat';

    protected $fillable = [
        'id',
        'no_seri',
        'qr_code',
        'status',
        'kondisi',


    ];

    public $incrementing = false; // Karena ID bukan auto-increment
    protected $keyType = 'string'; // Karena ID berbentuk string



    public static function generateNoSeri()
    {
        do {
            $yearMonth = date('Ym');
            $randomCode = strtoupper(string: Str::random(6));
            $noSeri = "SN-$yearMonth-$randomCode";
        } while (self::where('no_seri', $noSeri)->exists());

        return $noSeri;
    }

    public function generateBarcode()
    {
        $path = storage_path("app/public/barcodes/{$this->no_seri}.png");

        // Buat folder jika belum ada
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        // Generate QR Code dan simpan sebagai gambar
        QrCode::format('png')
            ->size(300) // Ukuran QR Code
            ->generate($this->no_seri, $path);

        return "barcodes/{$this->no_seri}.png";
    }


     public function logPerangkat()
    {
        return $this->hasMany(LogPerangkat::class, 'id_perangkat', 'id');
    }


}
