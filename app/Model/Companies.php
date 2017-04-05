<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Companies extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'cert_id', 'ico', 'dic', 'logo', 'address', 'phone'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get all companies
     *
     * @return Companies
     */
    public function getAll()
    {
        return $this->select($this->table . '.*')
            ->where($this->table . '.deleted_at', NULL);
    }

    /**
     * Get all companies where is logged user admin
     *
     * @return Companies
     */
    public function getAllWhereAdmin($user_id)
    {
        return $this->select($this->table . '.*', 'certs.valid as cert_valid', 'expiration_date')
            ->where($this->table . '.deleted_at', NULL)
            ->leftJoin('user_company', 'company_id', '=', $this->table . '.id')
            ->where('user_company.user_id', $user_id)
            ->leftJoin('user_company_role', 'user_company_id', '=', 'user_company.id')
            ->where('role_id', 1)
            ->leftJoin('certs', 'certs.id', '=', $this->table . '.cert_id')
            ->get();
    }

    /**
     * Get all companies
     *
     * @return Companies
     */
    public function getAllWithUserInfo()
    {
        return $this->select($this->table . '.*', 'user_company.user_id')
            ->where($this->table . '.deleted_at', NULL)
            ->leftJoin('user_company', 'company_id', '=', $this->table . '.id');
    }


}
