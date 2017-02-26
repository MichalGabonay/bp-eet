<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Admin\AdminController as AdminController;
use App\Model\UsersCompanyRole;
use Illuminate\Http\Request;
use App\Http\Requests;
use Flash;
use Auth;
use App\User;
use App\Model\Companies;
use App\Model\Roles;
use App\Model\UsersCompany;

class UsersController extends AdminController
{
    protected $users;
    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, User $user)
    {
        parent::__construct($request);
        $this->users = $user;
        $this->page_title = 'Užívatelia';
        $this->page_icon = 'fa-user';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UsersCompany $usersCompany)
    {
        $this->page_description = "prehlad";

        $users = $this->users->getAll()->get();
        foreach($users as $u){
            $company_count = $usersCompany->getCompaniesUserIn($u->id)->count();
            $u->companies_count = $company_count;
        }
//        dd($users);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Companies $companies, Roles $roles)
    {
        $this->page_description = 'vytvoriť';

        $companies = $companies->getAllWithUserInfo()->get();
        $roles = $roles->getAll();

        return view('admin.users.create', compact('companies', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'username' => 'required|unique:users',
                'password' => 'required',
            ]);

        $request['password'] = bcrypt($request['password']);

        try {
        $store = $this->users->create($request->all());
        }
        catch (\Illuminate\Database\QueryException $e){
            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors([
                    'Nepodarilo sa pridať nového užívatela.',
                ]);
        }

        Flash::success('Užívatel bol úspešne vytvorený!');

        return redirect(route('admin.users.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id, Companies $companies, Roles $roles, UsersCompany $usersCompany, UsersCompanyRole $usersCompanyRole)
    {
        $users = $this->users->findOrFail($id);

        $this->page_description = 'detail';

        $companies = $companies->getAll()->get();
        $user_in_companies = $usersCompany->getCompaniesUserIn($id);

        $usersCompanyRole = $usersCompanyRole->getAll()->get();

        $roles = $roles->getAll();

//        dd($user_in_companies);

        $users_role = null;

        foreach($companies as $c){
            $in_com = 0;
            foreach($user_in_companies as $uc){
                if($c->id == $uc->company_id){
                    $in_com = 1;

                    foreach($roles as $r){
                        $users_role[$id][$uc->company_id][$r->id]['enabled'] = false;
                        $users_role[$id][$uc->company_id][$r->id]['user_company_id'] = $uc->id;
                    }
                    foreach($usersCompanyRole as $ucr){
                        if($ucr->user_company_id == $uc->id){
                            $users_role[$id][$c->id][$ucr->role_id]['enabled'] = true;
                            $users_role[$id][$uc->company_id][$r->id]['user_company_id'] = $uc->id;
                        }
                    }
                }
            }
            if($in_com == 1){
                $c->user_enabled = true;
            }else{
                $c->user_enabled = false;
            }
        }

        return view('admin.users.detail', compact('users', 'companies', 'roles', 'users_role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->page_description = 'upraviť';

        $users = $this->users->findOrFail($id);

        return view('admin.users.edit', compact('users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = [
//            'name' => 'required',
        ];

//        $this->validate($request, $validate);
        $users = $this->users->findOrFail($id);

        if($request['password'] == ''){
            $users->update($request->except(['password']));
        }else{
            $request['password'] = bcrypt($request['password']);
            $users->update($request->all());
        }



        Flash::success('Užívateľ bol úspešne upravený!');
        return redirect(route('admin.users.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $users = $this->users->findOrFail($id);
        $users->delete();

        Flash::success('Užívatel bol zmazaný!');

        return redirect(route('admin.users.index'));
    }

    public function switchCompanyState($user_id, $company_id, UsersCompany $usersCompany){

        $userInCompany = $usersCompany->findUserCompany($user_id, $company_id)->first();

        if($userInCompany != null){
            if($userInCompany->enabled == 1){
                $userInCompany->enabled = 0;
                $userInCompany->update();
            }else{
                $userInCompany->enabled = 1;
                $userInCompany->update();
            }
        }else{
            $store = $usersCompany->create([
                'user_id' => $user_id,
                'company_id' => $company_id,
                'enabled' => 1
            ]);
        }

        return redirect(route('admin.users.detail', $user_id));

    }

}