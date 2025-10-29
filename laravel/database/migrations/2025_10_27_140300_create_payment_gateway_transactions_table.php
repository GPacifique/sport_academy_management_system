<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_gateway_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->string('gateway'); // flutterwave, stripe, paypal, etc
            $table->string('transaction_id')->unique();
            $table->unsignedInteger('amount_cents');
            $table->string('currency', 3)->default('RWF');
            $table->enum('status', ['pending','processing','succeeded','failed','refunded'])->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['gateway','transaction_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateway_transactions');
    }
};
