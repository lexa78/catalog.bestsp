<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }

    public function Category(){
        return $this->hasMany($this, 'parent_id');
    }

    public function rootCategories(){
        return $this->where('parent_id', null)->with('Category')->get();
    }
}
