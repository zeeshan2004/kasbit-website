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

        $undergraduate = DB::table('header_menus')
            ->where('parent_id', $programsId)
            ->whereIn('name', ['Undergraduate', 'Undergraduate Program'])
            ->first();

        if (!$undergraduate) {
            return;
        }

        DB::table('header_menus')->where('id', $undergraduate->id)->update([
            'name' => 'Undergraduate',
            'link' => null,
            'sort_order' => 2,
            'updated_at' => now(),
        ]);

        DB::table('header_menu_pages')
            ->where('header_menu_id', $undergraduate->id)
            ->update([
                'eyebrow' => 'Programs',
                'title' => 'Undergraduate',
                'updated_at' => now(),
            ]);

        $items = [
            ['old' => ['BBA'], 'name' => 'BBA'],
            ['old' => ['BS Accounting & Finance', 'BS (AF)'], 'name' => 'BS (AF)'],
            ['old' => ['BS Computer Science'], 'name' => 'BS Computer Science'],
            [
                'old' => ['BBA 2 Years', 'BBA 2 Years (After 14 years of Education)', 'BBA 2 Years (After 14 Years of Education)'],
                'name' => 'BBA 2 Years (After 14 Years of Education)',
            ],
        ];

        foreach ($items as $index => $item) {
            $menu = DB::table('header_menus')
                ->where('parent_id', $undergraduate->id)
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
                    'eyebrow' => 'Undergraduate',
                    'title' => $item['name'],
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        // Preserve CMS content and the corrected Undergraduate hierarchy.
    }
};
