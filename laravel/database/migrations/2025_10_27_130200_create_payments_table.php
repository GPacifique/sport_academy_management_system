<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
            $table->unsignedInteger('amount_cents');
            $table->string('currency', 3)->default('RWF');
            $table->enum('method', ['cash','mobile_money','card','bank']);
            $table->enum('status', ['pending','succeeded','failed'])->default('succeeded');
            $table->timestamp('paid_at')->nullable();
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['student_id','subscription_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
