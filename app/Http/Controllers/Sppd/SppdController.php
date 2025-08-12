<?php

namespace App\Http\Controllers\Sppd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SppdController extends Controller
{
    public function create()
    {
        return view('pages.sppd.create');
    }

    public function preview(Request $request)
    {
        // Ambil semua query parameter dari URL
        $data = $request->all();
        // Pastikan addons berbentuk array
        if (!empty($data['addons']) && !is_array($data['addons'])) {
            $data['addons'] = [$data['addons']];
        }

        // Kirim ke Blade
        return view('pages.sppd.preview', $data);
    }
}
