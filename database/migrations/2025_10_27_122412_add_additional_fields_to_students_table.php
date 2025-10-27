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
        Schema::table('students', function (Blueprint $table) {
            // Rename last_name to second_name for consistency
            $table->renameColumn('last_name', 'second_name');
            
            // Add missing fields
            $table->string('father_name')->nullable()->after('gender');
            $table->string('email')->nullable()->after('father_name');
            $table->string('emergency_phone')->nullable()->after('email');
            $table->string('mother_name')->nullable()->after('emergency_phone');
            $table->string('school_name')->nullable()->after('sport_discipline');
            $table->string('position')->nullable()->after('school_name');
            $table->string('coach')->nullable()->after('position');
            $table->string('combination')->nullable()->after('group_id');
            $table->string('membership_type')->nullable()->after('combination');
            $table->string('program')->nullable()->after('joined_at');
            $table->foreignId('registered_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('second_name', 'last_name');
            $table->dropColumn([
                'father_name',
                'email',
                'emergency_phone',
                'mother_name',
                'school_name',
                'position',
                'coach',
                'combination',
                'membership_type',
                'program',
            ]);
            $table->dropConstrainedForeignId('registered_by');
        });
    }
};
