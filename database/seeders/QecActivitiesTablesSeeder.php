<?php

namespace Database\Seeders;

use App\Models\HeaderMenuPage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QecActivitiesTablesSeeder extends Seeder
{
    public function run(): void
    {
        $page = HeaderMenuPage::where('slug', 'qec-activities')->firstOrFail();

        $this->replaceTable($page, [
            'title' => 'Contribution by QEC',
            'sort_order' => 2,
            'qec_col1_label' => 'Title of Workshop/Seminar',
            'qec_col2_label' => 'Contributed by',
            'qec_col3_label' => 'Venue',
            'qec_col4_label' => 'Date Held',
            'qec_col5_label' => null,
            'rows' => [
                ['External Reviewer for the two-day (July 08-09, 2025) Review of Institutional Performance and Effectiveness (RIPE)', 'Ms. Anum Yaseen, Deputy Director QEC, KASBIT', 'SIPMR', '8th-9th July 2025'],
                ['External Reviewer for Program Review for Effectiveness & Enhancement - PREE', 'Mr. Usama Iqbal Director QEC, KASBIT', 'MITE', '5th May 2025'],
                ['Conducted Workshop on PREE (Program Review for Effectiveness & Enhancement)', 'Mr. Usama Iqbal Director QEC, KASBIT', 'MITE', '30th Apr 2025'],
                ['How to Incorporate Sustainable Development Goals to Increase Education Quality', 'Mr. Usama Iqbal, Director QEC, KASBIT', 'SMIT', '16th January 2024'],
                ['International Conference on Business Management and Sustainability', 'Mr. Usama Iqbal, Director QEC, KASBIT', 'IoBM', '8th November 2023'],
                ['External Evaluator for IPE Conduction', 'Mr. Israr Ahmed, Director QEC, KASBIT', 'ILMA University', '14th June 2022'],
                ['How to Improve Quality Assurance in HEI', 'Mr. Israr Ahmed, Director QEC, KASBIT', 'CALWASS', '8th June 2022'],
                ['External Evaluator for MS PhD Review', 'Mr. Israr Ahmed, Director QEC, KASBIT', 'Sir Syed University', '24th May 2022'],
                ['External Evaluator for IPE Conduction', 'Mr. Israr Ahmed, Director QEC, KASBIT', 'FAST', '17th May 2022'],
                ['Quality Assurance in Higher Education', 'Mr. Israr Ahmed, Director QEC, KASBIT', 'Jinnah University for Women', '21st May 2022'],
                ['Panelist in Panel Discussion', 'Mr. Israr Ahmed, Director QEC, KASBIT', 'Dadabhoy', '5th May 2022'],
                ['External Evaluator for IPE Conduction', 'Mr. Israr Ahmed, Director QEC, KASBIT', 'Dadabhoy', '24th - 25th March 2022'],
                ['Session Chair at ILMA 3rd International Conference', 'Mr. Israr Ahmed, Director QEC, KASBIT', 'ILMA', '14th - 15th January 2022'],
                ['Paper Presentation at APQN', 'Mr. Israr Ahmed, Director QEC, KASBIT', 'APQN', '25th November 2021'],
                ['Self-Assessment Report: A Tool Towards Program Evaluation and Accreditation', 'Ms. Reema Zahid, Director QEC, KASBIT', 'Newport Institute Karachi', '11th August 2018'],
                ['Preparation of Self-Assessment Report', 'Ms. Reema Zahid, Director QEC, KASBIT', 'Hamdard University Karachi', '17th May 2018'],
                ['Quality is in the Eye of the Beholder: Relevance, Credibility and International Visibility', 'Ms. Reema Zahid, Director QEC, KASBIT', 'INQAAHE, Mauritius', '2nd & 4th May 2018'],
                ['Role of IMC in Textile Industry for Making Strong Branding: A Case Study of Orient Textile Mills Karachi', 'Mr. Abdullah Khan, Additional Director QEC, KASBIT', 'Sheikh Zayed Islamic Center on Socio-Economic Transformation in the Developed World: Challenges for Islamic Region, Karachi', '21st - 23rd December 2016'],
                ['2nd International Conference on Business & Management (ICBM)', 'Mr. Abdullah Khan, Additional Director QEC, KASBIT', 'Mohammad Ali Jinnah University Karachi', '16th - 18th December 2016'],
                ['ISO Training', 'Ms. Reema Zahid, Director QEC, KASBIT', 'Indus University, Karachi', '10th November 2016'],
                ['Interactive Session on ISO Certification', 'Ms. Reema Zahid, Director QEC, KASBIT', 'Hamdard University, Karachi', '3rd November 2016'],
                ['Sustainable Development Quality Assurance in Higher Education', 'Ms. Reema Zahid, Director QEC, KASBIT', 'FIJI, APQN', '25th May 2016'],
                ['Bridging the Gap Between QEC & Administrative Department', 'Ms. Reema Zahid, Director QEC, KASBIT', 'Institute of Business Technology (IBT)', '14th January 2016'],
                ['Second QEC SAP Awareness Workshop for Students', 'Ms. Reema Zahid, Director QEC, KASBIT', 'Benazir Bhutto Shaheed University', '12th November 2015'],
                ['Modern University Governance Training Program Under the Supervision of HEC', 'Ms. Reema Zahid, Director QEC, KASBIT', 'Benazir Bhutto Shaheed University', '27th - 29th October 2015'],
            ],
        ]);

        $this->replaceTable($page, [
            'title' => 'Conducted by QEC',
            'sort_order' => 3,
            'qec_col1_label' => 'Title of Seminar / Workshop',
            'qec_col2_label' => 'Conducted by',
            'qec_col3_label' => 'Venue',
            'qec_col4_label' => 'Date Held',
            'qec_col5_label' => 'Participants',
            'rows' => [
                ['Enhance Faculty & Student Survey Rate', 'QEC - KASBIT', 'KASBIT', '29th May, 2025', 'Students of KASBIT'],
                ['Training Session on RIPE Standards', 'Ms. Zareen Hussain, Additional Director, Ziauddin University', 'KASBIT SMCHS', '27th Feb, 2025', 'Staff Members, Faculty Members, Administrative Head of KASBIT'],
                ['PREE Training: Guidelines and Processes for Program Review, Effectiveness, and Enhancement', 'Ms. Anum Yaseen Deputy Director - IQAE-QEC, KASBIT', 'KASBIT SMCHS', '24th Jan, 2025', 'Program Team Members'],
                ['Sustainability Measures and Global Challenges for Improvement of Environmental Quality', 'Dr. Adnan Butt Lead Sustainability Consultant, Green Alpha Consultancy', 'KASBIT', '4th June, 2024', 'Chairman, Cluster Head, Faculty Members, Administrative Head'],
                ['Leveraging Finland\'s Education Success for Pakistan 21st Century Learning Skills', 'Dr. Ahmar Iqbal Consultant at Finland and Post-Doctoral Fellow', 'KASBIT', '30th March, 2024', 'Different University Dean, Faculty Members and KASBIT Faculty, Students'],
                ['Implication of New QA Framework: Challenges, Hurdles and Opportunities', 'Mr. Usama Iqbal Director QEC, KASBIT', 'KASBIT', '10th May, 2024', 'Chairman, Cluster Head, Faculty Members, Administrative Head'],
                ['Acquiring Accreditations: A Step Towards NBEAC and NCEAC', 'Mr. Usama Iqbal Director QEC, KASBIT', 'KASBIT', '30th December, 2023', 'Chairman, Cluster Head, Faculty Members, Administrative Head'],
                ['How to Prepare Self-Assessment Report', 'Mr. Israr Ahmed Former Director QEC, KASBIT', 'KASBIT', '18th November, 2022', 'Faculty Members'],
                ['Bloom Taxonomy, the Involvement of CLO\'s and PLO\'s in Preparing Assessments', 'Mr. Shahid Khan Assistant Professor, KASBIT', 'KASBIT', '15th July, 2022', 'Faculty Members, QEC Personnel, Coordinators'],
                ['Impact of Institutional Performance Evaluation (IPE) Standards to Enhance Quality Assurance', 'MR. Imran Ullah Khan Marwat Director, Quality Assurance, Govt. of Khyber Pakhtunkhwa Higher Education Department Peshawar', 'Online Training KASBIT S.M.C.H.S Building', '2nd February, 2021', 'Faculty Member, Staff and Different University Person, QEC'],
                ['Introduction of Self-Assessment Report (SAR) Standard', 'Dr. Munir Hussain Associate Professor Chairperson- Faculty of Management Sciences Barrett Hodgson University Karachi', 'Online Training KASBIT S.M.C.H.S Building', '18th November, 2020', 'Faculty Member, Staff and Different University Person, QEC Family'],
                ['IPE: Importance and Effectiveness', 'Ms. Reema Zahid Director QEC, KASBIT', 'KASBIT S.M.C.H.S Building', '4th June, 2019', 'Faculty, Staff & QEC Family'],
                ['Benefits of Adopting Learning Management System', 'Mr. Umair Ahmed Jalali Dy. Director QEC, KASBIT', 'KASBIT S.M.C.H.S Building', '3rd May, 2019', 'Faculty, Staff & QEC Family'],
                ['How to Develop Self-Assessment Report as per HEC Standards & Criterions', 'Ms. Sheema Haider Director QEC - INDUS UNIVERSITY', 'KASBIT S.M.C.H.S Building', '7th January, 2019', 'Faculty, Staff & QEC Family'],
                ['Quality Assurance in Teaching and Learning Process', 'Dr. Abdul Kabeer Kazi Associate Professor, KASBIT', 'KASBIT S.M.C.H.S Building', '2nd June, 2018', 'Faculty, Staff & QEC Family'],
                ['Importance of Institutional Performances Evaluation', 'Ms. Reema Zahid Director QEC, KASBIT', 'KASBIT S.M.C.H.S Building', '13th November, 2017', 'Faculty, Staff & QEC Family'],
                ['Awareness and Transition to ISO 9001:2015', 'Khalid Aslam Malik (URS) (Lahore)', '03 & 04 November, 2017', '22nd April, 2017', 'Faculty, Staff & QEC Family'],
                ['In Recognition of Her Active Participation in the Seminar Entitled ISO 9001:2015', 'Syed Ghazanfar Iqbal Lead Auditor ISO 9001:2015', 'KASBIT S.M.C.H.S Building', '31st October, 2017', 'Different University QEC Member, KASBIT Faculty, Staff & QEC Family'],
                ['Role of Program Team & Assessment Team', 'Ms. Syeda Nazneen Waseem Karachi University Business School', 'KASBIT S.M.C.H.S Building', '7th January, 2017', 'Faculty Members and QEC Family'],
                ['How to Develop Self-Assessment Report', 'Mr. Umair Ahmed Jalali Dy. Director QEC, KASBIT', 'KASBIT S.M.C.H.S Building', '25th October, 2016', 'Faculty Members'],
                ['First Aid Training by Pakistan Red Crescent Society', 'Saqib Ahmed Trainer PRC Sindh', 'KASBIT S.M.C.H.S Building', '22nd October, 2016', 'KASBIT Students, Faculty and Staff'],
                ['Developing Effective Self-Assessment Report', 'Ms. Reema Zahid, Director QEC, KASBIT', 'KASBIT S.M.C.H.S Building', '19th September, 2016', 'KASBIT Faculty and QEC Family'],
                ['Faculty Improvement Plan', 'Ms. Reema Zahid, Director QEC, KASBIT', 'KASBIT S.M.C.H.S Building', '1st September, 2016', 'Subject Group Heads'],
                ['Developing Effective Program Mission, Objectives and Learning Outcomes', 'Ms. Reema Zahid, Director QEC, KASBIT', 'KASBIT S.M.C.H.S Premises', '22nd February, 2016', 'PT members and faculty'],
                ['Integrating Departmental Effectiveness with QEC', 'Mr. Danish Hussain, Director QEC, BIZTEK', 'S.M.C.H.S Premises', '20th January, 2016', 'Administrative Employees'],
                ['How to Develop SAR', 'Mr. Umair Ahmed Jalali, Dy. Director QEC, KASBIT', 'S.M.C.H.S Premises', '23rd November, 2015', 'PT Members'],
                ['How to Develop SAR', 'Ms. Reema Zahid, Director QEC, KASBIT', 'S.M.C.H.S Premises', '14th October, 2015', 'PT Members'],
                ['Developing Effective Learning Outcomes', 'Mr. Moin Ali Khan, Dy. Director QEC, IoBM', 'S.M.C.H.S Premises', '17th September, 2015', 'KASBIT Faculty and QEC Family'],
                ['How to Develop Self-Assessment Report?', 'Ms. Reema Zahid, Director QEC, KASBIT', 'S.M.C.H.S Premises', '3rd February, 2015', 'Program Team Members'],
                ['Importance of Research for Quality Education', 'Dr. Abdul Kabeer Kazi, Registrar KASBIT & Mr. Karamatullah Hussainy, Dean KASBIT', 'S.M.C.H.S Premises', '27th January, 2015', 'Faculty Members'],
                ['How to Develop Self-Assessment Report', 'Ms. Reema Zahid, Director QEC, KASBIT', 'S.M.C.H.S Premises', '1st November, 2014', 'Program Team Members'],
                ['The Importance of Networking', 'Dr. Zakiuddin Ahmed, President OPEN Karachi', 'S.M.C.H.S Premises', '19th September, 2014', 'Students'],
                ['Training on How to Prepare Course Review Report', 'Ms. Reema Zahid & Umair Ahmed Jalali', 'S.M.C.H.S Premises', '06th September, 2014', 'Faculty Members'],
                ['Passion for Quality Teaching', 'Prof. Rubina Safdar, Educational Consultant and Trainer of the Trainers', 'S.M.C.H.S Premises', '21st August, 2014', 'Faculty Members'],
                ['Training Workshop on Customer Services and Personality Grooming', 'Mr. Umair Ahmed Jalali, Deputy Director QEC, KASBIT', 'S.M.C.H.S Premises', '19th July, 2014', 'Administrator Staff'],
                ['English Language Training Session', 'Ms. Komal Fatima & Ms. Rabia Sarwar - English Language Trainers', 'S.M.C.H.S Premises', '8th July, 2014', 'BBA Semester III Students'],
                ['English Language Training Session', 'Ms. Komal Fatima & Ms. Rabia Sarwar - English Language Trainers', 'S.M.C.H.S Premises', '7th July, 2014', 'BBA Semester II Students'],
                ['Training Session: SPSS', 'Ms. Anila Parveen, Member Research Associate', 'Hyderi Premises', '8th May, 2014', 'Faculty members'],
                ['How to Encourage Classroom Teaching?', 'Dr. Rahat Alam, Director QEC, IBT', 'S.M.C.H.S Premises', '19th April, 2014', 'Faculty Members'],
                ['SAR: A step towards Quality Education', 'Ms. Ambreen Asif, Vice President, S.M.C.H.S Premises', '14th March 2014', 'Faculty Members', 'Partner, IntellAct Consultants'],
                ['How to write SAR?', 'Mr. Umair Ahmed, Deputy Director, QEC, KASBIT', 'S.M.C.H.S Premises', '11th February, 2014', 'Program Teams'],
                ['QEC: A step towards Quality Education', 'Ms. Reema Zahid, Additional Director, QEC, KASBIT', 'Hyderi Premises', '19th November, 2013', 'Faculty Members, Dean, Director'],
                ['QEC: A step towards Quality Education', 'Ms. Reema Zahid, Additional Director, QEC, KASBIT', 'S.M.C.H.S Premises', '18th November, 2013', 'Faculty Members, Dean, Director'],
            ],
        ]);
    }

    private function replaceTable(HeaderMenuPage $page, array $data): void
    {
        DB::transaction(function () use ($page, $data) {
            $table = $page->programSchemaTables()->updateOrCreate(
                ['title' => $data['title']],
                [
                    'sort_order' => $data['sort_order'],
                    'is_active' => true,
                    'qec_serial_label' => 'S. No',
                    'qec_col1_label' => $data['qec_col1_label'],
                    'qec_col2_label' => $data['qec_col2_label'],
                    'qec_col3_label' => $data['qec_col3_label'],
                    'qec_col4_label' => $data['qec_col4_label'],
                    'qec_show_col4' => true,
                    'qec_col5_label' => $data['qec_col5_label'] ?? null,
                    'qec_show_col5' => filled($data['qec_col5_label'] ?? null),
                ]
            );

            $table->rows()->delete();

            foreach ($data['rows'] as $index => $row) {
                $table->rows()->create([
                    'semester' => null,
                    'subject' => $row[0],
                    'credit_hours' => $row[1],
                    'col3_text' => $row[2],
                    'col4_text' => $row[3],
                    'col5_text' => $row[4] ?? null,
                    'is_total' => false,
                    'sort_order' => $index,
                ]);
            }
        });
    }
}
