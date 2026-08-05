<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_knowledge_data', function (Blueprint $table) {
            $table->id();
            $table->string('intent', 50)->index(); // fee, faculty, program, admission, campus, general
            $table->string('title')->index(); // "Dr. Basheer", "BBA Fee", "BSCS Program"
            $table->longText('content'); // full info as plain text
            $table->string('keywords', 500)->nullable(); // extra search keywords
            $table->boolean('is_active')->default(true)->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->fulltext(['title', 'content', 'keywords']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_knowledge_data');
    }
};
