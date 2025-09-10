<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class FinanceController extends Controller
{
    public function index()
    {
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . '/finance/index';
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token belum tersedia, silakan login dulu.');
        }

        try {
            $response = Http::withToken($token)
                ->accept('application/json')
                ->get($apiUrl);

            if ($response->successful()) {
                $json = $response->json();

                $summary = $json['summary'] ?? [];
                $trends = $json['trends'] ?? [];
                $byDepartment = $json['by_department'] ?? [];
                $payments = $json['list'] ?? [];
            } else {
                $summary = [];
                $trends = [];
                $byDepartment = [];
                $payments = [];
                session()->flash('error', 'Gagal mengambil data Finance dari API.');
            }

        } catch (\Exception $e) {
            $summary = [];
            $trends = [];
            $byDepartment = [];
            $payments = [];
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return view('pages.finance.report.index', [
            'summary'      => $summary,
            'trends'       => $trends,
            'byDepartment' => $byDepartment,
            'payments'     => $payments,
            'pageTitle'    => 'Report Finance'
        ]);
    }

}
