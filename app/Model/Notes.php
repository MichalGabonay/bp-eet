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
    protected $fillable = ['note', 'type', 'sale_id', 'company_id', 'user_id', 'from', 'to'];


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
            ->where($this->table . '.company_id', $company_id);
    }

    /**
     * Get all notes
     *
     * @return Companies
     */
    public function getAllPeriod($company_id)
    {
        return $this->select($this->table . '.*', 'users.name as user_name')
            ->where('company_id', $company_id)
            ->where('type', 1)
            ->orderBy('created_at', 'desc')
            ->leftJoin('users', 'users.id', $this->table . '.user_id')
            ;
    }

    /**
     * Get all notes
     *
     * @return Companies
     */
    public function getAllSale($company_id)
    {
        return $this->select($this->table . '.*', 'sales.id as sale_id', 'receiptNumber', 'users.name as user_name')
            ->where($this->table . '.company_id', $company_id)
            ->where('type', 0)
            ->leftJoin('users', 'users.id', $this->table . '.user_id')
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
