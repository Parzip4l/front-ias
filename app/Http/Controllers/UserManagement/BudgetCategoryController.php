<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;


class BudgetCategoryController extends Controller
{
    public function index()
    {
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/kategori-budget/list';
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
                $kategori = $response->json()['data'] ?? [];
            } else {
                $kategori = [];
                // Bisa juga kirim flash message error
                session()->flash('error', 'Gagal mengambil data Kategori Budget');
            }
            
        } catch (\Exception $e) {
            $kategori = [];
            session()->flash('error', 'Terjadi kesalahan saat mengambil data kategori budget: ' . $e->getMessage());
        }

        return view('pages.usermanagement.budget.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        // Validasi input 'name' wajib dan string maksimal 255 karakter
        $validated = $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/kategori-budget/store';
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
                ]);

            if ($response->status() == 401) {
                // Token expired atau tidak valid
                Session::forget('jwt_token'); // hapus token dari session
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('budget-category.index')->with('success', 'Kategori berhasil ditambahkan.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menyimpan Kategori.';
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
            'name' => 'required|string|max:50',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/kategori-budget/update';
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
                ]);

            if ($response->status() == 401) {
                // Token expired atau tidak valid
                Session::forget('jwt_token'); // hapus token dari session
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('budget-category.index')->with('success', 'Kategori berhasil diupdate.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal mengupdate Kategori.';
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

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/kategori-budget/delete';
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
                return redirect()->route('budget-category.index')->with('success', 'Kategori berhasil dihapus.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menghapus Kategori.';
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
