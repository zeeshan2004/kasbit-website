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
        Schema::create('header_menu_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('header_menu_id')->unique()->constrained('header_menus')->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('eyebrow')->nullable();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->string('accent_color', 7)->default('#07559d');
            $table->boolean('show_image')->default(true);
            $table->timestamps();
        });

        $children = DB::table('header_menus')
            ->whereNotNull('parent_id')
            ->orderBy('id')
            ->get();

        $usedSlugs = [];

        foreach ($children as $child) {
            $base = Str::slug($child->name) ?: 'page-' . $child->id;
            $slug = $base;
            $suffix = 2;

            while (in_array($slug, $usedSlugs, true)) {
                $slug = $base . '-' . $suffix++;
            }

            $usedSlugs[] = $slug;

            DB::table('header_menu_pages')->insert([
                'header_menu_id' => $child->id,
                'slug' => $slug,
                'eyebrow' => DB::table('header_menus')->where('id', $child->parent_id)->value('name'),
                'title' => $child->name,
                'subtitle' => null,
                'content' => null,
                'accent_color' => '#07559d',
                'show_image' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('header_menus')->where('id', $child->id)->update([
                'link' => $child->name === 'About Us' ? '/about' : '/pages/' . $slug,
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('header_menu_pages');
    }
};
