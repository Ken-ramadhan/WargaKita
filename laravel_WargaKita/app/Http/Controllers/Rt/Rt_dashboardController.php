<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;    

use Illuminate\Http\Request;

class Rt_dashboardController extends Controller
{
    //
    public function index(){
        return view('rt.dashboard.dashboard');
    }
}
