<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('student')->after('id')->index();
            $table->string('student_id', 50)->nullable()->unique()->after('email');
            $table->foreignId('department_id')
                ->nullable()
                ->after('student_id')
                ->constrained('departments')
                ->nullOnDelete();
            $table->string('semester', 50)->nullable()->after('department_id');
            $table->boolean('is_active')->default(true)->after('semester')->index();
        });

        // The existing users table previously contained administrators only.
        DB::table('users')->update(['role' => 'admin']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropUnique(['student_id']);
            $table->dropColumn([
                'role',
                'student_id',
                'department_id',
                'semester',
                'is_active',
            ]);
        });
    }
};
