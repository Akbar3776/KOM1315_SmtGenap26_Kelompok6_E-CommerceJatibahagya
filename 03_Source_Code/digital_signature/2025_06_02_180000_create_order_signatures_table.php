<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('signature_id')->unique();
            $table->text('signature');
            $table->string('data_hash', 64);
            $table->string('algorithm', 20)->default('SHA256');
            $table->timestamp('signed_at');
            $table->timestamp('verified_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('signature_id');
            $table->index('signed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_signatures');
    }
};