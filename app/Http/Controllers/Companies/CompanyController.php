<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index()
    {
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/company/list';
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
            session()->flash('error', 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage());
        }

        return view('pages.company.index', compact('company'));
    }

    public function create()
    {
        $baseUrl = rtrim(env('SPPD_API_URL'), '/');
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }
        
        $type = [];

        try {
            $typeCompany = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/company-type/list');

            if ($typeCompany->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($typeCompany->successful()) {
                $type = $typeCompany->json()['data'] ?? [];
            } else {
                session()->flash('error', 'Gagal mengambil data.');
            }

        } catch (\Exception $e) {
            // Tangani error exception, redirect balik dengan pesan error
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        return view('pages.company.create', compact('type'));
    }

    public function store(Request $request)
    {
        // Validasi input sesuai form
        $validated = $request->validate([
            'name'             => 'required|string|max:50',
            'customer_id'      => 'required|string',
            'email'            => 'required|email',
            'company_type_id'  => 'required|integer',
            'phone'            => 'required|string|max:20',
            'address'          => 'required|string',
            'zipcode'          => 'nullable|string|max:10',
            'is_pkp'           => 'required|boolean',
            'npwp_number'      => 'nullable|string|max:25',
            'npwp_file'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'sppkp_file'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'skt_file'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'is_active'        => 'nullable|boolean',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/company/store';
        $token  = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            $httpRequest = Http::withToken($token)->accept('application/json');

            // attach file jika ada
            if ($request->hasFile('npwp_file')) {
                $httpRequest->attach(
                    'npwp_file',
                    file_get_contents($request->file('npwp_file')->getRealPath()),
                    $request->file('npwp_file')->getClientOriginalName()
                );
            }

            if ($request->hasFile('sppkp_file')) {
                $httpRequest->attach(
                    'sppkp_file',
                    file_get_contents($request->file('sppkp_file')->getRealPath()),
                    $request->file('sppkp_file')->getClientOriginalName()
                );
            }

            if ($request->hasFile('skt_file')) {
                $httpRequest->attach(
                    'skt_file',
                    file_get_contents($request->file('skt_file')->getRealPath()),
                    $request->file('skt_file')->getClientOriginalName()
                );
            }

            // kirim payload utama
            $response = $httpRequest->post($apiUrl, [
                'name'            => $validated['name'],
                'customer_id'     => $validated['customer_id'],
                'email'           => $validated['email'],
                'company_type_id' => $validated['company_type_id'],
                'phone'           => $validated['phone'],
                'address'         => $validated['address'],
                'zipcode'         => $request->input('zipcode'),
                'is_pkp'          => $validated['is_pkp'],
                'npwp_number'     => $request->input('npwp_number'),
                'is_active'       => $request->input('is_active', 1),
            ]);

            if ($response->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('company.index')->with('success', 'Company berhasil ditambahkan.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menyimpan Company.';
                return back()->withInput()->with('error', $errorMessage);
            }

        } catch (\Exception $e) {
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
            $types = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/company-type/list')
                ->json()['data'] ?? [];

            // Ambil data budget spesifik lewat API single
            $companyResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/company/single/' . $id);

            if ($companyResponse->status() == 404) {
                return redirect()->route('company.index')->with('error', 'Data budget tidak ditemukan.');
            }

            $company = $companyResponse->successful() ? $companyResponse->json()['data'] ?? [] : [];

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return view('pages.company.edit', compact('company', 'types'));
    }

    public function single($id)
    {
        $baseUrl = rtrim(env('SPPD_API_URL'), '/');
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            // Ambil daftar posisi / jabatan
            $types = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/company-type/list')
                ->json()['data'] ?? [];

            // Ambil data budget spesifik lewat API single
            $companyResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/company/single/' . $id);

            if ($companyResponse->status() == 404) {
                return redirect()->route('company.index')->with('error', 'Data budget tidak ditemukan.');
            }

            $company = $companyResponse->successful() ? $companyResponse->json()['data'] ?? [] : [];

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return view('pages.company.single', compact('company', 'types'));
    }


    public function update(Request $request)
    {
        // Validasi input sesuai form
        $validated = $request->validate([
            'id'              => 'required|integer',
            'name'            => 'required|string|max:50',
            'customer_id'     => 'required|string',
            'email'           => 'required|email',
            'company_type_id' => 'required|integer',
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string',
            'zipcode'         => 'nullable|string|max:10',
            'is_pkp'          => 'required|boolean',
            'npwp_number'     => 'nullable|string|max:25',
            'npwp_file'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'sppkp_file'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'skt_file'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'is_active'       => 'nullable|boolean',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/company/update';
        $token  = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            $httpRequest = Http::withToken($token)->accept('application/json');

            // attach file jika ada
            if ($request->hasFile('npwp_file')) {
                $httpRequest->attach(
                    'npwp_file',
                    file_get_contents($request->file('npwp_file')->getRealPath()),
                    $request->file('npwp_file')->getClientOriginalName()
                );
            }

            if ($request->hasFile('sppkp_file')) {
                $httpRequest->attach(
                    'sppkp_file',
                    file_get_contents($request->file('sppkp_file')->getRealPath()),
                    $request->file('sppkp_file')->getClientOriginalName()
                );
            }

            if ($request->hasFile('skt_file')) {
                $httpRequest->attach(
                    'skt_file',
                    file_get_contents($request->file('skt_file')->getRealPath()),
                    $request->file('skt_file')->getClientOriginalName()
                );
            }

            // kirim payload utama
            $response = $httpRequest->post($apiUrl, [
                'id'              => $validated['id'],
                'name'            => $validated['name'],
                'customer_id'     => $validated['customer_id'],
                'email'           => $validated['email'],
                'company_type_id' => $validated['company_type_id'],
                'phone'           => $validated['phone'],
                'address'         => $validated['address'],
                'zipcode'         => $request->input('zipcode'),
                'is_pkp'          => $validated['is_pkp'],
                'npwp_number'     => $request->input('npwp_number'),
                'is_active'       => $request->input('is_active', 1),
            ]);

            if ($response->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('company.index')->with('success', 'Company berhasil diperbarui.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal mengupdate Company.';
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

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/posisi/delete';
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
                return redirect()->route('posisi.index')->with('success', 'jabatan berhasil dihapus.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menghapus jabatan.';
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
