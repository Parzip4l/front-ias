<?php

namespace App\Http\Controllers\Sppd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ApprovalController extends Controller
{
    public function index()
    {
        $baseUrl = rtrim(env('SPPD_API_URL'), '/');
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }
        
        $flow = [];
        $company = [];

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
                session()->flash('error', 'Gagal mengambil data posisi.');
            }

            $flowResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/approval/flow/list');

            if ($flowResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($flowResponse->successful()) {
                $flow = $flowResponse->json()['data'] ?? [];
            } else {
                session()->flash('error', 'Gagal mengambil data divisi.');
            }

        } catch (\Exception $e) {
            // Tangani error exception, redirect balik dengan pesan error
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        return view('pages.company.approval.index', compact('flow','company'));
    }

    public function store(Request $request)
    {
        // Validasi input 'name' wajib dan string maksimal 255 karakter
        $validated = $request->validate([
            'name' => 'required',
            'company_id' => 'required',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/approval/flow/store';
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
                    'is_active' => 1,
                ]);

            if ($response->status() == 401) {
                // Token expired atau tidak valid
                Session::forget('jwt_token'); // hapus token dari session
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->route('flow.index')->with('success', 'Data berhasil ditambahkan.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menyimpan Data.';
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
            // Ambil detail flow
            $flowResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/approval/flow/single/' . $id);

            if ($flowResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            $flow = $flowResponse->json('data') ?? [];

            // Position
            $positionResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/posisi/list/');

            if ($positionResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            $position = $positionResponse->json('data') ?? [];

            // Company
            $companyResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/company/list/');

            if ($companyResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            $companies = $companyResponse->json('data') ?? [];

            // Divisi
            $divisionResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/divisi/list/');

            if ($divisionResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            $divisions = $divisionResponse->json('data') ?? [];

            // user
            $userResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/user/user-list/');

            if ($userResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            $users = $userResponse->json('data') ?? [];

            // Ambil daftar step
            $stepsResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/approval/steps/all-step/' . $id);

            $steps = $stepsResponse->json('data') ?? [];

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        return view('pages.company.approval.editflow', compact('flow', 'steps', 'position', 'divisions', 'users','companies'));
    }

    public function show($id)
    {
        $baseUrl = rtrim(env('SPPD_API_URL'), '/');
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            // Ambil detail flow
            $flowResponse = Http::withToken($token)
                ->accept('application/json')
                ->get("{$baseUrl}/approval/flow/single/{$id}");

            if ($flowResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            $flow = $flowResponse->json('data') ?? [];

            // Ambil daftar step hierarchy jika tipe 'hierarchy'
            $steps = [];
            if(isset($flow['approval_type']) && $flow['approval_type'] === 'hirarki') {
                $stepsResponse = Http::withToken($token)
                    ->accept('application/json')
                    ->get("{$baseUrl}/approval/steps/all-step/{$id}");

                $steps = $stepsResponse->json('data') ?? [];
            }

            // Ambil amount flow & step jika tipe 'nominal'
            $amountFlows = [];

            if(isset($flow['approval_type']) && $flow['approval_type'] === 'nominal') {
                $amountFlowResponse = Http::withToken($token)
                    ->accept('application/json')
                    ->get("{$baseUrl}/approval/amount-flow/by-flow/{$flow['id']}");

                // Ambil array langsung dari response
                $amountFlowsData = $amountFlowResponse->successful() ? $amountFlowResponse->json() : [];

                // Loop untuk ambil steps tiap amount flow
                foreach($amountFlowsData as $af) {
                    $stepsResponse = Http::withToken($token)
                        ->accept('application/json')
                        ->get("{$baseUrl}/approval/amount-step/by-flow/{$af['id']}");

                    // langsung ambil response tanpa ['data']
                    $af['steps'] = $stepsResponse->successful() ? $stepsResponse->json() ?? [] : [];
                    $amountFlows[] = $af;
                }
            }
            // Ambil posisi, divisi, user
            $position = Http::withToken($token)->accept('application/json')->get("{$baseUrl}/posisi/list/")->json('data') ?? [];
            $divisions = Http::withToken($token)->accept('application/json')->get("{$baseUrl}/divisi/list/")->json('data') ?? [];
            $users = Http::withToken($token)->accept('application/json')->get("{$baseUrl}/user/user-list/")->json('data') ?? [];

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return view('pages.company.approval.single', compact(
            'flow',
            'steps',
            'amountFlows',
            'position',
            'divisions',
            'users'
        ));
    }


    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/approval/flow/delete';
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
                return redirect()->route('flow.index')->with('success', 'data berhasil dihapus.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menghapus data.';
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function addStep(Request $request)
    {
        $validated = $request->validate([
            'step_order' => 'required',
            'user_id' => 'required|integer',
            'division_id' => 'required|integer',
            'position_id' => 'required|integer',
            'user_id' => 'required|integer',
            'approval_flow_id' => 'required',
            'is_final' => 'required',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . "/approval/steps/store";
        $token = Session::get('jwt_token');

        $response = Http::withToken($token)
            ->accept('application/json')
            ->post($apiUrl, $validated);

        if ($response->successful()) {
            return back()->with('success', 'Step berhasil ditambahkan');
        }
        return back()->with('error', $response->json('message') ?? 'Gagal menambahkan step');
    }

    public function editStep($flowId, $stepId)
    {
        $baseUrl = rtrim(env('SPPD_API_URL'), '/');
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            // Ambil detail flow
            $flowResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/approval/flow/single/' . $flowId);

            if ($flowResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }
            $flow = $flowResponse->json('data') ?? [];

            // Ambil detail step tunggal
            $stepResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/approval/steps/single/' . $stepId);

            if ($stepResponse->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }
            $step = $stepResponse->json('data') ?? [];

            // Ambil daftar division
            $divisionResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/divisi/list');

            $divisions = $divisionResponse->json('data') ?? [];

            // Ambil daftar posisi
            $positionResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/posisi/list');

            $positions = $positionResponse->json('data') ?? [];

            // Ambil daftar user
            $userResponse = Http::withToken($token)
                ->accept('application/json')
                ->get($baseUrl . '/user/user-list');

            $users = $userResponse->json('data') ?? [];

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return view('pages.company.approval.edit', compact('flow', 'step', 'divisions', 'positions', 'users'));
    }

    public function updateStep(Request $request, $flowId, $stepId)
    {
        $validated = $request->validate([
            'step_order'       => 'required|integer',
            'division_id'      => 'required|integer',
            'position_id'      => 'required|integer',
            'user_id'          => 'required|integer',
            'is_final'         => 'required|boolean',
            'approval_flow_id' => 'required|integer',
            'id'               => 'required|integer',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . "/approval/steps/update/";
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            $response = Http::withToken($token)
                ->accept('application/json')
                ->post($apiUrl, [
                    'id'                => $validated['id'],
                    'step_order'              => $validated['step_order'],
                    'division_id'        => $validated['division_id'],
                    'user_id'   => $validated['user_id'],
                    'position_id'       => $validated['position_id'],
                    'is_final'       => $validated['is_final'],
                    'approval_flow_id' => $validated['approval_flow_id'],
                ]);

            if ($response->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return back()->with('success', 'Step berhasil diperbarui');
            }

            $errorMessage = $response->json('message') ?? 'Gagal memperbarui step';
            return back()->with('error', $errorMessage);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function deleteStep(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/approval/steps/delete';
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
                return redirect()->back()->with('success', 'data berhasil dihapus.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menghapus data.';
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'company_id' => 'required|integer',
            'is_active'  => 'nullable|boolean',
            'approval_type' => 'nullable|string', 
            'requester_position_id' => 'nullable|integer',
            'id'  => 'required|integer',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/approval/flow/update/';
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
                    'id'         => $validated['id'],
                    'name'       => $validated['name'],
                    'company_id' => $validated['company_id'],
                    'is_active'  => $request->input('is_active', 1),
                    'approval_type' => $validated['approval_type'],
                    'requester_position_id' => $validated['requester_position_id'],
                ]);

            if ($response->status() == 401) {
                // Token expired / tidak valid
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Data berhasil diperbarui.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal memperbarui data.';
                return back()->withInput()->with('error', $errorMessage);
            }

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function isFinal(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'is_final'  => 'required|boolean',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/approval/step/final-step/' . $id;
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            $response = Http::withToken($token)
                ->accept('application/json')
                ->put($apiUrl, [
                    'is_final' => $request->input('is_final'),
                ]);

            if ($response->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal memperbarui data.';
                return response()->json(['success' => false, 'message' => $errorMessage], 422);
            }

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }


    public function storeAmount(Request $request)
    {
        $validated = $request->validate([
            'approval_flow_id' => 'required',
            'min_amount' => 'required|integer',
            'max_amount' => 'required|integer',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . "/approval/amount-flow/store";
        $token = Session::get('jwt_token');

        $response = Http::withToken($token)
            ->accept('application/json')
            ->post($apiUrl, $validated);

        if ($response->successful()) {
            return back()->with('success', 'Data berhasil ditambahkan');
        }
        return back()->with('error', $response->json('message') ?? 'Gagal menambahkan step');
    }

    public function deleteAmountStep($id)
    {
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . "/approval/amount-step/delete/{$id}";
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            $response = Http::withToken($token)
                ->accept('application/json')
                ->post($apiUrl); // id sudah di URL, tidak perlu body

            if ($response->status() == 401) {
                Session::forget('jwt_token');
                return redirect()->route('login')->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($response->successful()) {
                return back()->with('success', 'Step berhasil dihapus.');
            } else {
                $errorMessage = $response->json('message') ?? 'Gagal menghapus step.';
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function storeStepAmount(Request $request)
    {
        $validated = $request->validate([
            'approval_amount_flow_id' => 'required',
            'step_order'     => 'required|integer|min:1',
            'division_id'    => 'required',
            'position_id'    => 'required',
            'user_id'        => 'required',
            'is_final'       => 'required',
        ]);

        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . "/approval/amount-step/store";
        $token = Session::get('jwt_token');

        $response = Http::withToken($token)
            ->accept('application/json')
            ->post($apiUrl, $validated);

        if ($response->successful()) {
            return back()->with('success', 'Step Amount Flow berhasil ditambahkan');
        }

        return back()->with('error', $response->json('message') ?? 'Gagal menambahkan Step Amount Flow');
    }

}
