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

define('DIR_CERT', __DIR__ . "/../../../resources/assets/cert/");

class AdminController extends Controller
{
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


                $menu->add('Dashboard', ['route' => 'admin.dashboard', 'icon' => 'icon-home4']);
            $menu->add('Dashboard', ['route' => 'admin.dashboard', 'icon' => 'icon-home4']);
            $menu->add('Dashboard', ['route' => 'admin.dashboard', 'icon' => 'icon-home4']);
            $menu->add('Dashboard', ['route' => 'admin.dashboard', 'icon' => 'icon-home4']);

//                $menu->add('Společnosti', ['route' => 'admin.companies.index', 'icon' => 'icon-store']);
//
//
//
                $menu->add('Uživatelé', ['icon' => 'icon-user']);
                $menu->item('uzivatele')->add('Technici', ['route' => ['admin.dashboard']]);
                $menu->item('uzivatele')->add('Zákazníci', ['route' => ['admin.dashboard']]);
                $menu->item('uzivatele')->add('Administrátoři', ['route' => ['admin.dashboard']]);


//            }

        });

    }
}
