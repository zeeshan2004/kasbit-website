<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $menus = [
            'About' => ['About Us', 'Message', 'International Board of Advisors'],
            'Programs' => [
                'Associate Degree Program 2 Years',
                'Undergraduate Program',
                'Graduate Program',
                'Postgraduate',
                'Fee Structure',
                'Program Profile',
            ],
            'Admissions' => ['Admission Policy', 'Online Admission Portal'],
            'Academics' => [
                "Dean's Message",
                'Faculty',
                'Academic Calendar',
                'Academic Departments',
                'Academic Scholarship',
                'Rules & Regulations',
            ],
            'Life @ Kasbit' => [
                'Facilities & Services',
                'Life on Premises',
                'Student Societies',
                'Event Gallery',
            ],
            'QEC' => [
                'Quality Enhancement Cell Message',
                'Quality Policy Statement',
                'QEC Structure',
                'QEC Staff Details',
                'Functions of QEC',
                'Student Survey Forms',
                'QEC Activity Calender',
                'QEC Activities',
                'Yearly Progress Report',
                'Self Assessment Report',
                'Memberships',
                'AT / PT Notification',
                'SDG',
            ],
            'ORIC' => [
                'Introduction',
                'Research Journals',
                'Conferences',
                'Trainings & Workshops',
                'Research Project / Thesis',
                'Industrial Linkage',
            ],
            'Login' => [
                'Faculty Login',
                'Student Login',
                'Results',
                'Convocation Registration',
            ],
            'Alumni' => ['Office of Alumni', 'Alumni Login'],
            'E Library' => ['Kasbit E Library', 'E Library Resources'],
        ];

        $loginManagement = DB::table('header_menus')
            ->whereNull('parent_id')
            ->where('name', 'Login Management')
            ->first();

        if ($loginManagement) {
            DB::table('header_menus')->where('id', $loginManagement->id)->update([
                'name' => 'Login',
                'updated_at' => now(),
            ]);
        }

        foreach ($menus as $parentName => $children) {
            $parent = DB::table('header_menus')
                ->whereNull('parent_id')
                ->where('name', $parentName)
                ->first();

            if (!$parent) {
                continue;
            }

            DB::table('header_menus')->where('parent_id', $parent->id)->delete();

            foreach ($children as $sortOrder => $childName) {
                DB::table('header_menus')->insert([
                    'parent_id' => $parent->id,
                    'name' => $childName,
                    'link' => '#',
                    'icon' => $this->iconFor($childName),
                    'show_in_admin_sidebar' => false,
                    'sort_order' => $sortOrder + 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function iconFor(string $name): string
    {
        return match (true) {
            str_contains($name, 'Message') => 'fa-solid fa-message',
            str_contains($name, 'International') => 'fa-solid fa-globe',
            str_contains($name, 'Faculty'),
            str_contains($name, 'Alumni') => 'fa-solid fa-users',
            str_contains($name, 'Calendar'),
            str_contains($name, 'Calender') => 'fa-solid fa-calendar-days',
            str_contains($name, 'Computer') => 'fa-solid fa-laptop-code',
            str_contains($name, 'Results') => 'fa-solid fa-chart-line',
            str_contains($name, 'Policy'),
            str_contains($name, 'Rules') => 'fa-solid fa-shield-halved',
            str_contains($name, 'Library'),
            str_contains($name, 'Program'),
            str_contains($name, 'Postgraduate') => 'fa-solid fa-book-open',
            default => 'fa-solid fa-circle',
        };
    }

    public function down(): void
    {
        // Preserve the corrected CMS structure.
    }
};
