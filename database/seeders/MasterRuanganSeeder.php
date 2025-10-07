<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Master\MasterRuangan;

class MasterRuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'uid' => Str::uuid(),
                'name' => 'Poli',
                'description' => 'Ruang A untuk pemeriksaan umum'
            ],
        ];

        MasterRuangan::insert($data);
    }
}
