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
                    <i class="fa-solid fa-user-graduate"></i>
                </span>
                <h1>Student Sign In</h1>
                <p>Use your KASBIT email and password to access the feedback and query portal.</p>
            </div>

            <section class="student-auth-card" aria-labelledby="student-login-title">
                <h2 id="student-login-title" class="visually-hidden">Student login form</h2>

                @if($errors->any())
                    <div class="frontend-alert frontend-alert--error" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('student.login.store') }}" data-feedback-form>
                    @csrf

                    <div class="feedback-field-grid">
                        <div class="feedback-field feedback-field--full">
                            <label for="student-email">KASBIT Email</label>
                            <input id="student-email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="student@kasbit.edu.pk"
                                   autocomplete="email"
                                   required
                                   autofocus>
                        </div>

                        <div class="feedback-field feedback-field--full">
                            <label for="student-password">Password</label>
                            <input id="student-password"
                                   type="password"
                                   name="password"
                                   autocomplete="current-password"
                                   required>
                        </div>
                    </div>

                    <label class="feedback-check">
                        <input type="checkbox" name="remember" value="1">
                        <span>Keep me signed in</span>
                    </label>

                    <div class="student-auth-actions">
                        <button type="submit" class="feedback-submit" data-submit-button>
                            <i class="fa-solid fa-arrow-right-to-bracket" aria-hidden="true"></i>
                            <span>Sign In</span>
                        </button>
                        <span class="student-auth-switch">
                            New student?
                            <a href="{{ route('student.register') }}">Create an account</a>
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
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i><span>Signing In...</span>';
        });
    </script>
@endpush
