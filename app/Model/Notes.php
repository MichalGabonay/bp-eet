<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['note', 'type', 'sale_id', 'company_id', 'from', 'to'];


    /**
     * Get all notes
     *
     * @return Companies
     */
    public function getAll()
    {
        return $this->select($this->table . '.*');
    }

    /**
     * Get all notes
     *
     * @return Companies
     */
    public function getAllFromCompany($company_id)
    {
        return $this->select($this->table . '.*')
            ->where('company_id', $company_id);
    }

    /**
     * Get all notes
     *
     * @return Companies
     */
    public function getAllPeriod($company_id)
    {
        return $this->select($this->table . '.*')
            ->where('company_id', $company_id)
            ->where('type', 1)
//            ->leftJoin('companies', 'companies.id', $this->table . '.company_id')
            ;
    }

    /**
     * Get all notes
     *
     * @return Companies
     */
    public function getAllSale($company_id)
    {
        return $this->select($this->table . '.*', 'sales.id as sale_id', 'receiptNumber')
            ->where($this->table . '.company_id', $company_id)
            ->where('type', 0)
//            ->leftJoin('companies', 'companies.id', $this->table . '.company_id')
            ->leftJoin('sales', 'sales.id', $this->table . '.sale_id')
            ;
    }

    /**
     * Get all notes
     *
     * @return Companies
     */
    public function getAllBySaleId($sale_id)
    {
        return $this->select($this->table . '.*')
            ->where('type', 'sale')
            ->where('sale_id', $sale_id);
    }
}
