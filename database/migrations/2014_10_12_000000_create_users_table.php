<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lider');
            $table->foreign('id_lider')->references('id')->on('users')->onDelete('cascade');

            $table->string('nome');
            $table->string('cpf');
            $table->string('foto')->nullable();
            $table->date('dataNasc');
            $table->integer('sexo')->nullable(); // 1 - Masc 2 - Fem 3 - Outros
            $table->integer('civil')->nullable(); // 1 - Casado 2 - Solteiro 3- Viuvo 4 - Separado 5 - Outros
            $table->integer('escolaridade')->nullable(); // 1 - Fundamental 2 - Médio 3 - Superior 4 - Outros

            $table->string('email')->unique();
            $table->string('whatsapp')->unique();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();

            $table->string('cep')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->integer('zona'); // 1 - Norte 2 - Sul 3 - Leste 4 - Oeste 5 - Outros

            $table->integer('tipo'); // 1 - Master 2 - Liderança 3 - Eleitor
            $table->string('observacao')->nullable();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
