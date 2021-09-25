<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_has_category', 'product_id', 'category_id');
    }
}
