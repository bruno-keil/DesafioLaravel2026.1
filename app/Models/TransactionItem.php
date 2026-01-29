<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $table = 'itens_transacoes';

    protected $fillable = [
        'transacao_id', 'produto_id', 'quantidade', 'valor_unitario'
    ];

    protected $casts = [
        'valor_unitario' => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transacao_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'produto_id');
    }
}