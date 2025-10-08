<?php

namespace Database\Seeders;

use App\Models\SettingGeneral;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingGeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'key' => 'nama_perusahaan',
                'value' => json_encode(['value' => 'BIO EXPERIENCE INDONESIA']), // 1,
            ],
            [
                'key' => 'alamat_perusahaan',
                'value' => json_encode(['value' => 'Jl. Buni Jakarta Barat'])
            ]
        ];

        SettingGeneral::insert($data);
    }
}
