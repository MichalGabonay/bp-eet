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
    /**
     * @var $users
     */
    protected $users;
    /**
     * Create a new users controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, User $user)
    {
        parent::__construct($request);

        $this->middleware(function ($request, $next) {

            if (session('isAdmin') == false){
                return redirect(route('admin.dashboard'));
            }

            return $next($request);
        });

        $this->users = $user;
        $this->page_title = 'Užívatelia';
        $this->page_icon = 'fa-user';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UsersCompany $usersCompany, Companies $companies)
    {
        $this->page_description = "prehlad";

        $companies_ids = [];
        $companies = $companies->getAllWhereAdmin(Auth::user()->id);
        foreach ($companies as $c){
            $companies_ids[] = $c->id;
        }

        $users = $this->users->getAllFromCompanies($companies_ids);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->page_description = 'vytvoriť';

        return view('admin.users.create');
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

        $companies = $companies->getAllWhereAdmin(Auth::user()->id);
        $user_in_companies = $usersCompany->getCompaniesUserIn($id);

        $usersCompanyRole = $usersCompanyRole->getAll()->get();

        $roles = $roles->getAll();

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

    /**
     * disable/enable user in company
     *
     */
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
        return redirect()->back();
    }

    /**
     * add selected user to company
     *
     */
    public function addUserToCompany(Request $request, $company_id, UsersCompany $usersCompany){

        $user_id = $request->user;

        if ($user_id != ""){
            $userInCompany = $usersCompany->findUserCompany($user_id, $company_id)->first();

            if($userInCompany != null){
                $userInCompany->enabled = 1;
                $userInCompany->update();
            }else{
                $store = $usersCompany->create([
                    'user_id' => $user_id,
                    'company_id' => $company_id,
                    'enabled' => 1
                ]);
            }
        }
        return redirect(route('admin.companies.detail', $company_id));
    }
}