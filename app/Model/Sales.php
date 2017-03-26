<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

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
                            'receiptNumber', 'premiseId', 'cash_register', 'storno'];


    /**
     * Get all notes
     *
     * @return Companies
     */
    public function getAll()
    {
        return $this->select($this->table . '.*', 'users.name as user_name')
            ->leftJoin('users', 'users.id', $this->table . '.user_id')
            ->orderBy('id', 'desc');
    }
}
