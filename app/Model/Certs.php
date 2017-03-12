<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Certs extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'certs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['pks12', 'password', 'valid', 'expiration_date'];

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
}
