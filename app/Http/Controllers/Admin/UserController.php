<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateManagedUserRequest;
use App\Models\HeaderMenu;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'program_id' => ['nullable', Rule::in(HeaderMenu::registrationProgramIds(true))],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'per_page' => ['nullable', Rule::in([10, 25, 50])],
        ]);

        $users = User::with('program:id,parent_id,name')
            ->withCount('queries')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->input('search'));
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('student_id', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('program_id'), fn ($query) => $query
                ->where('program_id', $request->integer('program_id')))
            ->when($request->filled('status'), fn ($query) => $query
                ->where('is_active', $request->input('status') === 'active'))
            ->latest()
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'programGroups' => HeaderMenu::registrationProgramGroups(true),
            'studentCount' => User::where('role', 'student')->count(),
            'activeStudentCount' => User::where('role', 'student')->where('is_active', true)->count(),
        ]);
    }

    public function edit(User $managedUser): View
    {
        return view('admin.users.edit', [
            'managedUser' => $managedUser->load('program'),
            'programGroups' => HeaderMenu::registrationProgramGroups(true),
        ]);
    }

    public function update(
        UpdateManagedUserRequest $request,
        User $managedUser
    ): RedirectResponse {
        if ($request->user()->is($managedUser) && ! $request->boolean('is_active')) {
            return back()->withErrors(['is_active' => 'You cannot deactivate your own admin account.']);
        }

        $data = $request->safe()->except(['password_confirmation']);
        $data['is_active'] = $request->boolean('is_active');

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $managedUser->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User details updated successfully.');
    }

    public function destroy(Request $request, User $managedUser): RedirectResponse
    {
        if ($request->user()->is($managedUser)) {
            return back()->withErrors(['user' => 'You cannot delete your own admin account.']);
        }

        $managedUser->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    private function perPage(Request $request): int
    {
        $perPage = $request->integer('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
    }
}
