<?php

namespace App\Http\Controllers\UserManagement;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class UserListController extends Controller
{
    public function index()
    {
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/user/user-list';
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            $response = Http::withToken($token)
                ->accept('application/json')
                ->get($apiUrl);

            if ($response->successful()) {
                // Ambil data user dari API response
                $users = $response->json()['data'] ?? [];
            } else {
                $users = [];
                // Bisa juga kirim flash message error
                session()->flash('error', 'Gagal mengambil data user dari API.');
            }
        } catch (\Exception $e) {
            $users = [];
            session()->flash('error', 'Terjadi kesalahan saat mengambil data user: ' . $e->getMessage());
        }

        return view('user.list', compact('users'));
    }

    public function roles()
    {
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/user/role-list';
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            $response = Http::withToken($token)
                ->accept('application/json')
                ->get($apiUrl);

            if ($response->successful()) {
                // Ambil data user dari API response
                $users = $response->json()['data'] ?? [];
            } else {
                $users = [];
                // Bisa juga kirim flash message error
                session()->flash('error', 'Gagal mengambil data user dari API.');
            }
        } catch (\Exception $e) {
            $users = [];
            session()->flash('error', 'Terjadi kesalahan saat mengambil data user: ' . $e->getMessage());
        }

        return view('user.list', compact('users'));
    }
}
