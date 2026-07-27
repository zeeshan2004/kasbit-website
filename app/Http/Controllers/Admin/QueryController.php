<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFeedbackQueryRequest;
use App\Models\Department;
use App\Models\Query;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QueryController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'query_code' => ['nullable', 'string', 'max:100'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'department_id' => ['nullable', Rule::exists('departments', 'id')],
            'status' => ['nullable', Rule::in(Query::STATUSES)],
            'submitted_date' => ['nullable', 'date'],
            'per_page' => ['nullable', Rule::in([10, 25, 50])],
        ]);

        $queries = Query::with(['department:id,name', 'user:id,student_id,semester'])
            ->when($request->filled('query_code'), fn ($query) => $query
                ->where('query_code', 'like', '%'.trim((string) $request->input('query_code')).'%'))
            ->when($request->filled('name'), fn ($query) => $query
                ->where('name', 'like', '%'.trim((string) $request->input('name')).'%'))
            ->when($request->filled('email'), fn ($query) => $query
                ->where('email', 'like', '%'.trim((string) $request->input('email')).'%'))
            ->when($request->filled('department_id'), fn ($query) => $query
                ->where('department_id', $request->integer('department_id')))
            ->when($request->filled('status'), fn ($query) => $query
                ->where('status', $request->input('status')))
            ->when($request->filled('submitted_date'), fn ($query) => $query
                ->whereDate('created_at', $request->input('submitted_date')))
            ->latest()
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('admin.queries.index', [
            'queries' => $queries,
            'departments' => Department::orderBy('sort_order')->orderBy('name')->get(),
            'statuses' => Query::STATUSES,
            'counts' => [
                'total' => Query::count(),
                'pending' => Query::where('status', 'pending')->count(),
                'in_progress' => Query::where('status', 'in_progress')->count(),
                'resolved' => Query::where('status', 'resolved')->count(),
            ],
        ]);
    }

    public function show(Query $query): View
    {
        return view('admin.queries.show', [
            'query' => $query->load(['department:id,name', 'user:id,name,email,student_id,semester']),
            'statuses' => Query::STATUSES,
        ]);
    }

    public function update(
        UpdateFeedbackQueryRequest $request,
        Query $query
    ): RedirectResponse {
        $status = $request->string('status')->toString();

        $query->update([
            'status' => $status,
            'admin_notes' => $request->string('admin_notes')->trim()->toString() ?: null,
            'resolved_at' => $status === 'resolved'
                ? ($query->resolved_at ?? now())
                : null,
        ]);

        return back()->with('success', 'Query status and notes updated.');
    }

    public function destroy(Query $query): RedirectResponse
    {
        $query->delete();

        return redirect()
            ->route('admin.queries.index')
            ->with('success', 'Query deleted successfully.');
    }

    private function perPage(Request $request): int
    {
        $perPage = $request->integer('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
    }
}
