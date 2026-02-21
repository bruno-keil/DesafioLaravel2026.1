<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'email',
        'password',
        'cpf',
        'telefone',
        'data_nascimento',
        'saldo',
        'foto',
        'is_admin',
        'created_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
            'saldo' => 'decimal:2',
            'is_admin' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function addresses() {
        return $this->hasMany(Address::class, 'user_id');
    }

    public function address() {
        return $this->hasOne(Address::class, 'user_id');
    }

    public function defaultAddress() {
        return $this->addresses()->where('is_default', true)->first()
            ?? $this->addresses()->first();
    }

    public function products() {
        return $this->hasMany(Product::class, 'user_id');
    }

    public function cartItems() {
        return $this->hasMany(CartItem::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'comprador_id');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
