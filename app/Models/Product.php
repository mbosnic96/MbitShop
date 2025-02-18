<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock_quantity',
        'brand_id',
        'model',
        'processor',
        'ram_size',
        'storage',
        'graphics_card',
        'operating_system',
        'created_at',
        'updated_at',
        'category_id',
        'image',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
}
