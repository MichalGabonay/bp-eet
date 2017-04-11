<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use App\Model\Companies;
use App\Model\UsersCompany;
use App\Model\UsersCompanyRole;
use Illuminate\Http\Request;
use Auth;
use Menu;
use View;
use App\User;

define('DIR_CERT', public_path() . "/uploads/certs/");

class AdminController extends Controller
{
    /**
     * @var $page_title
     */
    protected $page_title = '';

    /**
     * @var $page_description
     */
    protected $page_description = '';

    /**
     * @var $page_icon
     */
    protected $page_icon = 'fa-archive';

    /**
     * @var $company_logo
     */
    protected $company_logo = '';

    /**
     * @var $selectedCompany
     */
    protected $selectedCompany;

    /**
     * @var $userCompanyId
     */
    protected $userCompanyId;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {

            //if still not selected company, choose one
            if (session('selectedCompany') == null){
                $this->changeSelectedCompany();
            }
            if(session('selectedCompany') != null){
                $companies = new Companies();
                $company = $companies->findOrFail(session('selectedCompany'));
                $this->company_logo = $company->logo;
            }

            //create menu
            Menu::make('MyNavBar', function($menu) {
                $menu->add('Dashboard', ['route' => 'admin.dashboard', 'icon' => 'fa fa-home']);

                if(session('isAdmin'))
                    $menu->add('Užívatelia', ['route' => 'admin.users.index', 'icon' => 'fa fa-user']);

                if(session('isAdmin'))
                    $menu->add('Spoločnosti', ['route' => 'admin.companies.index', 'icon' => 'fa fa-building']);

                if(session('isAdmin') || session('isManager') || session('isCashier'))
                    $menu->add('Tržby', ['route' => 'admin.sales.index', 'icon' => 'fa fa-money']);

                if(session('isAdmin') || session('isManager'))
                    $menu->add('Poznámky', ['route' => 'admin.notes.index', 'icon' => 'fa fa-sticky-note']);

                if(session('isAdmin') || session('isManager'))
                    $menu->add('Import', ['route' => 'admin.import.index', 'icon' => 'fa fa-download']);

                if(session('isAdmin') || session('haveExport'))
                    $menu->add('Export', ['route' => 'admin.export.index', 'icon' => 'fa fa-upload']);
            });

            // share page title and description with view
            View::composer('*', function($view){
                $view->page_title = $this->page_title;
                $view->page_description = $this->page_description;
                $view->page_icon = $this->page_icon;
                $view->company_logo = $this->company_logo;
            });

            return $next($request);
        });
    }

    /**
     * Choosing of selected company, for which will be system set up
     */
    public function changeSelectedCompany($company_id = null ){

        $usersCompanyRole = new UsersCompanyRole();
        $usersCompany = new UsersCompany();
        session([
            'selectedCompany' => null,
            'isAdmin' => false,
            'isManager' => false,
            'haveStorno' => false,
            'haveExport' => false,
            'isCashier' => false,
        ]);

        $user = new User();

        if ($company_id == null){
            $user_company = $user->getFirstCompany(Auth::user()->id);
        }else{
            $user_company = $usersCompany->findUserCompany(Auth::user()->id, $company_id)->first();
        }

        //set roles for user in selected company
        if ($user_company != null){
            session(['selectedCompany' => $user_company->company_id]);
            $roles_id = $usersCompanyRole->getAllUserCompanyRoles($user_company->id);

            foreach ($roles_id as $r){
                switch ($r->role_id) {
                    case 1:
                        session(['isAdmin' => true]);
                        break;
                    case 2:
                        session(['isManager' => true]);
                        break;
                    case 3:
                        session(['haveStorno' => true]);
                        break;
                    case 4:
                        session(['haveExport' => true]);
                        break;
                    case 5:
                        session(['isCashier' => true]);
                        break;
                }
            }
        }

        //if user only cashier, go straight to sales page
        if (session('isAdmin') == false && session('isManager') == false && session('isCashier')){
            return redirect(route('admin.sales.index'));
        }else{
            return redirect(route('admin.dashboard'));
        }
    }

}
