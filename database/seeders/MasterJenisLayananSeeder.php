<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Master\MasterJenisLayanan;
use Illuminate\Support\Str;

class MasterJenisLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'uid' => Str::uuid(),
                'name' => 'Reguler',
                'description' => ''
            ],
            [
                'uid' => Str::uuid(),
                'name' => 'VIP',
                'description' => ''
            ],
        ];

        MasterJenisLayanan::insert($data);
    }
}
