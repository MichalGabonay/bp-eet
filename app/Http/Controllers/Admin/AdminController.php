<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use App\Model\UsersCompany;
use App\Model\UsersCompanyRole;
use Illuminate\Http\Request;
use Auth;
use Menu;
use View;
use App\User;

define('DIR_CERT', __DIR__ . "/../../../resources/assets/cert/");

class AdminController extends Controller
{

    protected $page_title = '';

    /**
     * @var $page_description
     */
    protected $page_description = '';

    /**
     * @var $page_icon
     */
    protected $page_icon = 'fa-archive';

    protected $selectedCompany;

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
//            $this->setSelectedCompany();
//

//        dd($this->selectedCompany);
//        dd($user->getFirstCompany(1)->company_id);
//        $this->setSelectedCompany(1);



        Menu::make('MyNavBar', function($menu) {

//            $user = new User();
//            if(!is_null(Auth::user())) {


            $menu->add('Dashboard', ['route' => 'admin.dashboard', 'icon' => 'fa fa-home']);

            if(session('isAdmin') == true)
            $menu->add('Užívatelia', ['route' => 'admin.users.index', 'icon' => 'fa fa-user']);

            $menu->add('Spoločnosti', ['route' => 'admin.companies.index', 'icon' => 'fa fa-building']);

            $menu->add('Tržby', ['route' => 'admin.sales.index', 'icon' => 'fa fa-money']);

            $menu->add('Poznámky', ['route' => 'admin.notes.index', 'icon' => 'fa fa-sticky-note']);
//
//
//
//            $menu->add('Uživatelé', ['icon' => 'icon-user']);
//            $menu->item('uzivatele')->add('Technici', ['route' => ['admin.dashboard']]);
//            $menu->item('uzivatele')->add('Zákazníci', ['route' => ['admin.dashboard']]);
//            $menu->item('uzivatele')->add('Administrátoři', ['route' => ['admin.dashboard']]);


//            }

        });

            return $next($request);
        });

        // share page title and description with view
        View::composer('*', function($view){
            $view->page_title = $this->page_title;
            $view->page_description = $this->page_description;
            $view->page_icon = $this->page_icon;
        });

    }

    public function changeSelectedCompany(UsersCompanyRole $usersCompanyRole, UsersCompany $usersCompany, $company_id = null ){

        session([
            'selectedCompany' => null,
            'isAdmin' => false,
            'isManager' => false,
            'haveStorno' => false,
            'haveExport' => false,
            'isCashier' => false,
        ]);

        $user = new User();
//        dd($company_id);

        if ($company_id == null){
            $user_company = $user->getFirstCompany(Auth::user()->id);
        }else{
            $user_company = $usersCompany->findUserCompany(Auth::user()->id, $company_id)->first();
        }



//        dd($user_company);
        if ($user_company != null){
            session(['selectedCompany' => $user_company->company_id]);
            $roles_id = $usersCompanyRole->getAllUserCompanyRoles($user_company->id);
            foreach ($roles_id as $r){
                switch ($r->id) {
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

        return redirect(route('admin.dashboard'));
    }

}
