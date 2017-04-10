<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyPhones extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company_phones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'phone'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get all phones
     *
     * @return CompanyPhones
     */
    public function getAll()
    {
        return $this->select($this->table . '.*');
    }

    /**
     * Get all phones from company
     *
     * @return CompanyPhones
     */
    public function getAllFromCompany($company_id)
    {
        return $this->select($this->table . '.*')
            ->where($this->table . '.company_id', $company_id)
            ->get();
    }

    /**
     * Get company by phone number
     *
     * @return CompanyPhones
     */
    public function getCompanyByNumber($phone)
    {
        return $this->select($this->table . '.*')
            ->where($this->table . '.phone', $phone)
            ->get();
    }

}
