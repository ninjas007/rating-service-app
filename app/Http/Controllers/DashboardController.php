<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $tgl = request()->get('tanggal') ?? Carbon::now()->format('Y-m-d');

        // 1️⃣ Berdasarkan lokasi
        $byLocation = DB::table('responses')
            ->leftJoin('locations', 'responses.response_location_id', '=', 'locations.id')
            ->select(DB::raw('COALESCE(locations.name, "Tanpa Lokasi") as location_name'), DB::raw('COUNT(*) as total'))
            ->whereDate('responses.created_at', $tgl)
            ->groupBy('location_name')
            ->get();

        $labelsLocation = $byLocation->pluck('location_name')->map(fn($name) => $name ?? 'Tanpa Lokasi');
        $dataLocation = $byLocation->pluck('total');

        // 2️⃣ Berdasarkan jam
        $byHour = DB::table('responses')
            ->select(DB::raw('HOUR(created_at) as jam'), DB::raw('COUNT(*) as total'))
            ->whereDate('created_at', $tgl)
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('jam')
            ->get();

        // isi jam kosong 00–23
        $labelsHour = [];
        $dataHour = [];
        for ($i = 0; $i < 24; $i++) {
            $label = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            $labelsHour[] = $label;
            $dataHour[$label] = 0;
        }
        foreach ($byHour as $row) {
            $label = str_pad($row->jam, 2, '0', STR_PAD_LEFT) . ':00';
            $dataHour[$label] = $row->total;
        }

        // 3️⃣ Donut rating (response_value)
        $byRating = DB::table('responses')
            ->select('response_value as rating', DB::raw('COUNT(*) as total'))
            ->whereDate('created_at', $tgl)
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();

        $ratingLabels = [1 => 'Tidak puas', 2 => 'Puas', 3 => 'Sangat puas'];

        $labelsRating = $byRating->pluck('rating')->map(fn($r) => $ratingLabels[$r] ?? 'Unknown');
        $dataRating   = $byRating->pluck('total');

        return view('dashboard.index', [
            'tgl' => $tgl,
            'page' => 'dashboard',
            'labelsLocation' => $labelsLocation,
            'dataLocation' => $dataLocation,
            'labelsHour' => array_keys($dataHour),
            'dataHour' => array_values($dataHour),
            'labelsRating' => $labelsRating,
            'dataRating' => $dataRating
        ]);
    }
}
