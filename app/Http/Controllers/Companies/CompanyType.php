<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CompanyType extends Controller
{
    public function index()
    {
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/company-type/list';
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
                $company = $response->json()['data'] ?? [];
            } else {
                $company = [];
                // Bisa juga kirim flash message error
                session()->flash('error', 'Gagal mengambil data.');
            }
            
        } catch (\Exception $e) {
            $company = [];
            session()->flash('error', 'Terjadi kesalahan saat mengambil data : ' . $e->getMessage());
        }

        return view('pages.company.type.index', compact('company'));
    }

    public function store(Request $request)
    {
        // Validasi input 'name' wajib dan string maksimal 255 karakter
        $validated = $request->validate([
            'name' => 'required|string|max:25',
            'description' => 'required|string',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/company-type/store';
        $token = Session::get('jwt_token');

        // Cek token, jika tidak ada redirect ke login
        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            // Kirim POST request ke API dengan payload 'name'
            $response = Http::withToken($token)
                ->accept('application/json')
                ->post($apiUrl, [
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                ]);

            if ($response->status() == 401) {
                // Token expired atau tidak valid
                Session::forget('jwt_token'); // hapus token dari session
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('companytype.index')->with('success', 'Data berhasil ditambahkan.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menyimpan Data.';
                return back()->withInput()->with('error', $errorMessage);
            }

        } catch (\Exception $e) {
            // Tangani error exception, redirect balik dengan pesan error
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:25',
            'description' => 'required|string',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/company-type/update';
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            $response = Http::withToken($token)
                ->accept('application/json')
                ->post($apiUrl, [
                    'id' => $validated['id'],
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                ]);

            if ($response->status() == 401) {
                // Token expired atau tidak valid
                Session::forget('jwt_token'); // hapus token dari session
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('companytype.index')->with('success', 'Data berhasil diupdate.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal mengupdate Data.';
                return back()->withInput()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/company-type/delete';
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            // Kirim POST dengan form-data id ke API
            $response = Http::withToken($token)
                ->accept('application/json')
                ->post($apiUrl, ['id' => $request->id]);

            if ($response->status() == 401) {
                // Token expired atau tidak valid
                Session::forget('jwt_token'); // hapus token dari session
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('companytype.index')->with('success', 'data berhasil dihapus.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menghapus data.';
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
