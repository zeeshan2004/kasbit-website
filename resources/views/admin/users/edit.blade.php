<x-admin-layout>
    <x-slot name="title">Edit User - KASBIT Control</x-slot>
    <x-slot name="header">Edit User</x-slot>

    <div class="management-page">
        <div class="management-heading">
            <div>
                <h3>{{ $managedUser->name }}</h3>
                <p>Update account details, student information, access status or password.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="admin-button admin-button--secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                Back
            </a>
        </div>

        @if($errors->any())
            <div class="admin-alert admin-alert--error">{{ $errors->first() }}</div>
        @endif

        <section class="management-panel">
            <form method="POST" action="{{ route('admin.users.update', $managedUser) }}">
                @csrf
                @method('PUT')

                <div class="admin-form-grid">
                    <div class="admin-form-field">
                        <label for="managed-name">Full Name</label>
                        <input id="managed-name" type="text" name="name" value="{{ old('name', $managedUser->name) }}" required>
                        @error('name')<p class="admin-field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-field">
                        <label for="managed-email">Email Address</label>
                        <input id="managed-email" type="email" name="email" value="{{ old('email', $managedUser->email) }}" required>
                        @error('email')<p class="admin-field-error">{{ $message }}</p>@enderror
                    </div>

                    @if($managedUser->isStudent())
                        <div class="admin-form-field">
                            <label for="managed-student-id">Student ID</label>
                            <input id="managed-student-id"
                                   type="text"
                                   name="student_id"
                                   value="{{ old('student_id', $managedUser->student_id) }}"
                                   required>
                            @error('student_id')<p class="admin-field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="admin-form-field">
                            <label for="managed-program">Program / Course</label>
                            <select id="managed-program" name="program_id" required>
                                @foreach($programGroups as $programGroup)
                                    <optgroup label="{{ $programGroup->name }}">
                                        @foreach($programGroup->children as $program)
                                            <option value="{{ $program->id }}" @selected((int) old('program_id', $managedUser->program_id) === $program->id)>
                                                {{ $program->name }}{{ $program->is_active ? '' : ' (Inactive)' }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('program_id')<p class="admin-field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="admin-form-field">
                            <label for="managed-semester">Semester</label>
                            <select id="managed-semester" name="semester" required>
                                @for($semester = 1; $semester <= 8; $semester++)
                                    <option value="Semester {{ $semester }}" @selected(old('semester', $managedUser->semester) === "Semester {$semester}")>
                                        Semester {{ $semester }}
                                    </option>
                                @endfor
                            </select>
                            @error('semester')<p class="admin-field-error">{{ $message }}</p>@enderror
                        </div>
                    @endif

                    <div class="admin-form-field">
                        <label for="managed-password">New Password</label>
                        <input id="managed-password" type="password" name="password" autocomplete="new-password">
                        @error('password')<p class="admin-field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-field">
                        <label for="managed-password-confirmation">Confirm New Password</label>
                        <input id="managed-password-confirmation"
                               type="password"
                               name="password_confirmation"
                               autocomplete="new-password">
                    </div>
                    <div class="admin-form-field admin-form-field--full">
                        <label class="admin-check">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   @checked(old('is_active', $managedUser->is_active))>
                            Account is active and can sign in
                        </label>
                        @error('is_active')<p class="admin-field-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="admin-button admin-button--primary">
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        Save User
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-admin-layout>
