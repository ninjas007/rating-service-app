<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    protected $page = 'question';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('question.index', [
            'page' => $this->page,
            'title' => 'question',
        ]);
    }

    public function getData()
    {
        if (request()->ajax()) {
            $query = Question::query();
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
            'question' => 'required',
            'is_service' => 'required',
        ], [
            'question.required' => 'Question harus diisi',
            'is_service.required' => 'Kategori harus diisi',
        ]);

        try {
            $question = new Question();
            $question->uid = Str::uuid();
            $question->question = $request->question;
            $question->is_service = $request->is_service;
            $question->status = $request->status;
            $question->created_at = now();
            $question->updated_at = now();
            $question->save();

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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uid)
    {
        $this->validate($request, [
            'question' => 'required',
            'is_service' => 'required',
        ], [
            'question.required' => 'Question harus diisi',
            'is_service.required' => 'Kategori harus diisi',
        ]);

        try {
            $question = Question::where('uid', $uid)->firstOrFail();
            $question->question = $request->question;
            $question->description = $request->description;
            $question->status = $request->status;
            $question->is_service = $request->is_service;
            $question->updated_at = now();
            $question->save();

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uid)
    {
        $question = Question::where('uid', $uid)->first();
        if (!$question) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }

        try {
            $question->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan, silahkan coba lagi']);
        }
    }
}
