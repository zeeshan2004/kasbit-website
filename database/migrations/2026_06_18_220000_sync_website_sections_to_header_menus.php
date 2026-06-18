<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sections = [
            ['About', 'fa-solid fa-circle-info', ['About Us', 'Message', 'International Board of Advisors']],
            ['Programs', 'fa-solid fa-book-open', [
                'Associate Degree Program 2 Years', 'Undergraduate Program', 'Graduate Program',
                'Postgraduate', 'Fee Structure', 'Program Profile',
            ]],
            ['Admissions', 'fa-solid fa-file-signature', ['Admission Policy', 'Online Admission Portal']],
            ['Academics', 'fa-solid fa-building-columns', [
                "Dean's Message", 'Faculty', 'Academic Calendar', 'Academic Departments',
                'Business Administration', 'Computer Science & IS', 'Academic Scholarship',
                'Rules & Regulations',
            ]],
            ['Life @ Kasbit', 'fa-solid fa-circle-nodes', [
                'Facilities & Services', 'Life on Premises', 'Student Societies', 'Event Gallery',
            ]],
            ['QEC', 'fa-solid fa-shield-halved', [
                'QEC Cell Message', 'Quality Policy Statement', 'QEC Structure', 'QEC Staff Details',
                'Functions of QEC', 'Student Survey Forms', 'QEC Activity Calendar', 'QEC Activities',
                'Yearly Progress Report', 'Self Assessment Report', 'Memberships',
                'AT / PT Notification', 'SDG',
            ]],
            ['ORIC', 'fa-solid fa-flask', [
                'Introduction', 'Research Journals', 'Conferences', 'Trainings & Workshops',
                'Research Project / Thesis', 'Industrial Linkage',
            ]],
            ['Login', 'fa-solid fa-right-to-bracket', [
                'Faculty Login', 'Student Login', 'Results', 'Convocation Registration',
            ]],
            ['Alumni', 'fa-solid fa-user-graduate', ['Office of Alumni', 'Alumni Login']],
            ['E Library', 'fa-solid fa-book', ['Kasbit E Library', 'E Library Resources']],
        ];

        foreach ($sections as $sectionOrder => [$name, $icon, $children]) {
            $parent = DB::table('header_menus')
                ->whereNull('parent_id')
                ->whereRaw('LOWER(name) = ?', [strtolower($name)])
                ->first();

            if ($parent) {
                DB::table('header_menus')->where('id', $parent->id)->update([
                    'name' => $name,
                    'icon' => $icon,
                    'show_in_admin_sidebar' => true,
                    'sort_order' => $sectionOrder + 2,
                    'updated_at' => now(),
                ]);
                $parentId = $parent->id;
            } else {
                $parentId = DB::table('header_menus')->insertGetId([
                    'parent_id' => null,
                    'name' => $name,
                    'link' => '#',
                    'icon' => $icon,
                    'show_in_admin_sidebar' => true,
                    'sort_order' => $sectionOrder + 2,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach ($children as $childOrder => $childName) {
                if (!DB::table('header_menus')->where('parent_id', $parentId)->whereRaw('LOWER(name) = ?', [strtolower($childName)])->exists()) {
                    DB::table('header_menus')->insert([
                        'parent_id' => $parentId,
                        'name' => $childName,
                        'link' => '#',
                        'icon' => 'fa-solid fa-circle',
                        'show_in_admin_sidebar' => false,
                        'sort_order' => $childOrder + 1,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Preserve CMS content on rollback.
    }
};
