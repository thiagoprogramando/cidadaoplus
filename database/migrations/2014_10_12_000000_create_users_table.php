<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lider');
            $table->foreign('id_lider')->references('id')->on('users');
            $table->unsignedBigInteger('id_grupo');
            $table->foreign('id_grupo')->references('id')->on('grupo');

            $table->string('nome');
            $table->string('foto')->nullable();
            $table->date('dataNasc')->nullable();
            $table->integer('sexo')->nullable(); // 1 - Masc 2 - Fem 3 - Outros
            $table->string('profissao')->nullable();

            $table->string('email')->unique()->nullable();
            $table->string('whatsapp')->unique();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();

            $table->string('cep')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();

            $table->integer('tipo'); // 1 - Master 2 - Liderança 3 - Eleitor
            $table->string('password')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
