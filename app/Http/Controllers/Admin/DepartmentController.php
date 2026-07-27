<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        return view('admin.departments.index', [
            'departments' => Department::withCount('queries')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(DepartmentRequest $request): RedirectResponse
    {
        Department::create([
            ...$request->validated(),
            'slug' => $this->uniqueSlug($request->string('name')->toString()),
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Department added successfully.');
    }

    public function edit(Department $department): View
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(DepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update([
            ...$request->validated(),
            'slug' => $this->uniqueSlug($request->string('name')->toString(), $department),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function toggle(Department $department): RedirectResponse
    {
        $department->update(['is_active' => ! $department->is_active]);

        return back()->with('success', 'Department status updated.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->queries()->exists() || $department->users()->exists()) {
            return back()->withErrors([
                'department' => 'This department is in use and cannot be deleted. Deactivate it instead.',
            ]);
        }

        $department->delete();

        return back()->with('success', 'Department deleted successfully.');
    }

    private function uniqueSlug(string $name, ?Department $except = null): string
    {
        $base = Str::slug($name) ?: 'department';
        $slug = $base;
        $suffix = 2;

        while (Department::where('slug', $slug)
            ->when($except, fn ($query) => $query->whereKeyNot($except->getKey()))
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
