<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Models\Survey;
use App\Models\Template;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ResponseController extends Controller
{
    protected $page = 'response';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('survey.index', [
            'page' => $this->page,
            'title' => 'response',
        ]);
    }


    public function getData()
    {
        if (request()->ajax()) {
            $query = Response::query();
            $query = $query->with(['location', 'template']);
            $search = request()->input('filters') ?? [];
            $orderBy = request()->input('orderBy') != 0 ? request()->input('orderBy') : 'id';
            $asc = request()->input('ascending') == 'true' ? 'asc' : 'desc';

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    foreach ($search as $key => $value) {
                        $q->orWhere($key, 'like', '%' . $value . '%');
                    }
                });
            }

            $query->orderBy($orderBy, $asc);

            return DataTables::of($query)->make(true);
        }
    }

    public function storeFeedback(Request $request)
    {
        try {
            $response = new Response();
            $response->survey_id = $request->survey_id;
            $response->question_id = $request->question_id;
            $response->response_location_id = $request->location_id;
            $response->response_value = $request->response_value;
            $response->reason = $request->reason;

            $response->save();

            return response()->json([
                'status' => 'success',
                'msg' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'status' => 'error',
                'msg' => 'Data gagal disimpan'
            ]);
        }
    }
}
