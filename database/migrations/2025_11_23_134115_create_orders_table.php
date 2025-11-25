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
       Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Jika ada login user
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('order_code')->unique(); // gunakan sebagai merchantOrderId Duitku

            // Data customer
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_address');
            $table->string('city')->nullable();
            $table->string('area')->nullable();

            // Pengiriman
            $table->date('delivery_date')->nullable();
            $table->string('delivery_time_slot')->nullable(); // "pagi", "siang", dll
            $table->string('notes')->nullable();

            // Keuangan
            $table->integer('subtotal');
            $table->integer('delivery_fee')->default(0);
            $table->integer('discount_amount')->default(0);
            $table->integer('grand_total'); // final + yang dikirim ke Duitku

            // Status order
            $table->enum('status', [
                'draft',
                'pending_payment',
                'paid',
                'processing',
                'shipped',
                'completed',
                'cancelled',
            ])->default('pending_payment');

            // Relasi ke payment (nullable dulu)
            // $table->foreignId('payment_id')->nullable()->constrained('payments');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
