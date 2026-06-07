<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('request_details');
            $table->string('occasion')->nullable();
            $table->string('quantity_estimate')->nullable();
            $table->unsignedBigInteger('budget')->nullable();
            $table->date('preferred_date')->nullable();
            $table->json('attachments')->nullable();
            $table->enum('status', [
                'new', 'reviewing', 'quoted', 'accepted', 'declined'
            ])->default('new');
            $table->text('admin_note')->nullable();
            $table->unsignedBigInteger('quoted_amount')->nullable();
            $table->text('admin_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_requests');
    }
};