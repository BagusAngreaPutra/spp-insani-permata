<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Siswa - SPP SD IT Permata Insani</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --error: #dc2626;
        }

        * {margin:0;padding:0;box-sizing:border-box;}

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            position: relative;
            overflow: auto;
        }

        /* Animated Background Elements */
        .bg-animation {
            position: fixed;
            width: 100vw;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: 0;
            pointer-events: none;
        }

        .floating-shape {
            position: absolute;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            border-radius: 50%;
            animation: float 15s infinite ease-in-out;
        }

        .shape-1 {
            width: 200px;
            height: 200px;
            left: 10%;
            top: 10%;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 150px;
            height: 150px;
            right: 15%;
            top: 20%;
            animation-delay: -5s;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        }

        .shape-3 {
            width: 100px;
            height: 100px;
            left: 20%;
            bottom: 20%;
            animation-delay: -10s;
            border-radius: 63% 37% 54% 46% / 55% 48% 52% 45%;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            25% {
                transform: translate(-20px, 20px) rotate(5deg);
            }
            50% {
                transform: translate(10px, -15px) rotate(-5deg);
            }
            75% {
                transform: translate(15px, 10px) rotate(3deg);
            }
        }

        .login-container {
            position: relative;
            z-index: 10;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 24px;
            padding: 3rem;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            animation: containerAppear 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes containerAppear {
            0% {
                opacity: 0;
                transform: translateY(40px) scale(0.9);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
            animation: fadeIn 0.6s ease-out 0.2s backwards;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 2.2rem;
            margin: 0 auto 1.5rem;
            box-shadow: 0 15px 30px -10px rgba(59,130,246,0.5);
            animation: logoAppear 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.3s backwards;
        }

        @keyframes logoAppear {
            0% {
                transform: scale(0) rotate(-45deg);
                opacity: 0;
            }
            100% {
                transform: scale(1) rotate(0);
                opacity: 1;
            }
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: var(--text-light);
            font-size: 1rem;
        }

        .status-message {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: var(--error);
            font-size: 0.95rem;
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }

        @keyframes shake {
            10%, 90% { transform: translateX(-1px); }
            20%, 80% { transform: translateX(2px); }
            30%, 50%, 70% { transform: translateX(-4px); }
            40%, 60% { transform: translateX(4px); }
        }

        .form-group {
            margin-bottom: 1.5rem;
            animation: fadeIn 0.6s ease-out forwards;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255,255,255,0.9);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
            transform: translateY(-2px);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            animation: fadeIn 0.6s ease-out 0.2s backwards;
        }

        .checkbox-input {
            width: 20px;
            height: 20px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .checkbox-label {
            font-size: 0.95rem;
            color: var(--text-light);
            cursor: pointer;
        }

        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out 0.4s backwards;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px -10px rgba(59,130,246,0.5);
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: -100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shine 2s infinite;
        }

        @keyframes shine {
            100% { left: 100%; }
        }

        .back-to-home {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            animation: fadeIn 0.6s ease-out 0.6s backwards;
        }

        .back-to-home a {
            color: var(--text-light);
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .back-to-home a:hover {
            color: var(--primary);
            transform: translateX(-5px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media(max-width: 480px) {
            .login-container {
                padding: 2rem;
            }
            .login-title {
                font-size: 1.75rem;
            }
            .logo-icon {
                width: 70px;
                height: 70px;
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Background Animation -->
    <div class="bg-animation">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <div class="logo-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h1 class="login-title">Login Siswa</h1>
            <p class="login-subtitle">Masuk untuk melihat tagihan & riwayat SPP Anda</p>
        </div>

        @if ($errors->any())
            <div class="status-message">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('siswa.login.store') }}">
            @csrf
            <div class="form-group">
                <label for="username" class="form-label">
                    <i class="fas fa-user"></i> Username
                </label>
                <input id="username" type="text" name="username"
                       class="form-input" placeholder="Masukkan username siswa"
                       value="{{ old('username') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input id="password" type="password" name="password"
                       class="form-input" placeholder="Masukkan password" required>
            </div>

            <div class="checkbox-group">
                <input id="remember_me" type="checkbox" class="checkbox-input" name="remember">
                <label for="remember_me" class="checkbox-label">Ingat saya</label>
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                <span>Masuk</span>
            </button>
        </form>

        <div class="back-to-home">
            <a href="/">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>
</body>
</html>
