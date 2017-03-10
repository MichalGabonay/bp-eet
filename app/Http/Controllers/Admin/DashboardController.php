<?php

namespace App\Http\Controllers\Admin;

use App\Model\Companies;
use App\Model\UsersCompany;
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

        $this->middleware(function ($request, $next) {

            return $next($request);
        });

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

    public function index(Companies $companies, UsersCompany $usersCompany) {

//        dd(session()->all());
//        dd(session('selectedCompany'));
        if (session('selectedCompany') != null)
            $selected_company = $companies->findOrFail(session('selectedCompany'));
        else
            $selected_company = null;

        $usersCompany = $usersCompany->getCompaniesUserIn(Auth::user()->id);

//        dd(count($usersCompany));

        return view('admin.dashboard', compact('selected_company', 'usersCompany'));

    }
}