<x-admin-layout>
    <x-slot name="title">Edit Feedback Department - KASBIT Control</x-slot>
    <x-slot name="header">Edit Feedback Department</x-slot>

    <div class="management-page">
        <div class="management-heading">
            <div>
                <h3>{{ $department->name }}</h3>
                <p>Update department details, frontend availability and ordering.</p>
            </div>
            <a href="{{ route('admin.departments.index') }}" class="admin-button admin-button--secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                Back
            </a>
        </div>

        @if($errors->any())
            <div class="admin-alert admin-alert--error">{{ $errors->first() }}</div>
        @endif

        <section class="management-panel">
            <form method="POST" action="{{ route('admin.departments.update', $department) }}">
                @csrf
                @method('PUT')
                <div class="admin-form-grid">
                    <div class="admin-form-field">
                        <label for="department-name">Department Name</label>
                        <input id="department-name" type="text" name="name" value="{{ old('name', $department->name) }}" required>
                    </div>
                    <div class="admin-form-field">
                        <label for="department-order">Sort Order</label>
                        <input id="department-order" type="number" name="sort_order" value="{{ old('sort_order', $department->sort_order) }}" min="0" required>
                    </div>
                    <div class="admin-form-field admin-form-field--full">
                        <label class="admin-check">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $department->is_active))>
                            Active on the student feedback form
                        </label>
                    </div>
                </div>
                <div class="admin-form-actions">
                    <button type="submit" class="admin-button admin-button--primary">
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-admin-layout>
