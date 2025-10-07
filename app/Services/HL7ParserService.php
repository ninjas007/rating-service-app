<?php

// app/Services/HL7ParserService.php
namespace App\Services;

class HL7ParserService
{
    public static function parse($hl7)
    {
        $lines = explode("\r", $hl7);
        $segments = [];

        foreach ($lines as $line) {
            $fields = explode('|', $line);
            $segments[$fields[0]][] = $fields;
        }

        // Simpan contoh: PID, OBX, OBR
        $pid = $segments['PID'][0] ?? null;
        $obrs = $segments['OBR'] ?? [];
        $obxs = $segments['OBX'] ?? [];

        // Simpan ke database
        if ($pid) {
            \App\Models\Patient::firstOrCreate([
                'external_id' => $pid[3] ?? null,
            ], [
                'name' => $pid[5] ?? 'Unknown',
                'gender' => $pid[7] ?? 'Unknown',
            ]);
        }

        // Lanjutkan simpan hasil ke tabel lain
    }
}
