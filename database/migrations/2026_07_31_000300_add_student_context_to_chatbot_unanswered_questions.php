<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chatbot_unanswered_questions', function (Blueprint $table) {
            $table->string('student_name')->nullable()->after('guest_session_id');
            $table->string('student_id', 30)->nullable()->after('student_name');
            $table->foreignId('department_id')->nullable()->after('student_id')
                ->constrained('departments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('chatbot_unanswered_questions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
            $table->dropColumn(['student_name', 'student_id']);
        });
    }
};
