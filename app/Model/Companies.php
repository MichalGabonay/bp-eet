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
    protected $fillable = ['name', 'cert_id', 'ico', 'dic', 'logo'];

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
        return $this->select($this->table . '.*');
    }

    /**
     * Get all companies
     *
     * @return Companies
     */
    public function getAllWithUserInfo()
    {
        return $this->select($this->table . '.*', 'user_company.user_id')
            ->leftJoin('user_company', 'company_id', '=', $this->table . '.id');
    }


}
