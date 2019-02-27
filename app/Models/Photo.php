<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table='photos';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function User()
    {
        return $this->belongsTo('App\User');
    }
}
