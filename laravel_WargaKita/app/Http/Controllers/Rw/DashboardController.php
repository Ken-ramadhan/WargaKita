<?php

namespace App\Http\Controllers\Rw;
use App\Http\Controllers\Controller;

use App\Models\Warga;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index()
    {
        $jumlah_warga = Warga::count();
        return view('rw.dashboard.dashboard', compact('jumlah_warga'));
    }
}
