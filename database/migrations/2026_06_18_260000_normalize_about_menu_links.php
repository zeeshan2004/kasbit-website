<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $about = DB::table('header_menus')
            ->whereNull('parent_id')
            ->where('name', 'About')
            ->first();

        if (!$about) {
            return;
        }

        DB::table('header_menus')->where('id', $about->id)->update([
            'link' => '/about',
            'updated_at' => now(),
        ]);

        DB::table('header_menus')
            ->where('parent_id', $about->id)
            ->where('name', 'About Us')
            ->update([
                'link' => '/about',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        //
    }
};
