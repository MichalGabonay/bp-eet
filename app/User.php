<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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


}
