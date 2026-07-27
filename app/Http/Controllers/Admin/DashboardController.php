<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Query;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $queryCounts = [
            'total' => Query::count(),
            'pending' => Query::where('status', 'pending')->count(),
            'in_progress' => Query::where('status', 'in_progress')->count(),
            'resolved' => Query::where('status', 'resolved')->count(),
        ];

        return view('admin.dashboard', [
            'queryCounts' => $queryCounts,
            'totalStudents' => User::where('role', 'student')->count(),
            'recentQueries' => Query::with('department:id,name')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
