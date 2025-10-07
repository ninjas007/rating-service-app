<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Models\Master\MasterRuangan as Ruangan;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class RuanganController extends Controller
{
    protected $page = 'ruangan';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ruangan.index', [
            'page' => $this->page,
            'title' => 'ruangan',
        ]);
    }

    public function getData()
    {
        if (request()->ajax()) {
            $query = Ruangan::query();
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
        ], [
            'name.required' => 'Nama ruangan harus diisi',
        ]);

        try {
            $ruangan = new Ruangan();
            $ruangan->uid = Str::uuid();
            $ruangan->name = $request->name;
            $ruangan->description = $request->description;
            $ruangan->created_at = now();
            $ruangan->updated_at = now();
            $ruangan->save();

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
            'name' => 'required',
        ], [
            'name.required' => 'Nama ruangan harus diisi',
        ]);

        try {
            $ruangan = Ruangan::where('uid', $uid)->firstOrFail();
            $ruangan->name = $request->name;
            $ruangan->description = $request->description;
            $ruangan->updated_at = now();
            $ruangan->save();

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
        $ruangan = Ruangan::where('uid', $uid)->first();
        if (!$ruangan) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }

        try {
            $ruangan->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan, silahkan coba lagi']);
        }
    }
}
