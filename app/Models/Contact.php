<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contatos';

    protected $fillable = [
        'nome',
        'email',
        'assunto',
        'mensagem',
        'user_id',
        'resposta',
        'respondido_em',
    ];

    protected function casts(): array
    {
        return [
            'respondido_em' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
