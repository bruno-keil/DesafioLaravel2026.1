<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categorias';

    protected $fillable = ['nome'];

    public function products()
    {
        return $this->hasMany(Product::class, 'categoria_id');
    }
}