<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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

    public function address() {
        return $this->hasOne(Address::class, 'usuario_id');
    }

    public function products() {
        return $this->hasMany(Product::class, 'usuario_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'usuario_id');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
