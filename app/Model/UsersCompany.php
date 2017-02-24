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

    public function getUsersFromCompany($company_id)
    {
        return $this->select($this->table . '.*', 'users.name', 'users.username', 'users.email')
            ->where($this->table .'.company_id', $company_id)
            ->where($this->table .'.enabled', 1)
            ->leftJoin('users', 'users.id', '=', $this->table . '.user_id')
            ->get();
    }

    public function getCompaniesUserIn($user_id)
    {
        return $this->select($this->table . '.*')
            ->where($this->table .'.user_id', $user_id)
            ->where($this->table .'.enabled', 1)
            ->get();
    }
}
