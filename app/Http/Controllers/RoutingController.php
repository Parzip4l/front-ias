<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RoutingController extends Controller
{
    public function index()
    {
        // Pastikan user sudah login (JWT session)
        if (Auth::check()) {
            // langsung tampilkan dashboard
            return view('index');
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
}
