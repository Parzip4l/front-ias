<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    // Get User
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

        return view('pages.usermanagement.userlist', compact('users'));
    }

    // Create user
    public function create()
    {
        $baseUrl = rtrim(env('SPPD_API_URL'), '/');
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        $divisi = [];
        $role = [];

        try {
            // ====== Ambil daftar divisi ======
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
                session()->flash('error', 'Gagal mengambil data divisi.');
            }

            // ====== Ambil daftar user ======
            $roleResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/user/role-list');

            if ($roleResponse->successful()) {
                $role = $roleResponse->json()['data'] ?? [];
            } else {
                session()->flash('error', 'Gagal mengambil data roles.');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        return view('pages.usermanagement.createuser', compact('divisi', 'role'));
    }

    // Store User
    public function storeUser(Request $request)
    {
        // Validasi input 'name' wajib dan string maksimal 255 karakter
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email',
            'divisi_id' => 'required',
            'role' => 'required',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/user/create-user';
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
                    'email' => $validated['email'],
                    'divisi_id' => $validated['divisi_id'],
                    'role' => $validated['role'],
                ]);

            if ($response->status() == 401) {
                // Token expired atau tidak valid
                Session::forget('jwt_token'); // hapus token dari session
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menyimpan user.';

                // Jika API mengirimkan array error seperti validasi Laravel
                if (isset($response->json()['errors'])) {
                    $errors = $response->json()['errors'];
                    // Gabungkan semua pesan error jadi string
                    $errorMessage = collect($errors)->flatten()->implode(' ');
                }

                return back()->withInput()->with('error', $errorMessage);
            }


        } catch (\Exception $e) {
            // Tangani error exception, redirect balik dengan pesan error
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Roles Section

    // 1. Get Roles
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

            if ($response->status() == 401) {
                // Token expired atau tidak valid
                Session::forget('jwt_token'); // hapus token dari session
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                $users = $response->json()['data'] ?? [];
            } else {
                $users = [];
                session()->flash('error', 'Gagal mengambil data user dari API.');
            }
            
        } catch (\Exception $e) {
            $users = [];
            session()->flash('error', 'Terjadi kesalahan saat mengambil data user: ' . $e->getMessage());
        }

        return view('pages.usermanagement.rolelist', compact('users'));
    }

    // 2. SImpan Roles
    public function storeRoles(Request $request)
    {
        // Validasi input 'name' wajib dan string maksimal 255 karakter
        $validated = $request->validate([
            'name' => 'required|string|max:15',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/user/role-create';
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
                return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menyimpan role.';
                return back()->withInput()->with('error', $errorMessage);
            }

        } catch (\Exception $e) {
            // Tangani error exception, redirect balik dengan pesan error
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 3. Update Roles
    public function updateRole(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:15',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/user/role-update';
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
                return redirect()->route('roles.index')->with('success', 'Role berhasil diupdate.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal mengupdate role.';
                return back()->withInput()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 4. Hapus Roles
    public function destroyRole(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/user/role-delete';
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
                return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menghapus role.';
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Divisi Section

    // 1. Get Divisi
    public function divisi()
    {
        $baseUrl = rtrim(env('SPPD_API_URL'), '/');
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        $divisi = [];
        $users = [];
        $company = [];

        try {
            // ====== Ambil daftar divisi ======
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
                session()->flash('error', 'Gagal mengambil data divisi.');
            }

            // ====== Ambil daftar user ======
            $userResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/user/user-list');

            if ($userResponse->successful()) {
                $users = $userResponse->json()['data'] ?? [];
            } else {
                session()->flash('error', 'Gagal mengambil data user.');
            }

            $companyResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/company/list');

            if ($companyResponse->successful()) {
                $companies = $companyResponse->json()['data'] ?? [];
            } else {
                session()->flash('error', 'Gagal mengambil data user.');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        return view('pages.usermanagement.divisi.index', compact('divisi', 'users','companies'));
    }

    // 2. SImpan Roles
    public function storeDivisi(Request $request)
    {
        // Validasi input 'name' wajib dan string maksimal 255 karakter
        $validated = $request->validate([
            'name' => 'required|string|max:25',
            'head_id' => 'required|integer|max_digits:3',
            'company_id' => 'required',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/divisi/store';
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
                    'head_id' => $validated['head_id'],
                    'company_id' => $validated['company_id'],
                ]);

            if ($response->status() == 401) {
                // Token expired atau tidak valid
                Session::forget('jwt_token'); // hapus token dari session
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('divisi.index')->with('success', 'Divisi berhasil ditambahkan.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menyimpan divisi.';
                return back()->withInput()->with('error', $errorMessage);
            }

        } catch (\Exception $e) {
            // Tangani error exception, redirect balik dengan pesan error
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 3. Update Roles
    public function updateDivisi(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:25',
            'head_id' => 'required|integer|max_digits:3',
            'company_id' => 'required',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/divisi/update';
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
                    'head_id' => $validated['head_id'],
                    'company_id' => $validated['company_id'],
                ]);

            if ($response->status() == 401) {
                // Token expired atau tidak valid
                Session::forget('jwt_token'); // hapus token dari session
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('divisi.index')->with('success', 'Divisi berhasil diupdate.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal mengupdate Divisi.';
                return back()->withInput()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 4. Hapus Roles
    public function destroyDivisi(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/divisi/delete';
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
                return redirect()->route('divisi.index')->with('success', 'divisi berhasil dihapus.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menghapus divisi.';
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
