<x-admin-layout>
    <x-slot name="title">Dashboard - KASBIT Control</x-slot>
    <x-slot name="header">Dashboard</x-slot>

    <div class="management-page">
        <div class="management-heading">
            <div>
                <h3>Feedback Overview</h3>
                <p>Live student accounts and query activity across all departments.</p>
            </div>
            <a href="{{ route('admin.queries.index') }}" class="admin-button admin-button--primary">
                <i class="fa-solid fa-comments" aria-hidden="true"></i>
                View Queries
            </a>
        </div>

        <div class="management-stats">
            <a href="{{ route('admin.queries.index') }}" class="management-stat management-stat--total">
                <span>Total Queries</span>
                <strong>{{ $queryCounts['total'] }}</strong>
                <i class="fa-solid fa-inbox" aria-hidden="true"></i>
            </a>
            <a href="{{ route('admin.queries.index', ['status' => 'pending']) }}" class="management-stat management-stat--pending">
                <span>Pending</span>
                <strong>{{ $queryCounts['pending'] }}</strong>
                <i class="fa-solid fa-clock" aria-hidden="true"></i>
            </a>
            <a href="{{ route('admin.queries.index', ['status' => 'in_progress']) }}" class="management-stat management-stat--progress">
                <span>In Progress</span>
                <strong>{{ $queryCounts['in_progress'] }}</strong>
                <i class="fa-solid fa-spinner" aria-hidden="true"></i>
            </a>
            <a href="{{ route('admin.queries.index', ['status' => 'resolved']) }}" class="management-stat management-stat--resolved">
                <span>Resolved</span>
                <strong>{{ $queryCounts['resolved'] }}</strong>
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
            </a>
        </div>

        <section class="management-panel">
            <div class="management-panel-header">
                <h4>Recent Queries</h4>
                <span class="admin-status admin-status--student">{{ $totalStudents }} Students</span>
            </div>

            <div class="admin-table-wrap">
                <table class="admin-data-table">
                    <thead>
                        <tr>
                            <th>Query ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentQueries as $query)
                            <tr>
                                <td><span class="admin-table-primary">{{ $query->query_code }}</span></td>
                                <td>{{ $query->name }}</td>
                                <td>{{ $query->department->name }}</td>
                                <td>
                                    <span class="admin-status admin-status--{{ $query->status }}">
                                        {{ $query->status_label }}
                                    </span>
                                </td>
                                <td>{{ $query->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.queries.show', $query) }}" class="admin-button admin-button--secondary">
                                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="admin-empty-state">No feedback queries have been submitted.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-admin-layout>
