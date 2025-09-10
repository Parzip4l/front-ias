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
        return view('pages.sppd.index', [
            'sppds' => $sppds,
            'pageTitle' => null
        ]);
    }

    public function needApproval()
    {
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/sppd/need-approval';
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            $response = Http::withToken($token)
                ->accept('application/json')
                ->get($apiUrl);

            if ($response->successful()) {
                $sppds = $response->json()['data'] ?? [];
            } else {
                $sppds = [];
                session()->flash('error', 'Gagal mengambil data SPPD Need Approval dari API.');
            }
        } catch (\Exception $e) {
            $sppds = [];
            session()->flash('error', 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage());
        }

        // pakai view yang sama
        return view('pages.sppd.index', [
            'sppds' => $sppds,
            'pageTitle' => 'SPPD Need Approval'
        ]);
    }

    public function needPayment()
    {
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/sppd/need-payment';
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            $response = Http::withToken($token)
                ->accept('application/json')
                ->get($apiUrl);

            if ($response->successful()) {
                $sppds = $response->json()['data'] ?? [];
            } else {
                $sppds = [];
                session()->flash('error', 'Gagal mengambil data SPPD.');
            }
        } catch (\Exception $e) {
            $sppds = [];
            session()->flash('error', 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage());
        }

        // pakai view yang sama
        return view('pages.sppd.payment.index', [
            'sppds' => $sppds,
            'pageTitle' => 'Data Sppd Perlu Pembayaran.'
        ]);
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
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            // decode ID kalau perlu
            $realId = function_exists('decode_id') ? decode_id($id) : $id;

            $apiUrl = rtrim(env('SPPD_API_URL'), '/') . "/sppd/details/{$realId}";

            $response = Http::withToken($token)
                ->accept('application/json')
                ->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();

                $sppd     = $data['data'] ?? null;
                $history  = $data['history'] ?? ($data['data']['history'] ?? []);
                $approval = $data['approval'] ?? ($data['data']['approval'] ?? []);
                $expense  = $data['expense'] ?? ($data['data']['expense'] ?? []);
                $payment  = $data['payment'] ?? ($data['data']['payment'] ?? []);
                $tujuan  = $data['tujuan'] ?? ($data['data']['tujuan'] ?? []);
                $file  = $data['file'] ?? ($data['data']['file'] ?? []);

                $currentUserId = session('user.id');

                if (!$sppd) {
                    return redirect()->back()->with('error', 'Data SPPD tidak ditemukan.');
                }

                return view('pages.sppd.preview', compact(
                    'sppd','history','approval','currentUserId','expense','payment','tujuan','file'
                ));
            } else {
                return redirect()->back()->with('error', 'Gagal mengambil data SPPD dari API. Status: '.$response->status());
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage());
        }
    }


   public function store(Request $request)
    {
        // Validasi input utama
        $validated = $request->validate([
            'userid'               => 'required',
            'keperluan'            => 'required|string',
            'tanggal_berangkat'    => 'required|date|after_or_equal:today',
            'tanggal_pulang'       => 'required|date|after_or_equal:tanggal_berangkat',
            'surat_tugas'          => 'nullable|file|mimes:pdf|max:1024',
            'province_id'          => 'required|integer',
            'regency_id'           => 'required|integer',
            'district_id'          => 'required|integer',
            'village_id'           => 'required|integer',
            'full_address'         => 'nullable|string',
            'expenses'             => 'nullable|array',
            'expenses.*.kategori'  => 'required_with:expenses|string|max:100',
            'expenses.*.deskripsi' => 'nullable|string|max:255',
            'expenses.*.jumlah'    => 'nullable|numeric|min:0',
            'expenses.*.bukti_file'=> 'nullable|string',
        ]);

        // Masukkan transportasi ke dalam expenses
        $expenses = $validated['expenses'] ?? [];

        if ($request->filled('transportasi_pergi') && $request->filled('biaya_pergi')) {
            $expenses[] = [
                'kategori'  => 'Transportasi Pergi',
                'deskripsi' => $request->input('transportasi_pergi'),
                'jumlah'    => (float) $request->input('biaya_pergi'),
            ];
        }

        if ($request->filled('transportasi_pulang') && $request->filled('biaya_pulang')) {
            $expenses[] = [
                'kategori'  => 'Transportasi Pulang',
                'deskripsi' => $request->input('transportasi_pulang'),
                'jumlah'    => (float) $request->input('biaya_pulang'),
            ];
        }

        $validated['expenses'] = $expenses;

        // 🔹 Hitung estimasi biaya (total semua expenses)
        $validated['biaya_estimasi'] = collect($expenses)->sum('jumlah');

        // Kirim ke API
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/sppd/store';
        $token  = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
        $http = Http::withToken($token)->asMultipart();

        // Kalau ada file
        if ($request->hasFile('surat_tugas')) {
            $file = $request->file('surat_tugas');
            $http = $http->attach(
                'surat_tugas',
                fopen($file->getRealPath(), 'r'),
                $file->getClientOriginalName()
            );
        }

        // Attach semua field (kecuali file)
        foreach ($validated as $key => $value) {
            if (is_array($value)) {
                // Untuk array (misal expenses)
                foreach ($value as $i => $item) {
                    foreach ($item as $subKey => $subVal) {
                        $http = $http->attach("{$key}[{$i}][{$subKey}]", (string) $subVal);
                    }
                }
            } else {
                $http = $http->attach($key, (string) $value);
            }
        }

        $response = $http->post($apiUrl);

        if ($response->status() == 401) {
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



    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/sppd/delete';
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            // kirim hash langsung ke API
            $response = Http::withToken($token)
                ->accept('application/json')
                ->post($apiUrl, ['id' => $request->id]);

            if ($response->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('sppd.index')->with('success', 'Data berhasil dihapus.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menghapus data.';
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    public function schedulesaya()
    {
        return view('apps.calendar');
    }
}
