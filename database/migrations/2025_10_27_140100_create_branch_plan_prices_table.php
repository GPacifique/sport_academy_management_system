<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('branch_plan_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->unsignedInteger('price_cents');
            $table->timestamps();
            $table->unique(['branch_id','subscription_plan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_plan_prices');
    }
};
