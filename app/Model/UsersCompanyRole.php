<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UsersCompanyRole extends Model
{
    protected $table = 'user_company_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_company_id', 'role_id'];

    /**
     * Get all companies
     *
     * @return Companies
     */
    public function getAll()
    {
        return $this->select($this->table . '.*');
    }

    /**
     * Get role id
     *
     * @return Companies
     */
    public function getUserCompanyRoleId($user_company_id, $role_id)
    {
        return $this->select($this->table . '.*')
            ->where($this->table .'.user_company_id', $user_company_id)
            ->where($this->table .'.role_id', $role_id)
            ->get();
    }

    /**
     * Get all user company roles
     *
     * @return Companies
     */
    public function getAllUserCompanyRoles($user_company_id)
    {
        return $this->select($this->table . '.*')
            ->where($this->table .'.user_company_id', $user_company_id)
            ->get();
    }


}
