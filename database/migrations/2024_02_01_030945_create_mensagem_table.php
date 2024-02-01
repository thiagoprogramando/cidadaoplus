<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void {
        Schema::create('mensagem', function (Blueprint $table) {
            $table->id();
            $table->longText('texto');
            $table->longText('tokenKey');
            $table->longText('base64')->nullable();
            $table->string('numero');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('mensagem');
    }
};
