<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coach_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('training_session_id')->constrained('training_sessions')->cascadeOnDelete();
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['coach_user_id', 'training_session_id'], 'coach_session_unique');
            $table->index(['training_session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coach_attendances');
    }
};
