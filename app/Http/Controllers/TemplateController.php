<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    protected $page = 'template';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('template.index', [
            'page' => $this->page,
            'title' => 'template',
        ]);
    }

    public function getData()
    {
        if (request()->ajax()) {
            $query = Template::query();
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
            // 'bg_image_path' => 'required',
            // 'bg_color' => 'required',
            // 'bg_running_text' => 'required',
            // 'running_text' => 'required',
            // 'running_text_color' => 'required',
            // 'running_text_speed' => 'required',
            // 'logo_template_path' => 'required',
            'status' => 'required',
        ], [
            'name.required' => 'Nama harus diisi',
            'status' => 'Status harus diisi',
            // 'bg_image_path.required' => 'Background Image harus diisi',
            // 'bg_color.required' => 'Background Color harus diisi',
        ]);

        try {
            $template = new Template();
            $template->uid = Str::uuid();
            $template->name = $request->name;
            $template->bg_color = $request->bg_color;
            $template->bg_running_text = $request->bg_running_text;
            $template->running_text = $request->running_text;
            $template->running_text_color = $request->running_text_color;
            $template->running_text_speed = $request->running_text_speed;
            $template->logo_template_path = $this->uploadFile($request, 'logo_template_path');
            $template->bg_image_path = $this->uploadFile($request, 'bg_image_path');
            $template->status = $request->status;
            $template->created_at = now();
            $template->updated_at = now();
            $template->save();

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
            'status' => 'required',
        ], [
            'name.required' => 'Nama harus diisi',
            'status.required' => 'Status harus diisi',
        ]);

        try {
            $template = Template::where('uid', $uid)->firstOrFail();

            $template->name = $request->name;
            $template->bg_color = $request->bg_color;
            $template->bg_running_text = $request->bg_running_text;
            $template->running_text = $request->running_text;
            $template->running_text_color = $request->running_text_color;
            $template->running_text_speed = $request->running_text_speed;
            $template->status = $request->status;
            $template->updated_at = now();

            // ✅ hanya update jika ada file diupload
            if ($request->hasFile('logo_template_path')) {
                $template->logo_template_path = $this->uploadFile($request, 'logo_template_path');
            }

            if ($request->hasFile('bg_image_path')) {
                $template->bg_image_path = $this->uploadFile($request, 'bg_image_path');
            }

            $template->save();

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
        $template = Template::where('uid', $uid)->first();
        if (!$template) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }

        try {
            $result = $template->delete();

            // Delete file
            if ($result && $template->bg_image_path) {
                unlink(public_path($template->bg_image_path));
            }
            if ($result && $template->logo_template_path) {
                unlink(public_path($template->logo_template_path));
            }

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan, silahkan coba lagi']);
        }
    }

    private function uploadFile($request, $field)
    {
        if ($request->hasFile($field)) {
            $file = $request->file($field);
            $fileName = Str::uuid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filepath = public_path('storage/images');
            $file->move($filepath, $fileName);
            return 'storage/images/' . $fileName;
        }

        return null;
    }
}
