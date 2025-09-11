<?php

namespace App\Http\Controllers\Sppd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ReimbursementController extends Controller
{
    public function index()
    {
        $baseUrl = rtrim(env('SPPD_API_URL'), '/');
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }
        
        $reimbursement = [];

        try {
            $reimbursementResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/reimbursement/index');

            if ($reimbursementResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }
            $reimbursement = $reimbursementResponse->json('data') ?? [];
        } catch (\Exception $e) {
            // Tangani error exception, redirect balik dengan pesan error
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        return view('pages.reimbursement.index', compact('reimbursement'));
    }

    public function create(Request $request)
    {
        $baseUrl = rtrim(env('SPPD_API_URL'), '/');
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        $sppds = [];
        $category = [];

        try {
            $sppdsResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/sppd/need-reimbursement');

            if ($sppdsResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            $sppds = $sppdsResponse->json('data') ?? [];

            $categoryResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/reimbursement/category/index');

            if ($categoryResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            $category = $categoryResponse->json('data') ?? [];
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        // jangan dd(), langsung lempar ke view
        return view('pages.reimbursement.create', compact('sppds','category'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'sppd_id'     => 'required|integer',
            'title'       => 'required|string|max:255',
            'category_id' => 'required|integer',
            'description' => 'required|string',
            'amount'      => 'required|numeric',
            'files'       => 'required|array',
            'files.*'     => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/reimbursement/store';
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            $requestHttp = Http::withToken($token)->accept('application/json');

            foreach ($request->file('files') as $file) {
                $requestHttp->attach(
                    "files[]", file_get_contents($file), $file->getClientOriginalName()
                );
            }

            $response = $requestHttp->post($apiUrl, [
                'sppd_id'     => $validated['sppd_id'],
                'category_id'     => $validated['category_id'],
                'title'       => $validated['title'],
                'description' => $validated['description'],
                'amount'      => $validated['amount'],
            ]);

            if ($response->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('reimbursement.index')->with('success', 'Data berhasil ditambahkan.');
            }

            $errors = $response->json('errors') ?? [];
            $errorMessage = $response->json('message') ?? 'Gagal menyimpan data.';
            return back()->withInput()->withErrors($errors)->with('error', $errorMessage);

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {

            $apiUrl = rtrim(env('SPPD_API_URL'), '/') . "/reimbursement/single/{$id}";

            $response = Http::withToken($token)
                ->accept('application/json')
                ->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();

                $reimbursement     = $data['data'] ?? null;
                $sppd  = $data['sppd'] ?? ($data['data']['sppd'] ?? []);
                $user = $data['user'] ?? ($data['data']['user'] ?? []);
                $approvals  = $data['approvals'] ?? ($data['data']['approvals'] ?? []);
                $files  = $data['files'] ?? ($data['data']['files'] ?? []);

                $currentUserId = session('user.id');
                if (!$sppd) {
                    return redirect()->back()->with('error', 'Data tidak ditemukan.');
                }

                return view('pages.reimbursement.single', compact('reimbursement','sppd','user','approvals','files','currentUserId'));
            } else {
                return redirect()->back()->with('error', 'Gagal mengambil data SPPD dari API. Status: '.$response->status());
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage());
        }
    }


    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/reimbursement/delete';
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
                return redirect()->route('reimbursement.index')->with('success', 'data berhasil dihapus.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menghapus data.';
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
