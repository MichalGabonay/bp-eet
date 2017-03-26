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
    public function getFivFromUser($user_id)
    {
        return $this->select($this->table . '.*');
    }
}
