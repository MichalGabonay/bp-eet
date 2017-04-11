<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UsersCompany extends Model
{
    protected $table = 'user_company';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'company_id', 'enabled'];

    /**
     * @return UsersCompany
     */
    public function findUserCompany($user_id, $company_id)
    {
        return $this->select($this->table . '.*')
            ->where($this->table .'.user_id', $user_id)
            ->where($this->table .'.company_id', $company_id)
            ->get();
    }

    /**
     * Get all users from company
     *
     * @var integer
     *
     * @return UsersCompany
     */
    public function getUsersFromCompany($company_id)
    {
        return $this->select($this->table . '.*', 'users.name', 'users.username', 'users.email')
            ->where($this->table .'.company_id', $company_id)
            ->where($this->table .'.enabled', 1)
            ->leftJoin('users', 'users.id', '=', $this->table . '.user_id')
            ->get();
    }

    /**
     * Get all companies where is user enabled
     *
     * @var integer
     *
     * @return UsersCompany
     */
    public function getCompaniesUserIn($user_id)
    {
        return $this->select($this->table . '.*', 'companies.name')
            ->where($this->table .'.user_id', $user_id)
            ->where($this->table .'.enabled', 1)
            ->leftJoin('companies', 'companies.id', '=', $this->table . '.company_id')
            ->where('companies.deleted_at', NULL)
            ->get();
    }

    /**
     * Get all admins from company
     *
     * @var integer
     *
     * @return UsersCompany
     */
    public function getAllAdminsFromCopmany($company_id){
        return $this->select($this->table . '.*')
            ->where($this->table .'.company_id', $company_id)
            ->where($this->table .'.enabled', 1)
            ->leftJoin('user_company_role', 'user_company_role.user_company_id', '=', $this->table . '.id')
            ->where('user_company_role.role_id', 1)
            ->get();
    }
}
