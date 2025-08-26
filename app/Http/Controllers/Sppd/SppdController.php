<?php

namespace App\Http\Controllers\Sppd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class SppdController extends Controller
{
    public function index()
    {
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/sppd/list';
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
                $sppds = $response->json()['data'] ?? [];
            } else {
                $sppds = [];
                // Bisa juga kirim flash message error
                session()->flash('error', 'Gagal mengambil data Sppd dari API.');
            }
            
        } catch (\Exception $e) {
            $sppds = [];
            session()->flash('error', 'Terjadi kesalahan saat mengambil data user: ' . $e->getMessage());
        }
        return view('pages.sppd.index', compact('sppds'));
    }

    public function create()
    {
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/karyawan/list';
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
                $pegawais = $response->json()['data'] ?? [];
            } else {
                $pegawais = [];
                // Bisa juga kirim flash message error
                session()->flash('error', 'Gagal mengambil data Sppd dari API.');
            }
            
        } catch (\Exception $e) {
            $pegawais = [];
            session()->flash('error', 'Terjadi kesalahan saat mengambil data user: ' . $e->getMessage());
        }
        return view('pages.sppd.create', compact('pegawais'));
    }

    public function preview($id)
    {
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . "/sppd/details/{$id}";
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            $response = Http::withToken($token)
                ->accept('application/json')
                ->get($apiUrl);

            if ($response->successful()) {
                $sppd = $response->json()['data'] ?? null;

                if (!$sppd) {
                    return redirect()->back()->with('error', 'Data SPPD tidak ditemukan.');
                }

                // Langsung oper $sppd ke Blade
                return view('pages.sppd.preview', compact('sppd'));
            } else {
                return redirect()->back()->with('error', 'Gagal mengambil data SPPD dari API.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage());
        }
    }

    public function schedulesaya()
    {
        return view('apps.calendar');
    }
}
