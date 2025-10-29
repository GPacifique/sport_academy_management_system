<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'photo_path')) {
                $table->string('photo_path')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('students', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('joined_at')->constrained('branches')->nullOnDelete();
            }
            if (!Schema::hasColumn('students', 'group_id')) {
                $table->foreignId('group_id')->nullable()->after('branch_id')->constrained('groups')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'group_id')) {
                $table->dropConstrainedForeignId('group_id');
            }
            if (Schema::hasColumn('students', 'branch_id')) {
                $table->dropConstrainedForeignId('branch_id');
            }
            if (Schema::hasColumn('students', 'photo_path')) {
                $table->dropColumn('photo_path');
            }
        });
    }
};
