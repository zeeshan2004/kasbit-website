<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('header_menus', function (Blueprint $table) {
            $table->string('management_context', 20)
                ->nullable()
                ->after('show_in_admin_sidebar');
        });

        $programsId = DB::table('header_menus')
            ->whereNull('parent_id')
            ->where('name', 'Programs')
            ->value('id');

        if (! $programsId) {
            return;
        }

        $programGroupIds = DB::table('header_menus')
            ->where('parent_id', $programsId)
            ->pluck('id');

        DB::table('header_menus')
            ->whereIn('parent_id', $programGroupIds)
            ->where('created_at', '>=', '2026-07-27 00:00:00')
            ->update(['management_context' => 'registration']);

        Cache::store('file')->forget('frontend:header-menus:v1');
    }

    public function down(): void
    {
        Schema::table('header_menus', function (Blueprint $table) {
            $table->dropColumn('management_context');
        });

        Cache::store('file')->forget('frontend:header-menus:v1');
    }
};
