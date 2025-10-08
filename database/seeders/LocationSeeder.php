<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'uid' => Str::uuid(),
                'name' => 'Toilet',
                'description' => 'Toilet Ruangan 1',
                'status' => 1
            ],
            [
                'uid' => Str::uuid(),
                'name' => 'Area Parkir',
                'description' => 'Area Parkir Motor',
                'status' => 1
            ]
        ];

        DB::table('locations')->insert($data);
    }
}
