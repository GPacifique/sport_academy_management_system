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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Who recorded the expense
            $table->string('category'); // e.g., equipment, salaries, utilities, maintenance, supplies, etc.
            $table->string('description');
            $table->text('notes')->nullable();
            $table->integer('amount_cents'); // Amount in cents
            $table->string('currency', 3)->default('RWF');
            $table->date('expense_date');
            $table->string('payment_method')->nullable(); // cash, bank_transfer, mobile_money, card
            $table->string('receipt_number')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, paid
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
