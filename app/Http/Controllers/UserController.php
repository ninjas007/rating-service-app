<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected $page = 'user';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.index', [
            'page' => $this->page,
            'title' => 'User',
            'roles' => $this->getRoles()
        ]);
    }

    // get data by ajax
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query()->where('role', '!=', 'admin')->where('id', '!=', auth()->user()->id);
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
        if (auth()->user()->role != 'admin' && $request->role == 'admin') {
            return redirect()->route('users.index')->with('error', 'Anda tidak memiliki akses untuk menambahkan admin');
        }

        $this->validate($request, [
            'name' => 'required',
            'password' => 'required',
            'role' => 'required',
            'status' => 'required'
        ], [
            'name.required' => 'Nama harus diisi',
            'password.required' => 'Password harus diisi',
            'role.required' => 'Role harus diisi',
            'status.required' => 'Status harus diisi'
        ]);

        if ($request->email) {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Email sudah terdaftar',
                ]);
            }
        }

        try {
            $request->merge([
                'uid' => Str::uuid(),
                'password' => bcrypt($request->password)
            ]);

            User::create($request->all());

            return response()->json([
                'status' => 'success',
                'msg' => 'User berhasil ditambahkan',
            ]);
        } catch (\Exception $e) {
            $this->logError($e);

            return response()->json([
                'status' => 'error',
                'msg' => 'User gagal ditambahkan',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uid)
    {
        if (auth()->user()->role != 'admin' && $request->role == 'admin') {
            return redirect()->route('users.index')->with('error', 'Anda tidak memiliki akses untuk menambahkan admin');
        }

        $this->validate($request, [
            'name' => 'required',
            'password' => 'required',
            'role' => 'required',
            'status' => 'required'
        ], [
            'name.required' => 'Nama harus diisi',
            'password.required' => 'Password harus diisi',
            'role.required' => 'Role harus diisi',
            'status.required' => 'Status harus diisi'
        ]);

        $user = User::where('uid', $uid)->firstOrFail();

        if ($request->email && $user->email != $request->email) {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Email sudah terdaftar',
                ]);
            }
        }

        try {
            $user->update($request->all());

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
        $user = User::where('uid', $uid)->firstOrFail();

        try {
            $user->delete();

            return response()
                ->json([
                    'status' => 'success',
                    'message' => 'Data berhasil dihapus'
                ]);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()
                ->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan, silahkan coba lagi'
                ]);
        }
    }

    private function getRoles()
    {
        $data = [
            [
                'id' => 'supervisor',
                'name' => 'Supervisor',
            ],
            [
                'id' => 'user',
                'name' => 'User',
            ],
        ];

        return collect($data);
    }
}
