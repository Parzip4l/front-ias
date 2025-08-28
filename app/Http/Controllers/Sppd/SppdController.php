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
        // $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/karyawan/list';
        // $token = Session::get('jwt_token');

        // if (!$token) {
        //     return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        // }
        
        // try {
        //     $response = Http::withToken($token)
        //         ->accept('application/json')
        //         ->get($apiUrl);
        //     if ($response->successful()) {
        //         // Ambil data user dari API response
        //         $pegawais = $response->json()['data'] ?? [];
        //     } else {
        //         $pegawais = [];
        //         // Bisa juga kirim flash message error
        //         session()->flash('error', 'Gagal mengambil data Sppd dari API.');
        //     }
            
        // } catch (\Exception $e) {
        //     $pegawais = [];
        //     session()->flash('error', 'Terjadi kesalahan saat mengambil data user: ' . $e->getMessage());
        // }
        return view('pages.sppd.create');
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

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'userid'               => 'required',
            'tujuan'                => 'required|string|max:255',
            'lokasi_tujuan'         => 'required|string|max:255',
            'tanggal_berangkat'     => 'required|date|after_or_equal:today',
            'tanggal_pulang'        => 'required|date|after_or_equal:tanggal_berangkat',
            'transportasi'          => 'nullable|string|max:255',
            'biaya_estimasi'        => 'nullable|numeric|min:0',
            'expenses'              => 'nullable|array',
            'expenses.*.kategori'   => 'required_with:expenses|string|max:100',
            'expenses.*.deskripsi'  => 'nullable|string|max:255',
            'expenses.*.jumlah'     => 'nullable|numeric|min:0',
            'expenses.*.bukti_file' => 'nullable|string',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/sppd/store';
        $token  = Session::get('jwt_token');

        // Cek token, kalau tidak ada redirect login
        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            // Kirim request ke API
            $response = Http::withToken($token)
                ->accept('application/json')
                ->post($apiUrl, $validated);

            if ($response->status() == 401) {
                // Token expired atau tidak valid
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('sppd.index')->with('success', 'SPPD berhasil dibuat.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal membuat SPPD.';
                return back()->withInput()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    public function schedulesaya()
    {
        return view('apps.calendar');
    }
}
