<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    public function subcategory(){
        return $this->hasMany('App\Models\Category', 'parent_category_id');
    }
}
