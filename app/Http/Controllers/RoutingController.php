<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class RoutingController extends Controller
{
    public function index()
    {
        // Pastikan user sudah login (JWT session)
        if (Auth::check()) {
            $dashboard = $this->fetchDashboardData();

            return view('index', $dashboard);
        }

        // kalau belum login
        return redirect()->route('login');
    }

    public function root($first)
    {
        return $this->loadViewIfExists($first);
    }

    public function secondLevel($first, $second)
    {
        return $this->loadViewIfExists("$first.$second");
    }

    public function thirdLevel($first, $second, $third)
    {
        return $this->loadViewIfExists("$first.$second.$third");
    }

    /**
     * Helper: cek view ada, kalau nggak 404
     */
    private function loadViewIfExists($viewName)
    {
        if (view()->exists($viewName)) {
            return view($viewName);
        }
        abort(404);
    }

    private function fetchDashboardData(): array
    {
        $token = Session::get('jwt_token');
        $apiUrl = rtrim((string) env('SPPD_API_URL'), '/') . '/finance/dashboard';

        $fallback = [
            'dashboardSummary' => [],
            'dashboardCharts' => [
                'monthly_sppd' => ['labels' => [], 'values' => []],
                'monthly_spending' => ['labels' => [], 'values' => []],
                'status_breakdown' => ['Approved' => 0, 'Pending' => 0, 'Rejected' => 0],
            ],
            'latestSppds' => [],
            'topProvinces' => [],
        ];

        if (!$token) {
            return $fallback;
        }

        try {
            $response = Http::withToken($token)
                ->accept('application/json')
                ->get($apiUrl);

            if (!$response->successful()) {
                session()->flash('error', 'Gagal mengambil data dashboard dari API.');
                return $fallback;
            }

            $json = $response->json();

            return [
                'dashboardSummary' => $json['summary'] ?? $fallback['dashboardSummary'],
                'dashboardCharts' => $json['charts'] ?? $fallback['dashboardCharts'],
                'latestSppds' => $json['latest_sppds'] ?? $fallback['latestSppds'],
                'topProvinces' => $json['top_provinces'] ?? $fallback['topProvinces'],
            ];
        } catch (\Throwable $th) {
            session()->flash('error', 'Terjadi kesalahan saat memuat dashboard: ' . $th->getMessage());
            return $fallback;
        }
    }
}
