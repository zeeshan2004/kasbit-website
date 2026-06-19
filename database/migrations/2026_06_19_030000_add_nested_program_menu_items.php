<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $programGroups = [
            'Associate Degree Program 2 Years' => [
                'Associate Degree In Commerce (Previous B.COM)',
                'Associate Degree in Computer Science',
                'Associate Degree In Digital Marketing',
            ],
            'Undergraduate Program' => [
                'BBA',
                'BS Accounting & Finance',
                'BS Computer Science',
                'BBA 2 Years',
            ],
            'Graduate Program' => [
                'MBA (36) After 4 Years Bachelors',
                'MBA (66) After 16 Years Non-Business',
                'MS',
            ],
            'Postgraduate' => [
                'Ph.D',
            ],
        ];

        $programsId = DB::table('header_menus')
            ->whereNull('parent_id')
            ->where('name', 'Programs')
            ->value('id');

        if (!$programsId) {
            return;
        }

        foreach ($programGroups as $groupName => $programNames) {
            $groupId = DB::table('header_menus')
                ->where('parent_id', $programsId)
                ->where('name', $groupName)
                ->value('id');

            if (!$groupId) {
                continue;
            }

            foreach ($programNames as $index => $programName) {
                $menuId = DB::table('header_menus')
                    ->where('parent_id', $groupId)
                    ->where('name', $programName)
                    ->value('id');

                if (!$menuId) {
                    $menuId = DB::table('header_menus')->insertGetId([
                        'parent_id' => $groupId,
                        'name' => $programName,
                        'link' => '#',
                        'icon' => 'fa-solid fa-graduation-cap',
                        'show_in_admin_sidebar' => false,
                        'sort_order' => $index + 1,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                if (!DB::table('header_menu_pages')->where('header_menu_id', $menuId)->exists()) {
                    $slug = $this->uniqueSlug($programName);

                    DB::table('header_menu_pages')->insert([
                        'header_menu_id' => $menuId,
                        'slug' => $slug,
                        'eyebrow' => 'Programs',
                        'title' => $programName,
                        'subtitle' => null,
                        'content' => null,
                        'image' => null,
                        'accent_color' => '#07559d',
                        'show_image' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('header_menus')->where('id', $menuId)->update([
                        'link' => '/pages/'.$slug,
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'program';
        $slug = $base;
        $suffix = 2;

        while (DB::table('header_menu_pages')->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    public function down(): void
    {
        // Preserve program pages created through the CMS hierarchy.
    }
};
