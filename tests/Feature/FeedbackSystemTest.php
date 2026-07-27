<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\HeaderMenu;
use App\Models\Query;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FeedbackSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_feedback_request_redirects_to_student_login(): void
    {
        $this->get(route('feedback.index'))
            ->assertRedirect(route('student.login'));
    }

    public function test_student_can_register_with_kasbit_email_and_academic_details(): void
    {
        $program = $this->registrationProgram();

        $response = $this->post(route('student.register.store'), [
            'name' => 'Ayesha Khan',
            'email' => 'ayesha.khan@kasbit.edu.pk',
            'student_id' => 'KASBIT-24001',
            'program_id' => $program->id,
            'semester' => 'Semester 4',
            'password' => 'securepass123',
            'password_confirmation' => 'securepass123',
        ]);

        $student = User::where('email', 'ayesha.khan@kasbit.edu.pk')->firstOrFail();

        $response->assertRedirect(route('feedback.index'));
        $this->assertAuthenticatedAs($student, 'student');
        $this->assertSame('student', $student->role);
        $this->assertSame('KASBIT-24001', $student->student_id);
        $this->assertSame($program->id, $student->program_id);
    }

    public function test_registration_rejects_non_kasbit_email(): void
    {
        $program = $this->registrationProgram();

        $this->post(route('student.register.store'), [
            'name' => 'Ayesha Khan',
            'email' => 'ayesha@gmail.com',
            'student_id' => 'KASBIT-24001',
            'program_id' => $program->id,
            'semester' => 'Semester 4',
            'password' => 'securepass123',
            'password_confirmation' => 'securepass123',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users', ['email' => 'ayesha@gmail.com']);
    }

    public function test_student_can_submit_query_with_unique_generated_code(): void
    {
        $department = Department::active()->firstOrFail();
        $student = User::factory()->student()->create([
            'department_id' => $department->id,
        ]);

        $response = $this->actingAs($student, 'student')->post(route('feedback.store'), [
            'department_id' => $department->id,
            'message' => 'Please confirm the deadline for my semester fee submission.',
        ]);

        $query = Query::firstOrFail();

        $response->assertRedirect(route('feedback.index'));
        $response->assertSessionHas('submitted_query_code', $query->query_code);
        $this->assertMatchesRegularExpression('/^KASBIT-QRY-\d{5,}$/', $query->query_code);
        $this->assertSame($student->name, $query->name);
        $this->assertSame($student->email, $query->email);
        $this->assertSame('pending', $query->status);
    }

    public function test_student_cannot_access_admin_routes(): void
    {
        $student = User::factory()->student()->create();

        $this->actingAs($student)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_session_does_not_block_student_forms_or_open_feedback(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->get(route('student.login'))
            ->assertOk();

        $this->get(route('student.register'))
            ->assertOk();

        $this->get(route('feedback.index'))
            ->assertRedirect(route('student.login'));
    }

    public function test_admin_and_student_sessions_can_coexist(): void
    {
        $admin = User::factory()->create();
        $student = User::factory()->student()->create();

        $this->actingAs($admin);
        Auth::guard('student')->login($student);

        $this->get(route('admin.dashboard'))
            ->assertOk();

        $this->get(route('feedback.index'))
            ->assertOk()
            ->assertSee($student->email);
    }

    public function test_admin_can_resolve_query_and_add_internal_note(): void
    {
        $admin = User::factory()->create();
        $department = Department::active()->firstOrFail();
        $student = User::factory()->student()->create(['department_id' => $department->id]);
        $query = Query::create([
            'query_code' => 'KASBIT-QRY-00001',
            'user_id' => $student->id,
            'name' => $student->name,
            'email' => $student->email,
            'department_id' => $department->id,
            'message' => 'Please update me about the requested student document.',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.queries.update', $query), [
                'status' => 'resolved',
                'admin_notes' => 'Verified with Student Affairs.',
            ])
            ->assertRedirect();

        $query->refresh();
        $this->assertSame('resolved', $query->status);
        $this->assertSame('Verified with Student Affairs.', $query->admin_notes);
        $this->assertNotNull($query->resolved_at);

        Auth::guard('student')->login($student);

        $this->get(route('feedback.index'))
            ->assertOk()
            ->assertSee('Admin Response')
            ->assertSee('Verified with Student Affairs.');
    }

    public function test_department_with_queries_cannot_be_deleted(): void
    {
        $admin = User::factory()->create();
        $department = Department::active()->firstOrFail();
        $student = User::factory()->student()->create(['department_id' => $department->id]);
        Query::create([
            'query_code' => 'KASBIT-QRY-00001',
            'user_id' => $student->id,
            'name' => $student->name,
            'email' => $student->email,
            'department_id' => $department->id,
            'message' => 'This query keeps the department from being removed.',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.departments.destroy', $department))
            ->assertSessionHasErrors('department');

        $this->assertDatabaseHas('departments', ['id' => $department->id]);
    }

    public function test_admin_can_update_student_details_and_password(): void
    {
        $admin = User::factory()->create();
        $department = Department::active()->firstOrFail();
        $program = $this->registrationProgram();
        $student = User::factory()->student()->create(['department_id' => $department->id]);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $student), [
                'name' => 'Updated Student',
                'email' => 'updated.student@kasbit.edu.pk',
                'student_id' => 'KASBIT-99999',
                'program_id' => $program->id,
                'semester' => 'Semester 8',
                'is_active' => '1',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ])
            ->assertRedirect(route('admin.users.index'));

        $student->refresh();
        $this->assertSame('Updated Student', $student->name);
        $this->assertSame('KASBIT-99999', $student->student_id);
        $this->assertSame($program->id, $student->program_id);
        $this->assertTrue(Hash::check('newpassword123', $student->password));
    }

    public function test_registration_programs_follow_active_program_menu_courses(): void
    {
        $programGroup = HeaderMenu::registrationProgramGroups()->firstOrFail();
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.programs.index'))
            ->assertOk()
            ->assertSee('Add Course')
            ->assertDontSee('Loader Settings');

        $this->post(route('admin.programs.store'), [
                'parent_id' => $programGroup->id,
                'name' => 'BS Test Program',
                'sort_order' => 999,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.programs.index'));

        $newCourse = HeaderMenu::where([
            'parent_id' => $programGroup->id,
            'name' => 'BS Test Program',
        ])->firstOrFail();

        $this->assertSame('registration', $newCourse->management_context);

        $this->get(route('student.register'))
            ->assertOk()
            ->assertSee('BS Test Program');

        $this->get('/')
            ->assertOk()
            ->assertDontSee('BS Test Program');

        $headerOnlyCourse = HeaderMenu::create([
            'parent_id' => $programGroup->id,
            'name' => 'Header Only Program',
            'link' => '/pages/header-only-program',
            'icon' => 'fa-solid fa-graduation-cap',
            'show_in_admin_sidebar' => false,
            'management_context' => 'header',
            'sort_order' => 1000,
            'is_active' => true,
        ]);

        $this->get(route('student.register'))
            ->assertOk()
            ->assertSee('Header Only Program')
            ->assertDontSee('<option value="'.$headerOnlyCourse->id.'"', false);

        $this->patch(route('admin.programs.toggle', $newCourse))
            ->assertRedirect();

        $this->get(route('student.register'))
            ->assertOk()
            ->assertDontSee('BS Test Program');
    }

    public function test_student_logout_returns_to_home_page(): void
    {
        $student = User::factory()->student()->create();

        $this->actingAs($student, 'student')
            ->post(route('student.logout'))
            ->assertRedirect('/');

        $this->assertGuest('student');
    }

    private function registrationProgram(): HeaderMenu
    {
        return HeaderMenu::registrationProgramGroups()
            ->firstOrFail()
            ->children
            ->firstOrFail();
    }
}
