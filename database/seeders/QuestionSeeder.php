<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'uid' => Str::uuid(),
                'question' => 'Peralatan',
                'is_service' => 1,
                'status' => 1,
                'description' => 'Toilet Ruangan 1',
                'icon' => 'fa fa-file'
            ],
            [
                'uid' => Str::uuid(),
                'question' => 'Kebersihan',
                'is_service' => 1,
                'status' => 1,
                'description' => 'Toilet Ruangan 1',
                'icon' => 'fa fa-file'
            ],
            [
                'uid' => Str::uuid(),
                'question' => 'How was your dining experience today?',
                'is_service' => 0,
                'status' => 1,
                'description' => '',
                'icon' => 'fa fa-file'
            ],
        ];

        DB::table('questions')->insert($data);
    }
}
