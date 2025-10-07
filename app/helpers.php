<?php

use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

if (!function_exists('decrypt_text')) {
    function decrypt_text($encrypted)
    {
        $key = substr(hash('sha256', config('app.key')), 0, 32);
        $iv  = substr(hash('sha256', 'my_secret_iv'), 0, 16);

        $decryptedHex = openssl_decrypt(
            base64_decode($encrypted),
            "AES-256-CBC",
            $key,
            0,
            $iv
        );

        return hex2bin($decryptedHex); // baru dikembalikan ke string asli
    }
}

if (!function_exists('encrypt_text')) {
    function encrypt_text($plain)
    {
        $key = substr(hash('sha256', config('app.key')), 0, 32); // ambil 32 karakter
        $iv  = substr(hash('sha256', 'my_secret_iv'), 0, 16);

        // ubah plain ke hex supaya aman dipakai
        $plainHex = bin2hex($plain);

        return base64_encode(
            openssl_encrypt($plainHex, "AES-256-CBC", $key, 0, $iv)
        );
    }
}
