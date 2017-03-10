<?php

namespace App\Http\Controllers\Admin;

use App\Model\Companies;
use App\Model\Roles;
use App\Model\UsersCompanyRole;
use App\User;
use App\Model\UsersCompany;
use Illuminate\Http\Request;
//use App\Http\Requests;
use Flash;
use Auth;

class CompaniesController extends AdminController
{
    /**
     * @var companies
     */
    protected $companies;

    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, Companies $companies)
    {
        parent::__construct($request);
        $this->companies = $companies;
        $this->page_title = 'Spoločnosti';
        $this->page_icon = 'fa fa-building';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UsersCompany $usersCompany)
    {
        $this->page_description = "prehlad";

        $companies = $this->companies->getAllWhereAdmin(Auth::user()->id);
        
        foreach($companies as $c){
            $c->users = $usersCompany->getUsersFromCompany($c->id)->count();
        }
        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->page_description = 'vytvoriť';

        return view('admin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UsersCompany $usersCompany, UsersCompanyRole $usersCompanyRole)
    {
        $this->validate($request,
            [
                'name' => 'required',
            ]);

        $request['cert_id'] = NULL;
        $request['logo'] = '';

//        try {
            $store = $this->companies->create($request->all());
//        }
//        catch (\Illuminate\Database\QueryException $e){
//            if ($e) {
//                return redirect()
//                    ->back()
//                    ->withInput($request->all())
//                    ->withErrors([
//                        'Nepodarilo sa pridať novú spoločnosť.',
//                    ]);
//            }
//        }

        $company = $this->companies->findOrFail($store->id);



        $uc_store = $usersCompany->create([
            'user_id' => Auth::user()->id,
            'company_id' => $company->id,
            'enabled' => 1
        ]);

        $usersCompanyRole->create([
            'user_company_id' => $uc_store->id,
            'role_id' => 1
        ]);

        Flash::success('Společnost bola úspešne vytvorená!');

        return redirect(route('admin.companies.detail', $company->id));
//        return view('admin.companies.cert', compact('company'));
    }

    /**
     *Add of certificate for company.
     *
     * @param  int  $company_id
     * @return \Illuminate\Http\Response
     */
    public function addCert(Request $request, $company_id){

//        Flash::success('Spoločnosť bola úspešne upravená!');
        return redirect(route('admin.companies.detail', $company_id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id, Roles $roles, UsersCompany $usersCompany, User $all_users, UsersCompanyRole $usersCompanyRole)
    {
        $company = $this->companies->findOrFail($id);
        $this->page_description = 'detail - ' . $company->name;

        $company->users = $usersCompany->getUsersFromCompany($company->id)->count();

        $users_in = $usersCompany->getUsersFromCompany($id);
        $roles = $roles->getAll();
        $all_users = $all_users->getAll();

        $usersCompanyRole = $usersCompanyRole->getAll()->get();

//        dd($usersCompanyRole);

        foreach($users_in as $u){
            foreach($roles as $r){
                $users_role[$u->id][$r->id] = false;
            }
            foreach($usersCompanyRole as $ucr){
                if($ucr->user_company_id == $u->id){
                    $users_role[$u->id][$ucr->role_id] = true;
                }
            }
        }

        return view('admin.companies.detail', compact('company', 'users_in', 'roles', 'all_users', 'users_role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, UsersCompany $usersCompany, Roles $roles, User $all_users, UsersCompanyRole $usersCompanyRole)
    {
        $this->page_description = 'upraviť';

        $companies = $this->companies->findOrFail($id);
        $users_in = $usersCompany->getUsersFromCompany($id);
        $roles = $roles->getAll();
        $all_users = $all_users->getAll();

        $usersCompanyRole = $usersCompanyRole->getAll()->get();

//        dd($usersCompanyRole);

        foreach($users_in as $u){
            foreach($roles as $r){
                $users_role[$u->id][$r->id] = false;
            }
            foreach($usersCompanyRole as $ucr){
                if($ucr->user_company_id == $u->id){
                    $users_role[$u->id][$ucr->role_id] = true;
                }
            }
        }

        $company_id = $companies->id;

//        $users_role[][];

        return view('admin.companies.edit', compact('companies', 'users_in', 'roles', 'all_users', 'users_role', 'company_id'));
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
            'name' => 'required',
        ];

        $this->validate($request, $validate);
        $companies = $this->companies->findOrFail($id);

        $companies->update($request->all());


        Flash::success('Spoločnosť bola úspešne upravená!');
        return redirect(route('admin.companies.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $companies = $this->companies->findOrFail($id);
        $companies->delete();

        Flash::success('Spoločnost bola zmazaná!');

        return redirect(route('admin.companies.index'));
    }
}
