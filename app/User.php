<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getFirstCompany($userId){
        return $this->select('user_company.id','user_company.company_id')
            ->where($this->table . '.id', $userId)
            ->leftJoin('user_company', 'user_company.user_id', '=', $this->table . '.id')
            ->where('user_company.enabled', 1)
            ->first();
    }

    /**
     * Get all users
     *
     * @return User
     */
    public function getAll()
    {
        return $this->select($this->table . '.*')->orderBy($this->table . '.name', 'ASC');
    }

    /**
     * Get all users from companies
     *
     * @return User
     */
    public function getAllFromCompanies($companies_ids)
    {
        return $this->select($this->table . '.*')
            ->leftJoin('user_company', 'user_company.user_id', '=', $this->table . '.id')
            ->where('enabled', 1)
            ->wherein('company_id', $companies_ids)
            ->distinct()
            ->get();
    }

    public function getCashiersBySales($company_id){
        return $this->select('users.name', DB::raw("SUM(total_price) as sales"))
            ->leftJoin('user_company', 'user_company.user_id', '=', $this->table . '.id')
            ->where('enabled', 1)
            ->where('user_company.company_id', $company_id)
//            ->leftJoin('user_company_role', 'user_company_role.user_company_id', '=', 'user_company.id')
//            ->where('role_id', '=' , 5)
//            ->where('role_id', '<>' , 1)
//            ->where('role_id', '<>' , 2)
            ->leftJoin('sales', 'sales.user_id', '=', $this->table . '.id')
            ->where('sales.company_id', '=', $company_id)
            ->groupBy('users.name')
            ->get();
    }


}
