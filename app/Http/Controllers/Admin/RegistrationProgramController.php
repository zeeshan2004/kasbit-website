<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderMenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegistrationProgramController extends Controller
{
    public function index(): View
    {
        $programGroups = HeaderMenu::registrationProgramGroups(true);
        $programGroups->each(fn (HeaderMenu $group) => $group->children->loadCount('students'));

        return view('admin.programs.index', compact('programGroups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $program = HeaderMenu::create([
            ...$data,
            'parent_id' => $request->integer('parent_id'),
            'link' => null,
            'icon' => 'fa-solid fa-graduation-cap',
            'show_in_admin_sidebar' => false,
            'management_context' => 'registration',
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Course added to the registration form.');
    }

    public function edit(HeaderMenu $program): View
    {
        $this->ensureRegistrationProgram($program);

        return view('admin.programs.edit', [
            'program' => $program,
            'programGroups' => HeaderMenu::registrationProgramGroups(true),
        ]);
    }

    public function update(Request $request, HeaderMenu $program): RedirectResponse
    {
        $this->ensureRegistrationProgram($program);
        $data = $this->validatedData($request, $program);

        $program->update([
            ...$data,
            'parent_id' => $request->integer('parent_id'),
            'is_active' => $request->boolean('is_active'),
        ]);

        $program->page?->update([
            'title' => $program->name,
            'eyebrow' => 'Programs',
        ]);

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Course updated successfully.');
    }

    public function toggle(HeaderMenu $program): RedirectResponse
    {
        $this->ensureRegistrationProgram($program);
        $program->update(['is_active' => ! $program->is_active]);

        return back()->with('success', 'Course status updated.');
    }

    public function destroy(HeaderMenu $program): RedirectResponse
    {
        $this->ensureRegistrationProgram($program);

        if ($program->students()->exists()) {
            return back()->withErrors([
                'program' => 'This course has registered students. Deactivate it instead.',
            ]);
        }

        $program->delete();

        return back()->with('success', 'Course deleted successfully.');
    }

    private function validatedData(Request $request, ?HeaderMenu $program = null): array
    {
        $programGroupIds = HeaderMenu::registrationProgramGroups(true)->pluck('id')->all();

        return $request->validate([
            'parent_id' => ['required', Rule::in($programGroupIds)],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('header_menus', 'name')
                    ->where(fn ($query) => $query->where('parent_id', $request->integer('parent_id')))
                    ->ignore($program?->id),
            ],
            'sort_order' => ['required', 'integer', 'min:0', 'max:99999'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function ensureRegistrationProgram(HeaderMenu $program): void
    {
        abort_unless(
            in_array($program->id, HeaderMenu::registrationProgramIds(true), true),
            404
        );
    }

}
