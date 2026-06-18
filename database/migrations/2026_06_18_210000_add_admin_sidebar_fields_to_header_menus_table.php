<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('header_menus', function (Blueprint $table) {
            $table->string('icon', 100)->nullable()->after('link');
            $table->boolean('show_in_admin_sidebar')->default(false)->after('icon');
        });

        DB::table('header_menus')
            ->whereNull('parent_id')
            ->where('name', 'About')
            ->update([
                'icon' => 'fa-solid fa-circle-info',
                'show_in_admin_sidebar' => true,
                'sort_order' => 2,
            ]);
    }

    public function down(): void
    {
        Schema::table('header_menus', function (Blueprint $table) {
            $table->dropColumn(['icon', 'show_in_admin_sidebar']);
        });
    }
};
