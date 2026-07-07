<?php

namespace Database\Seeders;

use App\Models\HeaderMenuPage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembershipsTableSeeder extends Seeder
{
    public function run(): void
    {
        $page = HeaderMenuPage::where('slug', 'memberships')->first();

        if (! $page) {
            return;
        }

        DB::transaction(function () use ($page) {
            $table = $page->programSchemaTables()->updateOrCreate(
                ['title' => 'Memberships'],
                [
                    'qec_serial_label' => 'Logo’s',
                    'qec_col1_label' => 'Organization',
                    'qec_col2_label' => 'About the Organization',
                    'qec_col3_label' => 'Membership Status',
                    'qec_col4_label' => 'Membership Link',
                    'qec_show_col4' => true,
                    'qec_show_col5' => false,
                    'sort_order' => 1,
                    'is_active' => true,
                ]
            );

            $table->rows()->delete();

            foreach ($this->rows() as $index => $row) {
                $table->rows()->create([
                    'semester' => null,
                    'image_path' => null,
                    'subject' => $row[0],
                    'credit_hours' => $row[1],
                    'col3_text' => $row[2],
                    'col4_text' => $row[3],
                    'col5_text' => null,
                    'is_total' => false,
                    'sort_order' => $index,
                ]);
            }
        });
    }

    private function rows(): array
    {
        return [
            [
                'APQN – Asia Pacific Quality Network',
                'APQN aims to enhance the quality of higher education in Asia and the Pacific region through building the capacity of quality assurance agencies and extending the cooperation between them.',
                'Institutional Member',
                'https://www.apqn.org',
            ],
            [
                'INQAAHE – International Network for Quality Assurance Agencies in Higher Education',
                'The Network is a not-for-profit-making organization. The purposes of the Network is to create, collect and disseminate information on current and developing theory and practice in the assessment, improvement and maintenance of quality in assignment help higher education',
                'Associate Member',
                'https://www.inqaahe.org',
            ],
            [
                'The Talloires Network',
                'The Talloires Network is an international association of institutions committed to strengthening the civic roles and social responsibilities of higher education.',
                'Full Member',
                'https://talloiresnetwork.tufts.edu',
            ],
            [
                'Association of Quality Assurance Agencies of the Islamic World (IQA)',
                'The Association of Quality Assurance Agencies of the Islamic World (QA-Islamic) was formally established on May 4, 2011 in an effort to promote and enhance quality higher education in the countries of the Islamic world.',
                'Associate Member',
                'https://iqa-world.org',
            ],
        ];
    }
}
