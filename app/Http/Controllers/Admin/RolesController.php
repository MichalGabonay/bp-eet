<?php

namespace App\Http\Controllers\Admin;

use App\Model\UsersCompany;
use Illuminate\Http\Request;
use Flash;
use Auth;
use App\Model\UsersCompanyRole;

class RolesController extends AdminController
{
    /**
     * Create a new roles controller instance.
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
     * Swich role of user in specific company
     *
     */
    public function switchRoleUC($u_c_id, $role_id, $type, UsersCompanyRole $usersCompanyRole, UsersCompany $usersCompanies){

        $usersCompany = $usersCompanies->findOrFail($u_c_id);
        $u_c_r = $usersCompanyRole->getUserCompanyRoleId($u_c_id, $role_id);

        //zmena stavu administratora, osetrenie, ze musi ostat vzdy aspon jeden admin
        if ($role_id == 1 && $u_c_r->first() != null){
            $admins = $usersCompanies->getAllAdminsFromCopmany($usersCompany->company_id);
            if (count($admins) == 1){
                flash('Spoločnosť musí mať vždy aspoň jednoho administrátora!', 'danger');
                if($type == 'companies')
                    return redirect(route('admin.companies.detail', $usersCompany->company_id));
                else
                    return redirect(route('admin.users.detail', $usersCompany->user_id));
            }
        }

        if($u_c_r->first() == null){
            $store = $usersCompanyRole->create([
                'user_company_id' => $u_c_id,
                'role_id' => $role_id
            ]);

            Flash::success('Užívatelovi bola pridaná rola!');
        }else{
            $usersCompanyRole = $usersCompanyRole->findOrFail($u_c_r->first()->id);
            $usersCompanyRole->delete();

            Flash::success('Užívatelovi bola odobraná rola!');
        }

        if($type == 'companies')
            return redirect(route('admin.companies.detail', $usersCompany->company_id));
        else
            return redirect(route('admin.users.detail', $usersCompany->user_id));
    }

    //
//    /**
//     * Display a listing of the resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function index()
//    {
//        return view('admin.roles.index');
//    }
//
//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function create()
//    {
//        //
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
//     */
//    public function store(Request $request)
//    {
//        //
//    }
//
//    /**
//     * Display the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function detail($id)
//    {
//        //
//    }
//
//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function edit($id)
//    {
//        //
//    }
//
//    /**
//     * Update the specified resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function update(Request $request, $id)
//    {
//        //
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function delete($id)
//    {
//        //
//    }

}
