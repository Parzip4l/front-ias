<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MitraSaldoController extends Controller
{
    public function index()
    {
        return view('pages.finance.index');
    }
}
