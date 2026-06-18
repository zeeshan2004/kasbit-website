<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('header_menu_page_slides', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
        });

        $pages = DB::table('header_menu_pages')
            ->where(function ($query) {
                $query->whereNotNull('image')
                    ->orWhereNotNull('content');
            })
            ->get();

        foreach ($pages as $page) {
            $hasUsefulContent = filled($page->image) || filled($page->content);

            if (!$hasUsefulContent) {
                continue;
            }

            DB::table('header_menu_page_slides')->insert([
                'header_menu_page_id' => $page->id,
                'title' => $page->title,
                'description' => $page->content,
                'image' => $page->image,
                'sort_order' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('header_menu_pages')->where('id', $page->id)->update([
                'content' => null,
                'image' => null,
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        //
    }
};
