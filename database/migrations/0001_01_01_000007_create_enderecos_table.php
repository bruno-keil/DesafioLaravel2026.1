<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('usuarios')->unique();
            $table->string('cep', 8);
            $table->string('logradouro')->nullable();
            $table->string('numero', 20);
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 50)->nullable();
            $table->string('complemento')->nullable();
            $table->string('uf', 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enderecos');
    }
};
