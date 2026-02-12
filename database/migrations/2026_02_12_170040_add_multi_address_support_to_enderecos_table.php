<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enderecos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('usuarios')->cascadeOnDelete();
            $table->string('nome', 50)->default('Principal')->after('user_id');
            $table->boolean('is_default')->default(false)->after('uf');
        });

        DB::table('enderecos')->update(['is_default' => true]);
    }

    public function down(): void
    {
        Schema::table('enderecos', function (Blueprint $table) {
            $table->dropColumn(['nome', 'is_default']);
            $table->unique('user_id');
        });
    }
};
