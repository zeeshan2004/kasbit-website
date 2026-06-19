<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $programsId = DB::table('header_menus')
            ->whereNull('parent_id')
            ->where('name', 'Programs')
            ->value('id');

        if (!$programsId) {
            return;
        }

        $graduate = DB::table('header_menus')
            ->where('parent_id', $programsId)
            ->whereIn('name', ['Graduate Program', 'Graduate Programs'])
            ->first();

        if (!$graduate) {
            return;
        }

        DB::table('header_menus')->where('id', $graduate->id)->update([
            'name' => 'Graduate Programs',
            'link' => null,
            'sort_order' => 3,
            'updated_at' => now(),
        ]);

        DB::table('header_menu_pages')
            ->where('header_menu_id', $graduate->id)
            ->update([
                'eyebrow' => 'Programs',
                'title' => 'Graduate Programs',
                'updated_at' => now(),
            ]);

        $items = [
            [
                'old' => ['MBA (36) After 4 Years Bachelors', 'MBA (36) after 4 years Bachelors'],
                'name' => 'MBA (36) after 4 years Bachelors',
            ],
            [
                'old' => [
                    'MBA (66) After 16 Years Non-Business',
                    'MBA (66) After 16 Year Non Business Schooling',
                ],
                'name' => 'MBA (66) After 16 Year Non Business Schooling',
            ],
            ['old' => ['MS'], 'name' => 'MS'],
        ];

        foreach ($items as $index => $item) {
            $menu = DB::table('header_menus')
                ->where('parent_id', $graduate->id)
                ->whereIn('name', $item['old'])
                ->first();

            if (!$menu) {
                continue;
            }

            DB::table('header_menus')->where('id', $menu->id)->update([
                'name' => $item['name'],
                'sort_order' => $index + 1,
                'is_active' => true,
                'updated_at' => now(),
            ]);

            DB::table('header_menu_pages')
                ->where('header_menu_id', $menu->id)
                ->update([
                    'eyebrow' => 'Graduate Programs',
                    'title' => $item['name'],
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        // Preserve CMS content and the corrected Graduate hierarchy.
    }
};
