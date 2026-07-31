<x-admin-layout>
    <x-slot name="title">Feedback Departments - KASBIT Control</x-slot>
    <x-slot name="header">Feedback Department Management</x-slot>

    <div class="management-page">
        @if(session('success'))
            <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="admin-alert admin-alert--error">{{ $errors->first() }}</div>
        @endif

        <div class="management-heading">
            <div>
                <h3>Feedback Departments</h3>
                <p>Controls query routing choices on the student feedback form.</p>
            </div>
        </div>

        <div class="management-split">
            <section class="management-panel">
                <div class="management-panel-header">
                    <h4>Add Department</h4>
                </div>
                <form method="POST" action="{{ route('admin.departments.store') }}">
                    @csrf
                    <div class="admin-form-grid">
                        <div class="admin-form-field admin-form-field--full">
                            <label for="department-name">Department Name</label>
                            <input id="department-name" type="text" name="name" value="{{ old('name') }}" required>
                            @error('name')<p class="admin-field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="admin-form-field">
                            <label for="department-order">Sort Order</label>
                            <input id="department-order" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" required>
                        </div>
                        <div class="admin-form-field">
                            <label>Status</label>
                            <label class="admin-check">
                                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                                Active
                            </label>
                        </div>
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" class="admin-button admin-button--primary">
                            <i class="fa-solid fa-plus" aria-hidden="true"></i>
                            Add Department
                        </button>
                    </div>
                </form>
            </section>

            <section class="management-panel">
                <div class="management-panel-header">
                    <h4>Department List</h4>
                    <span class="admin-status admin-status--student">{{ $departments->count() }} Total</span>
                </div>
                <div class="admin-table-wrap">
                    <table class="admin-data-table admin-data-table--departments admin-data-table--actions">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Department</th>
                                <th>Queries</th>
                                <th>Status</th>
                                <th>Order</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departments as $department)
                                <tr>
                                    <td>#{{ $department->id }}</td>
                                    <td>
                                        <strong>{{ $department->name }}</strong>
                                    </td>
                                    <td>{{ $department->queries_count }}</td>
                                    <td>
                                        <span class="admin-status admin-status--{{ $department->is_active ? 'active' : 'inactive' }}">
                                            {{ $department->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $department->sort_order }}</td>
                                    <td>{{ $department->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="admin-row-actions">
                                            <a href="{{ route('admin.departments.edit', $department) }}"
                                               class="admin-button admin-button--secondary admin-button--icon"
                                               title="Edit department">
                                                <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.departments.toggle', $department) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="admin-button admin-button--secondary admin-button--icon"
                                                        title="{{ $department->is_active ? 'Deactivate' : 'Activate' }} department">
                                                    <i class="fa-solid {{ $department->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                            <form method="POST"
                                                  action="{{ route('admin.departments.destroy', $department) }}"
                                                  data-confirm-message="Delete this department permanently?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="admin-button admin-button--danger admin-button--icon"
                                                        title="Delete department">
                                                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-admin-layout>
