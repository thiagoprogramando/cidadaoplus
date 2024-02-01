<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('mensagem_log', function (Blueprint $table) {
            $table->id();
            $table->longText('retorno');
            $table->string('numero');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('mensagem_log');
    }
};
