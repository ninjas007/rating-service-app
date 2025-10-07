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

if (! function_exists('hitungReferenceRange')) {
    function hitungReferenceRange($pemeriksaanDetail, $pasien)
    {
        // hitung usia pasien dalam hari
        $usiaHari = $pasien->birth_date
            ? Carbon::parse($pasien->birth_date)->diffInDays(now())
            : 0;

        // normalisasi gender
        $jenisKelaminPasien = strtolower($pasien->gender == 'Male' ? 'laki-laki' : 'perempuan');

        $rujukanList = $pemeriksaanDetail->labParameter->rujukan ?? collect();

        // cari referensi sesuai usia (hari) & jenis kelamin
        $rujukan = $rujukanList->first(function ($item) use ($usiaHari, $jenisKelaminPasien) {
            $cocokUsia = $item->from_age <= $usiaHari && $item->to_age >= $usiaHari;

            if (is_null($item->jenis_kelamin)) {
                return $cocokUsia;
            }

            return strtolower($item->jenis_kelamin) === $jenisKelaminPasien && $cocokUsia;
        });

        if ($rujukan) {
            if (!is_null($rujukan->nr1) && !is_null($rujukan->nr2)) {
                return $rujukan->nr1 . '-' . $rujukan->nr2;
            } else {
                return $rujukan->nr1;
            }
        }

        // fallback ke default_ref_range
        return $pemeriksaanDetail->labParameter->default_ref_range ?? '';
    }
}


/**
 * Format hasil berdasarkan reference range.
 *
 * Aturan:
 * - Jika reference adalah range (e.g. "13-20")
 *   - Jika endpoint range punya decimal -> format sesuai decimal terbanyak pada endpoint
 *   - Jika endpoint range bulat -> tampilkan result apa adanya (jangan di-round)
 * - Jika reference adalah single (e.g. "<42", ">51" atau "42")
 *   - Jika reference punya decimal -> format sesuai decimal
 *   - Jika reference bulat -> jika result ada decimal -> batasi max 3 decimal, jika bulat -> tampil bulat
 *
 * @param  mixed  $val  (numeric)
 * @param  string|null $ref
 * @param  int $maxDecimalsForSingle  (default 3) - maksimal decimal untuk case single integer ref
 * @return string
 */
if (! function_exists('formatHasilDenganReference')) {
    function formatHasilDenganReference($val, $ref, $maxDecimalsForSingle = 3)
    {
        // hitung decimal places
        $getDecimalPlaces = function ($num) {
            if (strpos($num, '.') !== false) {
                return strlen(substr(strrchr($num, '.'), 1));
            }
            return 0;
        };

        // helper truncate tanpa round
        $truncateToDecimals = function ($num, $decimals) {
            $factor = pow(10, $decimals);
            return floor($num * $factor) / $factor;
        };

        if ($val === null || !is_numeric($val)) {
            return (string) $val;
        }

        $refStr = trim((string) ($ref ?? ''));
        preg_match_all('/\d+(?:\.\d+)?/', $refStr, $numMatches);
        $numbers = $numMatches[0] ?? [];

        $decimalsInRef = 0;
        foreach ($numbers as $n) {
            $d = $getDecimalPlaces($n);
            if ($d > $decimalsInRef) $decimalsInRef = $d;
        }

        $isRange = (bool) preg_match('/\d+(?:\.\d+)?\s*-\s*\d+(?:\.\d+)?/', $refStr);
        $isSingleComparator = (bool) preg_match('/^[\s]*[<>]=?[\s]*\d+(?:\.\d+)?[\s]*$/', $refStr);
        $isSingleNumber = !$isRange && !$isSingleComparator && preg_match('/^\s*\d+(?:\.\d+)?\s*$/', $refStr);

        $valFloat = (float) $val;
        $valStr = (string) $val;
        $valHasDecimal = strpos($valStr, '.') !== false;

        // CASE: range
        if ($isRange) {
            if ($decimalsInRef > 0) {
                $truncated = $truncateToDecimals($valFloat, $decimalsInRef);
                return number_format($truncated, $decimalsInRef, '.', '');
            } else {
                if ($valHasDecimal) {
                    return rtrim(rtrim($valStr, '0'), '.');
                }
                return (string) intval($valFloat);
            }
        }

        // CASE: single comparator/number
        if ($isSingleComparator || $isSingleNumber) {
            if ($decimalsInRef > 0) {
                $truncated = $truncateToDecimals($valFloat, $decimalsInRef);
                return number_format($truncated, $decimalsInRef, '.', '');
            } else {
                if ($valHasDecimal) {
                    $actualValDecimals = strlen($valStr) - (strpos($valStr, '.') + 1);
                    if ($actualValDecimals <= $maxDecimalsForSingle) {
                        return rtrim(rtrim($valStr, '0'), '.');
                    }
                    $truncated = $truncateToDecimals($valFloat, $maxDecimalsForSingle);
                    return number_format($truncated, $maxDecimalsForSingle, '.', '');
                } else {
                    return (string) intval($valFloat);
                }
            }
        }

        // DEFAULT fallback
        if ($decimalsInRef > 0) {
            $truncated = $truncateToDecimals($valFloat, $decimalsInRef);
            return number_format($truncated, $decimalsInRef, '.', '');
        }

        if ($valHasDecimal) {
            return rtrim(rtrim($valStr, '0'), '.');
        }

        return (string) intval($valFloat);
    }
}
