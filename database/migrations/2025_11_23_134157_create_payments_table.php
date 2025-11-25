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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Relasi opsional
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Identitas transaksi
            $table->string('merchant_order_id')->unique();
            $table->integer('amount');
            $table->string('currency')->default('IDR');
            $table->string('product_details')->nullable();
            $table->string('additional_param')->nullable();
            $table->string('payment_method')->nullable();

            // Response dari Duitku
            $table->string('reference')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('va_number')->nullable();
            $table->text('qr_string')->nullable();
            $table->string('status_code')->nullable();
            $table->string('status_message')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Status internal
            $table->enum('status', [
                'pending',
                'paid',
                'expired',
                'failed',
                'cancelled'
            ])->default('pending');

            $table->string('result_code')->nullable();
            $table->string('publisher_order_id')->nullable();
            $table->string('issuer_name')->nullable();
            $table->string('issuer_bank')->nullable();
            $table->dateTime('settlement_date')->nullable();
            $table->dateTime('callback_at')->nullable();
            $table->dateTime('paid_at')->nullable();

            // Logging
            $table->string('callback_signature')->nullable();
            $table->longText('raw_callback')->nullable();
            $table->longText('raw_request')->nullable();
            $table->longText('raw_response')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
