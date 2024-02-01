<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('whatsapp', function (Blueprint $table) {
            $table->id();
            $table->string('instanceName');
            $table->longText('webhookUrl');
            $table->longText('tokenKey')->nullable();
            $table->string('statusCode')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('whatsapp');
    }
};
