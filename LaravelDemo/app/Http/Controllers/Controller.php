<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // function __construct()
    // {
    //     $this->DangNhap();
    // }
    // function DangNhap()
    // {
    //     if(Auth::check())
    //     {
    //         view()->share('user_login',Auth::user());
    //     }
    // }
}
