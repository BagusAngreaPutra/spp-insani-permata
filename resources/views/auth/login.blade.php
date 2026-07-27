<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Guru · Permata Insani</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('layouts.auth-styles')
</head>
<body>
    <main class="auth-panel">
        <a class="auth-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.jpg') }}" alt="">
            <span><strong>Permata Insani</strong><small>Sistem Pembayaran</small></span>
        </a>

        <h1>Masuk Guru</h1>
        <p class="auth-subtitle">Gunakan akun yang sudah terdaftar.</p>

        @if ($errors->any())
            <div class="auth-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="field">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" value="{{ old('username') }}" autocomplete="username" required autofocus>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" autocomplete="current-password" required>
            </div>
            <div class="remember">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Ingat saya</label>
            </div>
            <button class="submit" type="submit">Masuk</button>
        </form>

        <a class="back" href="{{ url('/') }}">Kembali pilih akun</a>
    </main>
</body>
</html>
