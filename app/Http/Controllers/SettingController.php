<?php

namespace App\Http\Controllers;

use App\Models\SettingGeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{
    private $page = 'sistem';

    public function index()
    {
        $settingGeneral = SettingGeneral::all();

        if ($settingGeneral) {
            $setting = $settingGeneral;
        } else {
            // running seeder
            Artisan::call('db:seed --class=SettingGeneralSeeder');
            $setting = SettingGeneral::first();
        }

        $setting = $setting->map(function ($item) {
            return [
                'key' => $item->key,
                'value' => json_decode($item->value)
            ];
        });

        return view('setting.index', [
            'page' => $this->page,
            'title' => 'Setting',
            'setting' => $setting
        ]);
    }

    public function saveGeneral(Request $request)
    {
        try {
            if ($request->has('logo')) {
                $this->saveLogo($request);
            }

            if ($request->has('background')) {
                $this->saveBackground($request);
            }

            if ($request->has('gambar')) {
                $this->saveGambarLogin($request);
            }

            if ($request->has('logo_laporan')) {
                $this->saveLogoLaporan($request);
            }

            if ($request->has('logors')) {
                $this->saveLogoCompany($request);
            }

            // except token
            $data = $request->except('_token');
            $data = collect($data);
            foreach ($data as $key => $value) {
                SettingGeneral::updateOrCreate(
                    ['key' => $key],
                    ['value' => json_encode([
                        'value' => $value
                    ])]
                );
            }

            $setting = SettingGeneral::get()->pluck('value', 'key')->all();
            session()->put('setting', $setting);

            // call artisan
            Artisan::call('cache:clear');

            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            $this->logError($e);

            return redirect()->back()->with('error', 'Data gagal disimpan.');
        }
    }

    private function saveLogo($request)
    {
        $this->validate($request, [
            'logo' => 'image|mimes:png|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logo->move(public_path('images'), 'logo.png');
        }
    }

    private function saveBackground($request)
    {
        $this->validate($request, [
            'background' => 'image|mimes:png|max:2048',
        ]);

        if ($request->hasFile('background')) {
            $background = $request->file('background');
            $background->move(public_path('images'), 'background.png');
        }
    }

    private function saveGambarLogin($request)
    {
        $this->validate($request, [
            'gambar' => 'image|mimes:png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $gambar->move(public_path('images'), 'gambar-login.png');
        }
    }

    private function saveLogoLaporan($request)
    {
        $this->validate($request, [
            'logo_laporan' => 'image|mimes:png|max:2048',
        ]);

        if ($request->hasFile('logo_laporan')) {
            $logo_laporan = $request->file('logo_laporan');
            $logo_laporan->move(public_path('images'), 'logo-laporan.png');
        }
    }

    private function saveLogoCompany($request)
    {
        $this->validate($request, [
            'logors' => 'image|mimes:png|max:2048',
        ]);

        if ($request->hasFile('logors')) {
            $logors = $request->file('logors');
            $logors->move(public_path('images'), 'logors.png');
        }
    }
}
