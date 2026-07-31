<x-admin-layout>
    <x-slot name="title">Programs &amp; Courses - KASBIT Control</x-slot>
    <x-slot name="header">Programs &amp; Courses</x-slot>

    <div class="management-page">
        @if(session('success'))
            <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="admin-alert admin-alert--error">{{ $errors->first() }}</div>
        @endif

        <div class="management-heading">
            <div>
                <h3>Registration Courses</h3>
                <p>Active courses appear automatically in the student registration form.</p>
            </div>
        </div>

        <div class="management-split">
            <section class="management-panel">
                <div class="management-panel-header">
                    <h4>Add Course</h4>
                </div>
                <form method="POST" action="{{ route('admin.programs.store') }}">
                    @csrf
                    <div class="admin-form-grid">
                        <div class="admin-form-field admin-form-field--full">
                            <label for="program-group">Program Category</label>
                            <select id="program-group" name="parent_id" required>
                                <option value="">Select category</option>
                                @foreach($programGroups as $programGroup)
                                    <option value="{{ $programGroup->id }}" @selected((int) old('parent_id') === $programGroup->id)>
                                        {{ $programGroup->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')<p class="admin-field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="admin-form-field admin-form-field--full">
                            <label for="program-name">Course Name</label>
                            <input id="program-name" type="text" name="name" value="{{ old('name') }}" required>
                            @error('name')<p class="admin-field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="admin-form-field">
                            <label for="program-order">Sort Order</label>
                            <input id="program-order" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" required>
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
                            Add Course
                        </button>
                    </div>
                </form>
            </section>

            <section class="management-panel">
                <div class="management-panel-header">
                    <h4>Course List</h4>
                    <span class="admin-status admin-status--student">
                        {{ $programGroups->sum(fn ($group) => $group->children->count()) }} Total
                    </span>
                </div>
                <div class="admin-table-wrap">
                    <table class="admin-data-table admin-data-table--programs admin-data-table--actions">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Students</th>
                                <th>Status</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($programGroups as $programGroup)
                                @foreach($programGroup->children as $program)
                                    <tr>
                                        <td><strong>{{ $program->name }}</strong></td>
                                        <td>{{ $programGroup->name }}</td>
                                        <td>{{ $program->students_count }}</td>
                                        <td>
                                            <span class="admin-status admin-status--{{ $program->is_active ? 'active' : 'inactive' }}">
                                                {{ $program->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $program->sort_order }}</td>
                                        <td>
                                            <div class="admin-row-actions">
                                                <a href="{{ route('admin.programs.edit', $program) }}"
                                                   class="admin-button admin-button--secondary admin-button--icon"
                                                   title="Edit course">
                                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.programs.toggle', $program) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="admin-button admin-button--secondary admin-button--icon"
                                                            title="{{ $program->is_active ? 'Deactivate' : 'Activate' }} course">
                                                        <i class="fa-solid {{ $program->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                                <form method="POST"
                                                      action="{{ route('admin.programs.destroy', $program) }}"
                                                      data-confirm-message="Delete this course permanently?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="admin-button admin-button--danger admin-button--icon"
                                                            title="Delete course">
                                                        <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-admin-layout>
