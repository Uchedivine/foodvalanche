<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->enum('gateway', ['paystack'])->default('paystack');
            $table->string('gateway_reference')->nullable()->unique();
            $table->json('gateway_response')->nullable();
            $table->unsignedBigInteger('amount')->default(0);
            $table->unsignedBigInteger('expected_amount');
            $table->string('currency')->default('NGN');
            $table->enum('status', [
                'pending', 'paid', 'partial_payment', 'failed', 'refunded'
            ])->default('pending');
            $table->timestamp('initiated_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};