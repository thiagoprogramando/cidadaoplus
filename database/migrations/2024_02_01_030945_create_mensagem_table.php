<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void {
        Schema::create('mensagem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_whatsapp');

            $table->longText('texto');
            $table->longText('base64')->nullable();

            $table->longText('phone_number_id');
            $table->longText('user_access_token');

            $table->string('status');
            $table->string('code');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('mensagem');
    }
};
