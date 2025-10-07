<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    protected $page = 'location';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('location.index', [
            'page' => $this->page,
            'title' => 'location',
        ]);
    }

    public function getData()
    {
        if (request()->ajax()) {
            $query = Location::query();
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
            'name.required' => 'Nama location harus diisi',
        ]);

        try {
            $location = new Location();
            $location->uid = Str::uuid();
            $location->name = $request->name;
            $location->description = $request->description;
            $location->status = $request->status;
            $location->created_at = now();
            $location->updated_at = now();
            $location->save();

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
            'name.required' => 'Nama lokasi harus diisi',
        ]);

        try {
            $location = Location::where('uid', $uid)->firstOrFail();
            $location->name = $request->name;
            $location->description = $request->description;
            $location->status = $request->status;
            $location->updated_at = now();
            $location->save();

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
        $location = Location::where('uid', $uid)->first();
        if (!$location) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }

        try {
            $location->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan, silahkan coba lagi']);
        }
    }
}
