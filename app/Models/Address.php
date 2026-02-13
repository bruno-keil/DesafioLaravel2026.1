<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Address extends Model
{
    protected $table = 'enderecos';

    protected $fillable = [
        'user_id', 'nome', 'cep', 'logradouro', 'numero', 'bairro',
        'cidade', 'estado', 'complemento', 'uf', 'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::saving(function ($address) {
            if ($address->isDirty('cep') && strlen($address->cep) == 8) {
                
                $response = Http::get(url("/api/cep/{$address->cep}"));

                if ($response->successful() && !isset($response['erro'])) {
                    $data = $response->json();
                    
                    $address->logradouro = $data['logradouro'] ?? $address->logradouro;
                    $address->bairro = $data['bairro'] ?? $address->bairro;
                    $address->cidade = $data['localidade'] ?? $address->cidade;
                    $address->uf = $data['uf'] ?? $address->uf;
                }
            }
        });
    }
}