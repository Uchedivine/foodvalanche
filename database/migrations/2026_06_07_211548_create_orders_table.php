<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number')->unique();
            $table->string('guest_name')->nullable();
            $table->string('guest_phone')->nullable();
            $table->string('guest_email')->nullable();
            $table->enum('status', [
                'pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'
            ])->default('pending');
            $table->enum('order_type', ['dine_in', 'takeout', 'delivery']);
            $table->string('table_identifier')->nullable();
            $table->enum('payment_method', ['pay_on_delivery', 'online_payment']);
            $table->enum('payment_status', [
                'unpaid', 'paid', 'partial_payment', 'refunded'
            ])->default('unpaid');
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('delivery_fee')->default(0);
            $table->unsignedBigInteger('total')->default(0);
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('delivery_address_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('estimated_ready_at')->nullable();
            $table->boolean('requires_verification')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};