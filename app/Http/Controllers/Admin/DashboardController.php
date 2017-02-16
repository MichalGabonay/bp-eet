<?php

namespace App\Http\Controllers\Admin;


use App\Models\Companies\Companies;
use Illuminate\Http\Request;
use App\Http\Requests;
use Flash;
use Auth;
use App\User;

class DashboardController extends AdminController
{

    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->page_title = 'Dashboard';
        $this->page_icon = 'fa-home';
    }

    /**
     * Default page / redirects to dashboard or login page
     *
     * @return Redirect
     */
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

    public function index() {

        return view('admin.dashboard');

    }
}