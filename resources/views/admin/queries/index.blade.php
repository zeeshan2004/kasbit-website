<x-admin-layout>
    <x-slot name="title">Queries - KASBIT Control</x-slot>
    <x-slot name="header">Query Management</x-slot>

    <div class="management-page">
        @if(session('success'))
            <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
        @endif

        <div class="management-heading">
            <div>
                <h3>Feedback Queries</h3>
                <p>Search, review and resolve student feedback routed to KASBIT departments.</p>
            </div>
        </div>

        <div class="management-stats">
            <a href="{{ route('admin.queries.index') }}" class="management-stat management-stat--total">
                <span>Total Queries</span>
                <strong>{{ $counts['total'] }}</strong>
                <i class="fa-solid fa-inbox" aria-hidden="true"></i>
            </a>
            <a href="{{ route('admin.queries.index', ['status' => 'pending']) }}" class="management-stat management-stat--pending">
                <span>Pending</span>
                <strong>{{ $counts['pending'] }}</strong>
                <i class="fa-solid fa-clock" aria-hidden="true"></i>
            </a>
            <a href="{{ route('admin.queries.index', ['status' => 'in_progress']) }}" class="management-stat management-stat--progress">
                <span>In Progress</span>
                <strong>{{ $counts['in_progress'] }}</strong>
                <i class="fa-solid fa-spinner" aria-hidden="true"></i>
            </a>
            <a href="{{ route('admin.queries.index', ['status' => 'resolved']) }}" class="management-stat management-stat--resolved">
                <span>Resolved</span>
                <strong>{{ $counts['resolved'] }}</strong>
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
            </a>
        </div>

        <section class="management-panel">
            <form method="GET" action="{{ route('admin.queries.index') }}">
                <div class="admin-form-grid admin-form-grid--filters">
                    <div class="admin-form-field">
                        <label for="filter-query-code">Query ID</label>
                        <input id="filter-query-code" type="search" name="query_code" value="{{ request('query_code') }}">
                    </div>
                    <div class="admin-form-field">
                        <label for="filter-name">Name</label>
                        <input id="filter-name" type="search" name="name" value="{{ request('name') }}">
                    </div>
                    <div class="admin-form-field">
                        <label for="filter-email">Email</label>
                        <input id="filter-email" type="search" name="email" value="{{ request('email') }}">
                    </div>
                    <div class="admin-form-field">
                        <label for="filter-department">Department</label>
                        <select id="filter-department" name="department_id">
                            <option value="">All departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @selected((int) request('department_id') === $department->id)>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="admin-form-field">
                        <label for="filter-status">Status</label>
                        <select id="filter-status" name="status">
                            <option value="">All statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>
                                    {{ $status === 'in_progress' ? 'In Progress' : str($status)->headline() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="admin-form-field">
                        <label for="filter-date">Submitted Date</label>
                        <input id="filter-date" type="date" name="submitted_date" value="{{ request('submitted_date') }}">
                    </div>
                    <div class="admin-form-field">
                        <label for="query-per-page">Rows</label>
                        <select id="query-per-page" name="per_page">
                            @foreach([10, 25, 50] as $size)
                                <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="admin-form-actions">
                    <button type="submit" class="admin-button admin-button--primary">
                        <i class="fa-solid fa-filter" aria-hidden="true"></i>
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.queries.index') }}" class="admin-button admin-button--secondary">
                        <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                        Reset
                    </a>
                </div>
            </form>
        </section>

        <section class="management-panel">
            <div class="management-panel-header">
                <h4>Query Records</h4>
                <span class="admin-status admin-status--student">{{ $queries->total() }} Results</span>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-data-table">
                    <thead>
                        <tr>
                            <th>Query ID</th>
                            <th>Name / Email</th>
                            <th>Department</th>
                            <th>Query Details</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($queries as $query)
                            <tr>
                                <td><span class="admin-table-primary">{{ $query->query_code }}</span></td>
                                <td>
                                    <strong>{{ $query->name }}</strong>
                                    <span class="admin-table-secondary">{{ $query->email }}</span>
                                </td>
                                <td>{{ $query->department->name }}</td>
                                <td><span class="admin-table-message">{{ str($query->message)->limit(78) }}</span></td>
                                <td>
                                    <span class="admin-status admin-status--{{ $query->status }}">
                                        {{ $query->status_label }}
                                    </span>
                                </td>
                                <td>
                                    {{ $query->created_at->format('d M Y') }}
                                    <span class="admin-table-secondary">{{ $query->created_at->format('h:i A') }}</span>
                                </td>
                                <td>
                                    <div class="admin-row-actions">
                                        <a href="{{ route('admin.queries.show', $query) }}" class="admin-button admin-button--secondary">
                                            <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                            View
                                        </a>
                                        <form method="POST"
                                              action="{{ route('admin.queries.destroy', $query) }}"
                                              data-confirm-message="Delete {{ $query->query_code }} permanently?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-button admin-button--danger admin-button--icon" title="Delete query">
                                                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="admin-empty-state">No queries match the selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('admin.partials.pagination', ['paginator' => $queries])
        </section>
    </div>
</x-admin-layout>
