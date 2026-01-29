<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'produtos';

    protected $fillable = [
        'user_id', 'nome', 'descricao', 'foto', 
        'preco', 'quantidade', 'categoria_id'
    ];

    protected $casts = [
        'preco' => 'decimal:2',
        'quantidade' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class, 'produto_id');
    }
}