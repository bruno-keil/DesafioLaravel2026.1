<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transacoes';

    protected $fillable = [
        'comprador_id', 'valor_total', 'data', 'status'
    ];

    protected $casts = [
        'valor_total' => 'decimal:2',
        'data' => 'datetime',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'comprador_id');
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transacao_id');
    }
}