<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function categories(){
        return $this->hasMany('App\Models\ProductCategories', 'product_id')->get();
    }
}
