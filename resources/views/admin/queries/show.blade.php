<x-admin-layout>
    <x-slot name="title">{{ $query->query_code }} - KASBIT Control</x-slot>
    <x-slot name="header">Query Details</x-slot>

    <div class="management-page">
        @if(session('success'))
            <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="admin-alert admin-alert--error">{{ $errors->first() }}</div>
        @endif

        <div class="management-heading">
            <div>
                <h3>{{ $query->query_code }}</h3>
                <p>Complete submission, student information and internal resolution notes.</p>
            </div>
            <a href="{{ route('admin.queries.index') }}" class="admin-button admin-button--secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                Back
            </a>
        </div>

        <div class="query-detail-grid">
            <section class="management-panel">
                <div class="management-panel-header">
                    <h4>Submission</h4>
                    <span class="admin-status admin-status--{{ $query->status }}">{{ $query->status_label }}</span>
                </div>

                <div class="query-meta-grid">
                    <div class="query-meta">
                        <span>Full Name</span>
                        <strong>{{ $query->name }}</strong>
                    </div>
                    <div class="query-meta">
                        <span>Email Address</span>
                        <strong>{{ $query->email }}</strong>
                    </div>
                    <div class="query-meta">
                        <span>Student ID</span>
                        <strong>{{ $query->user?->student_id ?: 'Deleted user' }}</strong>
                    </div>
                    <div class="query-meta">
                        <span>Semester</span>
                        <strong>{{ $query->user?->semester ?: '-' }}</strong>
                    </div>
                    <div class="query-meta">
                        <span>Department</span>
                        <strong>{{ $query->department->name }}</strong>
                    </div>
                    <div class="query-meta">
                        <span>Submitted</span>
                        <strong>{{ $query->created_at->format('d M Y, h:i A') }}</strong>
                    </div>
                    <div class="query-meta">
                        <span>Last Updated</span>
                        <strong>{{ $query->updated_at->format('d M Y, h:i A') }}</strong>
                    </div>
                    <div class="query-meta">
                        <span>Resolved At</span>
                        <strong>{{ $query->resolved_at?->format('d M Y, h:i A') ?: '-' }}</strong>
                    </div>
                </div>

                <h4>Complete Query Message</h4>
                <p class="query-message-full">{{ $query->message }}</p>
            </section>

            <section class="management-panel">
                <div class="management-panel-header">
                    <h4>Admin Review</h4>
                </div>

                <form method="POST" action="{{ route('admin.queries.update', $query) }}">
                    @csrf
                    @method('PUT')
                    <div class="admin-form-grid">
                        <div class="admin-form-field admin-form-field--full">
                            <label for="query-status">Status</label>
                            <select id="query-status" name="status" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" @selected(old('status', $query->status) === $status)>
                                        {{ $status === 'in_progress' ? 'In Progress' : str($status)->headline() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="admin-form-field admin-form-field--full">
                            <label for="query-admin-notes">Admin Response</label>
                            <textarea id="query-admin-notes"
                                      name="admin_notes"
                                      maxlength="5000"
                                      placeholder="This response will be visible to the student.">{{ old('admin_notes', $query->admin_notes) }}</textarea>
                        </div>
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" class="admin-button admin-button--primary">
                            <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                            Update Query
                        </button>
                    </div>
                </form>

                <form method="POST"
                      action="{{ route('admin.queries.destroy', $query) }}"
                      data-confirm-message="Delete {{ $query->query_code }} permanently?"
                      class="admin-form-actions">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-button admin-button--danger">
                        <i class="fa-solid fa-trash" aria-hidden="true"></i>
                        Delete Query
                    </button>
                </form>
            </section>
        </div>
    </div>
</x-admin-layout>
