<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Sales extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'company_id', 'products', 'total_price', 'fik', 'bkp',
                            'receiptNumber', 'premiseId', 'cash_register', 'storno', 'receipt_time', 'not_sent'];


    /**
     * Get all notes
     *
     * @return Companies
     */
    public function getAll($company_id)
    {
        return $this->select($this->table . '.*', 'users.name as user_name')
            ->where($this->table . '.company_id', $company_id)
            ->where($this->table . '.deleted_at', NULL)
            ->leftJoin('users', 'users.id', $this->table . '.user_id')
            ->orderBy('id', 'desc');
    }

    /**
     * Get all sales for charts
     *
     * @return Companies
     */
    public function getAllForChart($company_id)
    {
        return $this->select(
            DB::raw("DATE(receipt_time) as date"),
            DB::raw("SUM(total_price) as total_price"),
            DB::raw("COUNT(total_price) as count"))
            ->where($this->table . '.company_id', $company_id)
            ->where($this->table . '.deleted_at', NULL)
            ->where($this->table . '.storno', 0)
            ->where($this->table . '.not_sent', 0)
            ->orderBy("receipt_time")
            ->groupBy('date');
    }


    public function getAllForSmallChart($company_id)
    {
        return $this->select('receipt_time as date', 'total_price')
            ->where($this->table . '.company_id', $company_id)
            ->where($this->table . '.deleted_at', NULL)
            ->where($this->table . '.storno', 0)
            ->orderBy("receipt_time");
    }

    /**
     * Get all for export
     *
     * @return Companies
     */
    public function getAllForExport($company_id)
    {
        return $this->select($this->table . '.*', 'users.name as user_name')
            ->where($this->table . '.deleted_at', NULL)
            ->where($this->table . '.storno', 0)
            ->where($this->table . '.company_id', $company_id)
            ->leftJoin('users', 'users.id', $this->table . '.user_id')
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Get all not sent sales
     *
     * @return Companies
     */
    public function getNotSent($company_id)
    {
        return $this->select($this->table . '.*')
            ->where($this->table . '.deleted_at', NULL)
            ->where($this->table . '.storno', 0)
            ->where($this->table . '.not_sent', 1)
            ->where($this->table . '.company_id', $company_id)
            ->orderBy('id', 'desc')
            ->get();
    }
}