<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('grupo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lider')->nullable();
            $table->foreign('id_lider')->references('id')->on('users');
            $table->string('nome');
            $table->string('code');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('grupo');
    }
};
