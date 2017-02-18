<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use SlevomatEET\Cryptography\CryptographyService;
use SlevomatEET\Driver\GuzzleSoapClientDriver;
use SlevomatEET\Configuration;
use SlevomatEET\EvidenceEnvironment;
use SlevomatEET\Client;
use SlevomatEET\Receipt;
use Auth;
use Menu;
use View;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');

        Menu::make('MyNavBar', function($menu) {

//            $user = new User();
//            if(!is_null(Auth::user())) {


            $menu->add('Dashboard', ['route' => 'admin.dashboard', 'icon' => 'fa fa-home']);

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

        // share page title and description with view
        View::composer('*', function($view){
            $view->page_title = $this->page_title;
            $view->page_description = $this->page_description;
            $view->page_icon = $this->page_icon;

            $view->country = config('loreal.country');
        });

    }
}
