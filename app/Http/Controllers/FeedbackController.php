<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackQueryRequest;
use App\Models\Department;
use App\Models\HomePage;
use App\Models\Query;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(): View
    {
        $student = Auth::guard('student')->user();

        return view('frontend.feedback.index', [
            'home' => HomePage::first() ?? new HomePage(),
            'departments' => Department::active()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'student' => $student,
            'studentQueries' => $student
                ->queries()
                ->with('department:id,name')
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }

    public function store(StoreFeedbackQueryRequest $request): RedirectResponse
    {
        $user = Auth::guard('student')->user();

        $query = DB::transaction(function () use ($request, $user) {
            $query = Query::create([
                'query_code' => 'PENDING-'.Str::uuid(),
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'department_id' => $request->integer('department_id'),
                'message' => $request->string('message')->trim()->toString(),
                'status' => 'pending',
            ]);

            $query->update([
                'query_code' => 'KASBIT-QRY-'.str_pad((string) $query->id, 5, '0', STR_PAD_LEFT),
            ]);

            return $query;
        });

        return redirect()
            ->route('feedback.index')
            ->with('submitted_query_code', $query->query_code)
            ->with('success', 'Your query has been submitted successfully.');
    }
}
