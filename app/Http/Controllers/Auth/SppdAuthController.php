<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SppdApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SppdAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function ForgotPassword()
    {
        return view('auth.recoverpw');
    }

    public function doLogin(Request $request, SppdApiService $api)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $result = $api->login($request->email, $request->password);

        if (isset($result['access_token'])) {
            // Simpan token JWT di session
            Session::put('jwt_token', $result['access_token']);
            Session::put('user', $result['user']);
            return redirect()->route('home')->with('success', 'Login Berhasil');
        }

        return back()->withErrors([
            'email' => $result['message'] ?? 'Login gagal'
        ]);
    }

    public function logout()
    {
        // Hapus session token dan user
        Session::forget(['jwt_token', 'user']);
        Session::flush();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
