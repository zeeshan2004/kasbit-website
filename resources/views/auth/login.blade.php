<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KASBIT Admin - Login</title>
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}?v={{ filemtime(public_path('vendor/fontawesome/css/all.min.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}?v={{ filemtime(public_path('css/admin-login.css')) }}">
</head>
<body>
    <main class="admin-login-card">
        <div class="admin-login-brand">
            <i class="fa-solid fa-graduation-cap" aria-hidden="true"></i>
            <h1>KASBIT Admin</h1>
        </div>
        
        @if ($errors->any())
            <div class="admin-login-error" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="admin-login-field">
                <label for="admin-email">Email Address</label>
                <input id="admin-email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="admin-login-field">
                <label for="admin-password">Password</label>
                <input id="admin-password" type="password" name="password" required>
            </div>
            <button type="submit" class="admin-login-submit">Sign In</button>
        </form>
    </main>
</body>
</html>
