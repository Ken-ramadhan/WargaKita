<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index()
    {
        $jumlah_warga = Warga::count();
        return view('dashboard', compact('jumlah_warga'));
    }
}
