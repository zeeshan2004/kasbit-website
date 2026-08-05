@extends('layout.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/feedback.css') }}?v={{ filemtime(public_path('css/feedback.css')) }}">
@endpush

@section('content')
    @include('frontend.partials.header')

    <main class="student-access-page">
        <div class="student-access-inner">
            <div class="student-access-heading">
                <span class="access-icon" aria-hidden="true">
                    <i class="fa-solid fa-user-plus"></i>
                </span>
                <h1>Create Student Account</h1>
                <p>Register with your KASBIT email and academic details to submit and track feedback queries.</p>
            </div>

            <section class="student-auth-card student-auth-card--wide" aria-labelledby="student-register-title">
                <h2 id="student-register-title" class="visually-hidden">Student registration form</h2>

                @if($errors->any())
                    <div class="frontend-alert frontend-alert--error" role="alert">
                        Please correct the highlighted fields.
                    </div>
                @endif

                <form method="POST" action="{{ route('student.register.store') }}" data-feedback-form>
                    @csrf

                    <div class="feedback-field-grid">
                        <div class="feedback-field">
                            <label for="register-name">Full Name</label>
                            <input id="register-name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="feedback-field">
                            <label for="register-email">Student Email</label>
                            <input id="register-email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="your@email.com"
                                   required>
                            @error('email')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="feedback-field">
                            <label for="register-student-id">Student ID</label>
                            <input id="register-student-id"
                                   type="text"
                                   name="student_id"
                                   value="{{ old('student_id') }}"
                                   placeholder="KASBIT-12345"
                                   required>
                            @error('student_id')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="feedback-field">
                            <label for="register-program">Program / Course</label>
                            <select id="register-program" name="program_id" required>
                                <option value="">Select program or course</option>
                                @foreach($programGroups as $programGroup)
                                    @foreach($programGroup->children as $program)
                                        <option value="{{ $program->id }}" @selected((int) old('program_id') === $program->id)>
                                            {{ $program->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('program_id')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="feedback-field">
                            <label for="register-semester">Semester</label>
                            <select id="register-semester" name="semester" required>
                                <option value="">Select semester</option>
                                @for($semester = 1; $semester <= 8; $semester++)
                                    <option value="Semester {{ $semester }}" @selected(old('semester') === "Semester {$semester}")>
                                        Semester {{ $semester }}
                                    </option>
                                @endfor
                            </select>
                            @error('semester')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="feedback-field">
                            <label for="register-password">Password</label>
                            <input id="register-password" type="password" name="password" autocomplete="new-password" required>
                            @error('password')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="feedback-field">
                            <label for="register-password-confirmation">Confirm Password</label>
                            <input id="register-password-confirmation"
                                   type="password"
                                   name="password_confirmation"
                                   autocomplete="new-password"
                                   required>
                        </div>
                    </div>

                    <div class="student-auth-actions">
                        <button type="submit" class="feedback-submit" data-submit-button>
                            <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                            <span>Create Account</span>
                        </button>
                        <span class="student-auth-switch">
                            Already registered?
                            <a href="{{ route('student.login') }}">Sign in</a>
                        </span>
                    </div>
                </form>
            </section>
        </div>
    </main>

    @include('frontend.partials.footer')
@endsection

@push('scripts')
    <script>
        document.querySelector('[data-feedback-form]')?.addEventListener('submit', (event) => {
            const button = event.currentTarget.querySelector('[data-submit-button]');
            if (!button || button.disabled) return;

            button.disabled = true;
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i><span>Creating Account...</span>';
        });
    </script>
@endpush
