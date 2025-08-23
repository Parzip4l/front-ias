<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class EmployeeController extends Controller
{
    public function index()
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
                $employee = $response->json()['data'] ?? [];
            } else {
                $employee = [];
                // Bisa juga kirim flash message error
                return redirect()->route('login')->with('error', 'Session Habi, silakan login dulu.');
            }
            
        } catch (\Exception $e) {
            $employee = [];
            session()->flash('error', 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage());
        }

        return view('pages.usermanagement.employee.index', compact('employee'));
    }

    public function create()
    {
        $baseUrl = rtrim(env('SPPD_API_URL'), '/');
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }
        
        $company = [];
        $divisi = [];
        $posisi = [];

        try {
            $companyResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/company/list');

            if ($companyResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($companyResponse->successful()) {
                $company = $companyResponse->json()['data'] ?? [];
            } else {
                session()->flash('error', 'Gagal mengambil data.');
            }

            // ====== Ambil daftar Categoey ======
            $divisiResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/divisi/list');

            if ($divisiResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($divisiResponse->successful()) {
                $divisi = $divisiResponse->json()['data'] ?? [];
            } else {
                session()->flash('error', 'Gagal mengambil data.');
            }

            // Budget

            $posisiResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/posisi/list');

            if ($posisiResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($posisiResponse->successful()) {
                $posisi = $posisiResponse->json()['data'] ?? [];
            } else {
                session()->flash('error', 'Gagal mengambil data.');
            }

        } catch (\Exception $e) {
            // Tangani error exception, redirect balik dengan pesan error
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        return view('pages.usermanagement.employee.create', compact('company','divisi','posisi'));
    }

    public function store(Request $request)
    {
        // Validasi input 'name' wajib dan string maksimal 255 karakter
        $validated = $request->validate([
            'company_id'        => 'required',
            'employee_number'   => 'required|string|max:50',
            'name'              => 'required|string|max:100',
            'division_id'       => 'required',
            'position_id'       => 'required',
            'employment_status' => 'required|string',
            'grade_level'       => 'nullable|string|max:20',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/karyawan/store';
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
                    'company_id' => $validated['company_id'],
                    'employee_number' => $validated['employee_number'],
                    'division_id' => $validated['division_id'],
                    'position_id' => $validated['position_id'],
                    'employment_status' => $validated['employment_status'],
                    'grade_level' => $validated['grade_level'],
                ]);

            if ($response->status() == 401) {
                // Token expired atau tidak valid
                Session::forget('jwt_token'); // hapus token dari session
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('employee.index')->with('success', 'Data berhasil ditambahkan.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menyimpan Data.';
                return redirect()->route('employee.create')->with('error', $errorMessage);
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
            $company = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/company/list')
                ->json()['data'] ?? [];

            // Ambil daftar kategori
            $divisi = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/divisi/list')
                ->json()['data'] ?? [];

            $posisi = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/posisi/list')
                ->json()['data'] ?? [];

            // Ambil data budget spesifik lewat API single
            $employeeResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/karyawan/single/' . $id);

            if ($employeeResponse->status() == 404) {
                return redirect()->route('budget.index')->with('error', 'Data budget tidak ditemukan.');
            }

            $employee = $employeeResponse->successful() ? $employeeResponse->json()['data'] ?? [] : [];

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return view('pages.usermanagement.employee.edit', compact('company', 'divisi', 'posisi','employee'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'id' => 'required|integer',
            'company_id'        => 'required',
            'employee_number'   => 'required|string|max:50',
            'name'              => 'required|string|max:100',
            'division_id'       => 'required',
            'position_id'       => 'required',
            'employment_status' => 'required|string',
            'grade_level'       => 'nullable|string|max:20',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . "/karyawan/update";
        $token = Session::get('jwt_token');

        // Cek token
        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            // Kirim PUT request ke API
            $response = Http::withToken($token)
                ->accept('application/json')
                ->post($apiUrl, [
                    'id'                => $validated['id'],
                    'name'              => $validated['name'],
                    'company_id'        => $validated['company_id'],
                    'employee_number'   => $validated['employee_number'],
                    'division_id'       => $validated['division_id'],
                    'position_id'       => $validated['position_id'],
                    'employment_status' => $validated['employment_status'],
                    'grade_level'       => $validated['grade_level'],
                ]);

            if ($response->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('employee.index')->with('success', 'Data berhasil diperbarui.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal memperbarui data.';
                return redirect()->route('employee.edit', $request->id)->with('error', $errorMessage);
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

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/karyawan/delete';
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
                return redirect()->route('employee.index')->with('success', 'data berhasil dihapus.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menghapus data.';
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
