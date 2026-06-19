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

        $adpGroupId = DB::table('header_menus')
            ->where('parent_id', $programsId)
            ->where('name', 'Associate Degree Program 2 Years')
            ->value('id');

        if (!$adpGroupId) {
            return;
        }

        $programNames = [
            'Associate Degree In Commerce (Previous B.COM)',
            'Associate Degree in Computer Science',
            'Associate Degree In Digital Marketing',
        ];

        foreach ($programNames as $index => $name) {
            $existingDirect = DB::table('header_menus')
                ->where('parent_id', $programsId)
                ->whereRaw('LOWER(name) = ?', [strtolower($name)])
                ->first();

            $seededNested = DB::table('header_menus')
                ->where('parent_id', $adpGroupId)
                ->whereRaw('LOWER(name) = ?', [strtolower($name)])
                ->first();

            if ($existingDirect) {
                if ($seededNested && $seededNested->id !== $existingDirect->id) {
                    DB::table('header_menus')->where('id', $seededNested->id)->delete();
                }

                DB::table('header_menus')->where('id', $existingDirect->id)->update([
                    'parent_id' => $adpGroupId,
                    'sort_order' => $index + 1,
                    'updated_at' => now(),
                ]);
            } elseif ($seededNested) {
                DB::table('header_menus')->where('id', $seededNested->id)->update([
                    'sort_order' => $index + 1,
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Keep the corrected nested structure and existing CMS content.
    }
};
