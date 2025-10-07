<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SettingGeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(
            [
                'username' => 'required',
                'password' => 'required',
            ],
            [
                'username.required' => 'Username tidak boleh kosong.',
                'password.required' => 'Password tidak boleh kosong.',
            ]
        );

        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $setting = SettingGeneral::get()->pluck('value', 'key')->all();
            session()->put('setting', $setting);

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'Login gagal. Periksa kembali username dan password.',
        ]);
    }

    public function profile()
    {
        return view('profile.index', [
            'page' => 'profile',
            'title' => 'Profile'
        ]);
    }

    public function saveProfile(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'current_password' => 'required',
        ]);

        // Ambil user yang sedang login
        $user = User::find(Auth::id());

        $checkUsername = User::where('username', $request->username)->where('id', '!=', $user->id)->first();

        if ($checkUsername) {
            return back()->with('error', 'Username sudah digunakan.');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $user->name = $request->name;
        $user->username = $request->username;

        if ($request->new_password) {
            $request->validate([
                'new_password' => 'string|min:6',
            ]);
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
