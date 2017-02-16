<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Admin\AdminController as AdminController;
use Illuminate\Http\Request;
use App\Http\Requests;
use Flash;
use Auth;
use App\User;

class UsersController extends AdminController
{

    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->page_title = 'Users';
        $this->page_icon = 'fa-user';
    }

    public function index() {

        return view('admin.users.index');

    }
}