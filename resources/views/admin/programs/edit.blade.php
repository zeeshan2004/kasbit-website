<x-admin-layout>
    <x-slot name="title">Edit Course - KASBIT Control</x-slot>
    <x-slot name="header">Edit Course</x-slot>

    <div class="management-page">
        <div class="management-heading">
            <div>
                <h3>{{ $program->name }}</h3>
                <p>Update the course shown in student registration.</p>
            </div>
            <a href="{{ route('admin.programs.index') }}" class="admin-button admin-button--secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                Back
            </a>
        </div>

        @if($errors->any())
            <div class="admin-alert admin-alert--error">{{ $errors->first() }}</div>
        @endif

        <section class="management-panel">
            <form method="POST" action="{{ route('admin.programs.update', $program) }}">
                @csrf
                @method('PUT')
                <div class="admin-form-grid">
                    <div class="admin-form-field">
                        <label for="program-group">Program Category</label>
                        <select id="program-group" name="parent_id" required>
                            @foreach($programGroups as $programGroup)
                                <option value="{{ $programGroup->id }}" @selected((int) old('parent_id', $program->parent_id) === $programGroup->id)>
                                    {{ $programGroup->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="admin-form-field">
                        <label for="program-name">Course Name</label>
                        <input id="program-name" type="text" name="name" value="{{ old('name', $program->name) }}" required>
                    </div>
                    <div class="admin-form-field">
                        <label for="program-order">Sort Order</label>
                        <input id="program-order" type="number" name="sort_order" value="{{ old('sort_order', $program->sort_order) }}" min="0" required>
                    </div>
                    <div class="admin-form-field">
                        <label>Status</label>
                        <label class="admin-check">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $program->is_active))>
                            Active
                        </label>
                    </div>
                </div>
                <div class="admin-form-actions">
                    <button type="submit" class="admin-button admin-button--primary">
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        Save Course
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-admin-layout>
