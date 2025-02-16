<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['id','name', 'parent_id', 'position'];


    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function children()
{
    return $this->hasMany(Category::class, 'parent_id');
}
}
