<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function products()
    {
        return $this->belongsTo('App\Product');
    }
}
