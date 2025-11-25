<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();

            $table->string('provider'); // duitku
            $table->string('merchant_code');
            $table->string('api_key');
            $table->string('callback_url')->nullable();
            $table->string('return_url')->nullable();
            $table->enum('environment', ['sandbox', 'production'])->default('sandbox');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
