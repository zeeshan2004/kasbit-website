<x-admin-layout>
    <x-slot name="title">All Users - KASBIT Control</x-slot>
    <x-slot name="header">All Users</x-slot>

    <div class="management-page">
        @if(session('success'))
            <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="admin-alert admin-alert--error">{{ $errors->first() }}</div>
        @endif

        <div class="management-heading">
            <div>
                <h3>User Management</h3>
                <p>Registered student details, account access and submitted query totals.</p>
            </div>
        </div>

        <div class="management-stats">
            <div class="management-stat management-stat--users">
                <span>Registered Students</span>
                <strong>{{ $studentCount }}</strong>
                <i class="fa-solid fa-user-graduate" aria-hidden="true"></i>
            </div>
            <div class="management-stat management-stat--resolved">
                <span>Active Students</span>
                <strong>{{ $activeStudentCount }}</strong>
                <i class="fa-solid fa-user-check" aria-hidden="true"></i>
            </div>
        </div>

        <section class="management-panel">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="admin-form-grid admin-form-grid--filters">
                    <div class="admin-form-field">
                        <label for="user-search">Name, Email or Student ID</label>
                        <input id="user-search" type="search" name="search" value="{{ request('search') }}">
                    </div>
                    <div class="admin-form-field">
                        <label for="user-program">Program / Course</label>
                        <select id="user-program" name="program_id">
                            <option value="">All programs</option>
                            @foreach($programGroups as $programGroup)
                                <optgroup label="{{ $programGroup->name }}">
                                    @foreach($programGroup->children as $program)
                                        <option value="{{ $program->id }}" @selected((int) request('program_id') === $program->id)>
                                            {{ $program->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="admin-form-field">
                        <label for="user-status">Account Status</label>
                        <select id="user-status" name="status">
                            <option value="">All statuses</option>
                            <option value="active" @selected(request('status') === 'active')>Active</option>
                            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                        </select>
                    </div>
                    <div class="admin-form-field">
                        <label for="user-per-page">Rows</label>
                        <select id="user-per-page" name="per_page">
                            @foreach([10, 25, 50] as $size)
                                <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="admin-form-actions">
                    <button type="submit" class="admin-button admin-button--primary">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        Search
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="admin-button admin-button--secondary">
                        <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                        Reset
                    </a>
                </div>
            </form>
        </section>

        <section class="management-panel">
            <div class="management-panel-header">
                <h4>Users</h4>
                <span class="admin-status admin-status--student">{{ $users->total() }} Records</span>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-data-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Student ID</th>
                            <th>Program / Course</th>
                            <th>Semester</th>
                            <th>Role</th>
                            <th>Queries</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $managedUser)
                            <tr>
                                <td>
                                    <strong>{{ $managedUser->name }}</strong>
                                    <span class="admin-table-secondary">{{ $managedUser->email }}</span>
                                </td>
                                <td>{{ $managedUser->student_id ?: '-' }}</td>
                                <td>{{ $managedUser->program?->name ?: '-' }}</td>
                                <td>{{ $managedUser->semester ?: '-' }}</td>
                                <td>
                                    <span class="admin-status admin-status--{{ $managedUser->role }}">
                                        {{ str($managedUser->role)->headline() }}
                                    </span>
                                </td>
                                <td>{{ $managedUser->queries_count }}</td>
                                <td>
                                    <span class="admin-status admin-status--{{ $managedUser->is_active ? 'active' : 'inactive' }}">
                                        {{ $managedUser->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $managedUser->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="admin-row-actions">
                                        <a href="{{ route('admin.users.edit', $managedUser) }}"
                                           class="admin-button admin-button--secondary">
                                            <i class="fa-solid fa-user-pen" aria-hidden="true"></i>
                                            Edit
                                        </a>
                                        @unless(auth()->user()->is($managedUser))
                                            <form method="POST"
                                                  action="{{ route('admin.users.destroy', $managedUser) }}"
                                                  data-confirm-message="Delete this user? Their submitted queries will be retained.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="admin-button admin-button--danger admin-button--icon" title="Delete user">
                                                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="admin-empty-state">No users match the selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('admin.partials.pagination', ['paginator' => $users])
        </section>
    </div>
</x-admin-layout>
