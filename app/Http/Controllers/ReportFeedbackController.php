<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportFeedbackController extends Controller
{
    protected $page = 'report';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $today = $request->input('tanggal', Carbon::today()->toDateString());
        $data = $this->getData($today);

        return view('report_feedback.index', [
            'page' => $this->page,
            'title' => 'Laporan Kepuasan Pelanggan',
            'tanggal' => $today,
            'data' => $data,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $today = $request->input('tanggal', Carbon::today()->toDateString());
        $data = $this->getData($today);

        $filename = 'Laporan_Rating_' . Carbon::parse($today)->format('d_m_Y') . '.xlsx';

        // return Excel::download(new \App\Exports\RatingExport($data, $today), $filename);
    }

    public function exportPdf(Request $request)
    {
        $today = $request->input('tanggal', Carbon::today()->toDateString());
        $data = $this->getData($today);

        $pdf = Pdf::loadView('report_feedback.print', [
            'title' => 'Laporan Kepuasan Pelanggan',
            'tanggal' => $today,
            'data' => $data
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('Laporan_Rating_' . Carbon::parse($today)->format('d_m_Y') . '.pdf');
    }

    private function getData($tanggal)
    {
        $responses = DB::table('responses as r')
            ->join('locations as l', 'l.id', '=', 'r.response_location_id')
            ->select(
                'l.name as area',
                DB::raw("SUM(CASE WHEN r.response_value = 1 THEN 1 ELSE 0 END) as very_bad"),
                DB::raw("SUM(CASE WHEN r.response_value = 2 THEN 1 ELSE 0 END) as bad"),
                DB::raw("SUM(CASE WHEN r.response_value = 3 THEN 1 ELSE 0 END) as neutral"),
                DB::raw("SUM(CASE WHEN r.response_value = 4 THEN 1 ELSE 0 END) as good"),
                DB::raw("SUM(CASE WHEN r.response_value = 5 THEN 1 ELSE 0 END) as very_good"),
                DB::raw("COUNT(*) as total")
            )
            ->whereDate('r.created_at', $tanggal)
            ->groupBy('l.name')
            ->orderBy('l.name')
            ->get();

        return $responses;
    }
}
