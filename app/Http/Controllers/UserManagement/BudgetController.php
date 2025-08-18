<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class BudgetController extends Controller
{
    public function index()
    {
        $baseUrl = rtrim(env('SPPD_API_URL'), '/');
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }
        
        $jabatan = [];
        $category = [];
        $budget = [];

        try {
            $jabatanResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/posisi/list');

            if ($jabatanResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($jabatanResponse->successful()) {
                $jabatan = $jabatanResponse->json()['data'] ?? [];
            } else {
                session()->flash('error', 'Gagal mengambil data posisi.');
            }

            // ====== Ambil daftar Categoey ======
            $categoryResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/kategori-budget/list');

            if ($categoryResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($categoryResponse->successful()) {
                $category = $categoryResponse->json()['data'] ?? [];
            } else {
                session()->flash('error', 'Gagal mengambil data user.');
            }

            // Budget

            $budgetResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/budget/list');

            if ($budgetResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($budgetResponse->successful()) {
                $budget = $budgetResponse->json()['data'] ?? [];
            } else {
                session()->flash('error', 'Gagal mengambil data divisi.');
            }

        } catch (\Exception $e) {
            // Tangani error exception, redirect balik dengan pesan error
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        return view('pages.usermanagement.budget.index', compact('budget','jabatan','category'));
    }

    public function store(Request $request)
    {
        // Validasi input 'name' wajib dan string maksimal 255 karakter
        $validated = $request->validate([
            'position_id' => 'required',
            'category_id' => 'required',
            'type' => 'required',
            'max_budget' => 'required',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/budget/store';
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
                    'position_id' => $validated['position_id'],
                    'category_id' => $validated['category_id'],
                    'type' => $validated['type'],
                    'max_budget' => $validated['max_budget'],
                ]);

            if ($response->status() == 401) {
                // Token expired atau tidak valid
                Session::forget('jwt_token'); // hapus token dari session
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('budget.index')->with('success', 'Budget berhasil ditambahkan.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menyimpan Budget.';
                return back()->withInput()->with('error', $errorMessage);
            }

        } catch (\Exception $e) {
            // Tangani error exception, redirect balik dengan pesan error
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $baseUrl = rtrim(env('SPPD_API_URL'), '/');
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            // Ambil daftar posisi / jabatan
            $jabatan = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/posisi/list')
                ->json()['data'] ?? [];

            // Ambil daftar kategori
            $category = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/kategori-budget/list')
                ->json()['data'] ?? [];

            // Ambil data budget spesifik lewat API single
            $budgetResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/budget/single/' . $id);

            if ($budgetResponse->status() == 404) {
                return redirect()->route('budget.index')->with('error', 'Data budget tidak ditemukan.');
            }

            $budget = $budgetResponse->successful() ? $budgetResponse->json()['data'] ?? [] : [];

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return view('pages.usermanagement.budget.edit', compact('budget', 'jabatan', 'category'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'id' => 'required|integer',
            'position_id' => 'required',
            'category_id' => 'required',
            'type' => 'required',
            'max_budget' => 'required',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/budget/update';
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            // Kirim request ke API
            $response = Http::withToken($token)
                ->accept('application/json')
                ->post($apiUrl, [
                    'id' => $validated['id'],
                    'position_id' => $validated['position_id'],
                    'category_id' => $validated['category_id'],
                    'type' => $validated['type'],
                    'max_budget' => $validated['max_budget'],
                ]);

            if ($response->status() == 401) {
                // Token expired atau tidak valid
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('budget.index')->with('success', 'Budget berhasil diupdate.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal mengupdate Budget.';
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

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/budget/delete';
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
                return redirect()->route('budget.index')->with('success', 'Budget berhasil dihapus.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menghapus Budget.';
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
