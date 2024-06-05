<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_creator')->nullable();
            $table->unsignedBigInteger('id_company')->nullable();

            $table->string('name');
            $table->string('photo')->nullable();
            $table->date('birth')->nullable();
            $table->integer('sex')->nullable(); // 1 - Masc 2 - Fem 3 - Outros
            $table->string('profession')->nullable();

            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();

            $table->string('postal_code')->nullable();
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();

            $table->longText('obs')->nullable();

            $table->integer('type'); // 1 - Administrador 2 - Apoiador 3 - Cidadão 4 - Coordenador
            $table->string('password');
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
