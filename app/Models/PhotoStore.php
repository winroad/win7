<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoStore extends Model
{
    protected $table='photo_stores';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function Photo()
    {
        return $this->belongsTo('App\Models\Photo');
    }
}