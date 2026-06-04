<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('hash_value', 64);
            $table->text('signature');
            $table->string('algorithm', 20)->default('RSA-SHA256');
            $table->foreignId('signed_by_admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('signed_at')->useCurrent();
            $table->timestamps();

            $table->index('order_id');
            $table->index('signed_by_admin_id');
            $table->index('signed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_signatures');
    }
};