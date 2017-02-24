<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{

    protected $table = 'roles';

    /**
     * Get all roles
     *
     * @return Companies
     */
    public function getAll()
    {
        return $this->select($this->table . '.*')->get();
    }
}
