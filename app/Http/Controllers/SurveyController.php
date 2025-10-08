<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Question;
use App\Models\Survey;
use App\Models\SurveyDetail;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class SurveyController extends Controller
{
    protected $page = 'survey';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('survey.index', [
            'page' => $this->page,
            'title' => 'question',
            'locations' => Location::where('status', 1)->get(),
            'templates' => Template::where('status', 1)->get(),
            'questions' => Question::where('status', 1)->get(),
        ]);
    }

    public function details($surveyId)
    {
        $survey = Survey::where('uid', $surveyId)->first();

        return response()->json(
            SurveyDetail::where('survey_id', $survey->id)
                ->get(['question_id'])
        );
    }

    public function getData()
    {
        if (request()->ajax()) {
            $query = Survey::query();
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ], [
            'name.required' => 'Nama harus diisi',
            'status' => 'Status harus diisi',
        ]);

        try {
            DB::beginTransaction();

            $survey = new Survey();
            $survey->uid = Str::uuid();
            $survey->name = $request->name;
            $survey->description = $request->description;
            $survey->template_id = $request->template;
            $survey->location_id = $request->location;
            $survey->type = $request->type;
            $survey->status = $request->status;
            $survey->created_at = now();
            $survey->updated_at = now();
            $survey->save();

            if ($request->type == 'single') {
                SurveyDetail::create([
                    'survey_id' => $survey->id,
                    'question_id' => $request->question_id,
                ]);
            } else {
                foreach ($request->question_ids as $qid) {
                    SurveyDetail::create([
                        'survey_id' => $survey->id,
                        'question_id' => $qid,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'msg' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e);
            return response()->json([
                'status' => 'error',
                'msg' => 'Data gagal disimpan'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uid)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ], [
            'name.required' => 'Nama harus diisi',
            'status.required' => 'Status harus diisi',
        ]);

        try {
            DB::beginTransaction();
            $survey = Survey::where('uid', $uid)->firstOrFail();
            $survey->name = $request->name;
            $survey->description = $request->description;
            $survey->template_id = $request->template;
            $survey->location_id = $request->location;
            $survey->type = $request->type;
            $survey->status = $request->status;
            $survey->updated_at = now();
            $survey->save();

            // Hapus dulu detail lama
            SurveyDetail::where('survey_id', $survey->id)->delete();

            if ($request->type == 'single') {
                SurveyDetail::create([
                    'survey_id' => $survey->id,
                    'question_id' => $request->question_id,
                ]);
            } else {
                foreach ($request->question_ids as $qid) {
                    SurveyDetail::create([
                        'survey_id' => $survey->id,
                        'question_id' => $qid,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'msg' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e);
            return response()->json([
                'status' => 'error',
                'msg' => 'Data gagal disimpan'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uid)
    {
        $survey = Survey::where('uid', $uid)->first();
        if (!$survey) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }

        try {
            $survey->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan, silahkan coba lagi']);
        }
    }

    public function preview($uid)
    {
        $survey = Survey::where('uid', $uid)->first();

        if (!$survey) {
            abort(404);
        }

        $survey = $survey->load(['template', 'location', 'details.question']);

        if ($survey->type == 'single') {
            return view('survey.preview-single', [
                'survey' => $survey
            ]);
        }

        return view('survey.preview-multiple', [
            'survey' => $survey
        ]);
    }
}
