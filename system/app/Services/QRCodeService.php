<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class QRCodeService
{
    public function validate($qrcode): bool|string
    {
        if (!is_string($qrcode)) {
            Log::error('Invalid QR Code type: ' . json_encode($qrcode));
            return "Format QR Code tidak valid!";
        }

        $qrcode = trim($qrcode);
        if (empty($qrcode)) {
            Log::error('Empty QR Code detected');
            return "QR Code tidak boleh kosong!";
        }

        if (strlen($qrcode) < 3) {
            Log::error('QR Code too short: ' . $qrcode);
            return "QR Code terlalu pendek! Minimal 3 karakter.";
        }

        if (strlen($qrcode) > 50) {
            Log::error('QR Code too long: ' . $qrcode);
            return "QR Code terlalu panjang! Maksimal 50 karakter.";
        }

        if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $qrcode)) {
            Log::error('Invalid QR Code format: ' . $qrcode);
            return "Format QR Code tidak valid! Hanya huruf, angka, dash (-), dan underscore (_).";
        }

        return true;
    }
}
