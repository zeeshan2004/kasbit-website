<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $programsId = DB::table('header_menus')
            ->whereNull('parent_id')
            ->whereRaw('LOWER(name) = ?', ['programs'])
            ->value('id');

        if (! $programsId) {
            return;
        }

        $container = DB::table('header_menus')
            ->where('parent_id', $programsId)
            ->whereRaw('LOWER(name) = ?', ['associate degree program 2 years'])
            ->first();

        if (! $container) {
            return;
        }

        $target = DB::table('header_menus')
            ->where('parent_id', $container->id)
            ->whereRaw('LOWER(name) = ?', ['associate degree in computer science'])
            ->first();

        if (! $target) {
            return;
        }

        $sourcePage = DB::table('header_menu_pages')
            ->where('header_menu_id', $container->id)
            ->first();
        $targetPage = DB::table('header_menu_pages')
            ->where('header_menu_id', $target->id)
            ->first();

        if (! $sourcePage || ! $targetPage) {
            return;
        }

        DB::transaction(function () use ($container, $sourcePage, $target, $targetPage): void {
            DB::table('header_menu_pages')
                ->where('id', $targetPage->id)
                ->update([
                    'eyebrow' => $sourcePage->eyebrow,
                    'subtitle' => $sourcePage->subtitle,
                    'content' => $sourcePage->content,
                    'image' => $sourcePage->image,
                    'pdf_file' => $sourcePage->pdf_file,
                    'pdf_original_name' => $sourcePage->pdf_original_name,
                    'accent_color' => $sourcePage->accent_color,
                    'show_image' => $sourcePage->show_image,
                    'updated_at' => now(),
                ]);

            $relatedTables = [
                'header_menu_page_slides',
                'program_schema_tables',
                'academic_calendar_tables',
                'academic_departments',
                'page_gallery_images',
                'event_albums',
                'elibrary_resources',
            ];

            foreach ($relatedTables as $table) {
                $sourceHasContent = DB::table($table)
                    ->where('header_menu_page_id', $sourcePage->id)
                    ->exists();

                if (! $sourceHasContent) {
                    continue;
                }

                DB::table($table)
                    ->where('header_menu_page_id', $targetPage->id)
                    ->delete();
                DB::table($table)
                    ->where('header_menu_page_id', $sourcePage->id)
                    ->update([
                        'header_menu_page_id' => $targetPage->id,
                        'updated_at' => now(),
                    ]);
            }

            DB::table('header_menu_pages')
                ->where('id', $sourcePage->id)
                ->update([
                    'subtitle' => null,
                    'content' => null,
                    'image' => null,
                    'pdf_file' => null,
                    'pdf_original_name' => null,
                    'updated_at' => now(),
                ]);

            DB::table('header_menus')
                ->where('id', $container->id)
                ->update([
                    'link' => null,
                    'updated_at' => now(),
                ]);
            DB::table('header_menus')
                ->where('id', $target->id)
                ->update([
                    'link' => '/pages/'.$targetPage->slug,
                    'updated_at' => now(),
                ]);
        });

        Cache::store('file')->forget('frontend:header-menus:v1');
    }

    public function down(): void
    {
        // Preserve content edited on the corrected Computer Science child page.
    }
};
