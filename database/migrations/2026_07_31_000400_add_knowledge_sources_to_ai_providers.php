<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_providers', function (Blueprint $table) {
            $table->text('knowledge_source_url')->nullable()->after('system_prompt');
            $table->text('knowledge_api_url')->nullable()->after('knowledge_source_url');
            $table->string('knowledge_api_key_env', 100)->nullable()->after('knowledge_api_url');
        });
    }

    public function down(): void
    {
        Schema::table('ai_providers', function (Blueprint $table) {
            $table->dropColumn([
                'knowledge_source_url',
                'knowledge_api_url',
                'knowledge_api_key_env',
            ]);
        });
    }
};
