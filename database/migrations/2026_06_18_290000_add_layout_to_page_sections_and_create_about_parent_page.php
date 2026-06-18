<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('header_menu_page_slides', function (Blueprint $table) {
            $table->string('image_position', 10)->default('left')->after('image');
        });

        $about = DB::table('header_menus')
            ->whereNull('parent_id')
            ->whereRaw('LOWER(name) LIKE ?', ['about%'])
            ->first();

        if (!$about || DB::table('header_menu_pages')->where('header_menu_id', $about->id)->exists()) {
            return;
        }

        $slug = Str::slug($about->name) ?: 'about-us';

        if (DB::table('header_menu_pages')->where('slug', $slug)->exists()) {
            $slug .= '-' . $about->id;
        }

        DB::table('header_menu_pages')->insert([
            'header_menu_id' => $about->id,
            'slug' => $slug,
            'eyebrow' => 'About KASBIT',
            'title' => $about->name,
            'subtitle' => null,
            'content' => null,
            'image' => null,
            'accent_color' => '#07559d',
            'show_image' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('header_menus')->where('id', $about->id)->update([
            'link' => '/about',
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('header_menu_page_slides', function (Blueprint $table) {
            $table->dropColumn('image_position');
        });
    }
};
