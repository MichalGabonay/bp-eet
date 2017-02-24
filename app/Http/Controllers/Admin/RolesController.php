<?php

namespace App\Http\Controllers\Admin;

use App\Model\UsersCompany;
use Illuminate\Http\Request;
//use App\Http\Requests;
use Flash;
use Auth;
use App\Model\Roles;
use App\Model\UsersCompanyRole;

class RolesController extends AdminController
{
    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->page_title = 'Role';
        $this->page_icon = 'fa-address-card';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
    }

    public function switchRoleUC($u_c_id, $role_id, $type, UsersCompanyRole $usersCompanyRole, UsersCompany $usersCompany){

        $usersCompany = $usersCompany->findOrFail($u_c_id);
        $u_c_r = $usersCompanyRole->getUserCompanyRoleId($u_c_id, $role_id);

        if($u_c_r->first() == null){
            $store = $usersCompanyRole->create([
                'user_company_id' => $u_c_id,
                'role_id' => $role_id
            ]);

            Flash::success('Užívatelovi bola pridaná rola!');
        }else{
//            dd($u_c_r);
            $usersCompanyRole = $usersCompanyRole->findOrFail($u_c_r->first()->id);
            $usersCompanyRole->delete();

            Flash::success('Užívatelovi bola odobraná rola!');
        }

        if($type == 'companies')
            return redirect(route('admin.companies.edit', $usersCompany->company_id)); //TODO: edit - #users_tab
        else
            return redirect(route('admin.users.edit', $usersCompany->user_id)); //TODO: edit - #companies_tab
    }
}
