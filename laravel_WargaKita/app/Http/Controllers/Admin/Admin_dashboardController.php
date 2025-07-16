<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rukun_tetangga;
use App\Models\Rw;
use Illuminate\Http\Request;

class Admin_dashboardController extends Controller
{
    //
    public function index()
    {
        $title = 'Dashboard';
        $jumlah_rt = Rukun_tetangga::count();
        $jumlah_rw = Rw::count();
        return view('admin.dashboard.dashboard',compact('title','jumlah_rt','jumlah_rw'));
    }
}
