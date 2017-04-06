<?php

namespace App\Http\Controllers\Admin;

use App\Model\Certs;
use App\Model\Companies;
use App\Model\Sales;
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
            return redirect(route('login'));
        }
    }

    public function index(Companies $companies, UsersCompany $usersCompany, Certs $certs, Sales $sales, User $user) {

//        dd(session()->all());
//        dd(session('selectedCompany'));
        $cert = null;
        if (session('selectedCompany') != null){
            $selected_company = $companies->findOrFail(session('selectedCompany'));
            $company = $companies->findOrFail($selected_company->id);

            if ($company->cert_id != null){
                $cert = $certs->findOrFail($company->cert_id);
            }

        }
        else{
            $selected_company = null;
        }

        $all_sales = $sales->getAllForChart(session('selectedCompany'))->get();

        $usersCompany = $usersCompany->getCompaniesUserIn(Auth::user()->id);

        $sales_by_day_week = [];
        for ($i=0;$i<=6;$i++){
            $sales_by_day_week[$i] = 0;
        }

        foreach ($all_sales as $as){
            $day_of_week = date("w", strtotime($as->date));
            switch ($day_of_week){
                case 0:
                    $sales_by_day_week[0] += $as->total_price;
                    break;
                case 1:
                    $sales_by_day_week[1] += $as->total_price;
                    break;
                case 2:
                    $sales_by_day_week[2] += $as->total_price;
                    break;
                case 3:
                    $sales_by_day_week[3] += $as->total_price;
                    break;
                case 4:
                    $sales_by_day_week[4] += $as->total_price;
                    break;
                case 5:
                    $sales_by_day_week[5] += $as->total_price;
                    break;
                case 6:
                    $sales_by_day_week[6] += $as->total_price;
                    break;
            }
        }


        $last_sales = $sales->getAll(session('selectedCompany'))->get()->take(5);

        $users_sales = $user->getCashiersBySales(session('selectedCompany'));

//        dd(count($last_sales));

        return view('admin.dashboard', compact('selected_company', 'usersCompany', 'cert', 'all_sales', 'sales_by_day_week', 'last_sales', 'users_sales'));

    }
}