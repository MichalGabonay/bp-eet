<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function redirect() {
//        return redirect()->away('http://servis.holab.cz');
        if (Auth::check())
        {
            return redirect(route('admin.dashboard'));
        }
        else
        {
            return view('welcome');
        }
    }
}
