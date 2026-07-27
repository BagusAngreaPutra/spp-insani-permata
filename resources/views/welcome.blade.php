@if (session('status') === 'account-deleted')
    <div class="mb-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-700">
        {{ __('Akun Anda berhasil dihapus.') }}
    </div>
@endif

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SPP SD IT Permata Insani - Sistem Pembayaran</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <!-- Styles -->
        <style>
            :root {
                /* Primary Green Colors */
                --primary-green: #059669;
                --primary-green-light: #10b981;
                --primary-green-dark: #047857;
                
                /* Complementary Navy Blue Colors */
                --navy-primary: #1e3a8a;
                --navy-light: #3b82f6;
                --navy-dark: #1e40af;
                
                /* Neutral Colors */
                --white: #ffffff;
                --gray-50: #f9fafb;
                --gray-100: #f3f4f6;
                --gray-200: #e5e7eb;
                --gray-300: #d1d5db;
                --gray-400: #9ca3af;
                --gray-500: #6b7280;
                --gray-600: #4b5563;
                --gray-700: #374151;
                --gray-800: #1f2937;
                --gray-900: #111827;
                
                /* Accent Colors */
                --gold: #f59e0b;
                --gold-light: #fbbf24;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
                min-height: 100vh;
                overflow-x: hidden;
                line-height: 1.6;
            }

            .hero-section {
                position: relative;
                min-height: 100vh;
                display: flex;
                align-items: center;
                background: linear-gradient(135deg, 
                    rgba(5, 150, 105, 0.95) 0%, 
                    rgba(4, 120, 87, 0.98) 50%,
                    rgba(30, 58, 138, 0.9) 100%);
                padding-top: 150px;
                padding-bottom: 80px; 
            }

            .hero-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" style="stop-color:rgba(255,255,255,0.08)"/><stop offset="100%" style="stop-color:rgba(255,255,255,0)"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"/><circle cx="800" cy="300" r="150" fill="url(%23a)"/><circle cx="400" cy="700" r="80" fill="url(%23a)"/><circle cx="900" cy="800" r="120" fill="url(%23a)"/></svg>');
                opacity: 0.4;
            }

            .navbar {
                position: fixed;
                top: 0;
                width: 100%;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                z-index: 1000;
                padding: 1.2rem 0;
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
                border-bottom: 1px solid rgba(5, 150, 105, 0.1);
            }

            .navbar-content {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .logo {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .logo-icon {
                width: 48px;
                height: 48px;
                background: linear-gradient(135deg, var(--primary-green), var(--navy-primary));
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.4rem;
                box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
            }

            .logo-text {
                font-size: 1.4rem;
                font-weight: 800;
                color: var(--gray-800);
                letter-spacing: -0.5px;
            }

            .nav-buttons {
                display: flex;
                gap: 1rem;
                align-items: center;
            }

            .nav-link {
                text-decoration: none;
                color: var(--gray-600);
                font-weight: 500;
                padding: 0.75rem 1.5rem;
                border-radius: 10px;
                transition: all 0.3s ease;
                font-size: 0.95rem;
            }

            .nav-link:hover {
                background: var(--gray-100);
                color: var(--primary-green);
                transform: translateY(-1px);
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
                color: white;
                padding: 0.875rem 2rem;
                border-radius: 12px;
                text-decoration: none;
                font-weight: 600;
                font-size: 0.95rem;
                transition: all 0.3s ease;
                box-shadow: 0 6px 20px rgba(5, 150, 105, 0.25);
                border: none;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(5, 150, 105, 0.35);
                color: white;
                background: linear-gradient(135deg, var(--primary-green-light), var(--primary-green));
            }

            .hero-content {
                position: relative;
                z-index: 10;
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 2rem;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 5rem;
                align-items: center;
            }

            .hero-text {
                color: white;
            }

            .hero-title {
                font-size: 3.8rem;
                font-weight: 800;
                line-height: 1.1;
                margin-bottom: 1.5rem;
                text-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                letter-spacing: -1px;
            }

            .hero-subtitle {
                font-size: 1.35rem;
                font-weight: 600;
                margin-bottom: 1.2rem;
                opacity: 0.95;
                color: var(--gold-light);
            }

            .hero-description {
                font-size: 1.15rem;
                line-height: 1.7;
                margin-bottom: 3rem;
                opacity: 0.9;
                font-weight: 400;
            }

            .cta-buttons {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                margin-bottom: 3rem;
            }

            .btn-cta {
                padding: 1.1rem 2.5rem;
                border-radius: 14px;
                text-decoration: none;
                font-weight: 600;
                font-size: 1.05rem;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
                min-height: 56px;
                position: relative;
                overflow: hidden;
            }

            .btn-cta::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
                transition: left 0.5s;
            }

            .btn-cta:hover::before {
                left: 100%;
            }

            .btn-cta-primary {
                background: linear-gradient(135deg, var(--white), var(--gray-50));
                color: var(--primary-green);
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
                border: 2px solid rgba(255, 255, 255, 0.8);
            }

            .btn-cta-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.18);
                color: var(--primary-green-dark);
                background: linear-gradient(135deg, var(--white), var(--white));
            }

            .btn-cta-secondary {
                background: linear-gradient(135deg, var(--navy-primary), var(--navy-dark));
                color: white;
                border: 2px solid rgba(255, 255, 255, 0.2);
                backdrop-filter: blur(10px);
                box-shadow: 0 8px 30px rgba(30, 58, 138, 0.25);
            }

            .btn-cta-secondary:hover {
                background: linear-gradient(135deg, var(--navy-light), var(--navy-primary));
                transform: translateY(-3px);
                color: white;
                box-shadow: 0 12px 40px rgba(30, 58, 138, 0.35);
            }

            .hero-cards {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1.75rem;
                width: 100%;
            }

            .feature-card {
                background: rgba(255, 255, 255, 0.12);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.18);
                border-radius: 20px;
                padding: 2.5rem;
                color: white;
                transition: all 0.4s ease;
                width: 100%;
                position: relative;
                overflow: hidden;
            }

            .feature-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .feature-card:hover::before {
                opacity: 1;
            }

            .feature-card:hover {
                transform: translateY(-8px);
                background: rgba(255, 255, 255, 0.18);
                border-color: rgba(255, 255, 255, 0.3);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            }

            .feature-icon {
                width: 70px;
                height: 70px;
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.75rem;
                margin-bottom: 1.5rem;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .feature-title {
                font-size: 1.4rem;
                font-weight: 700;
                margin-bottom: 1rem;
                letter-spacing: -0.3px;
            }

            .feature-description {
                line-height: 1.7;
                opacity: 0.92;
                font-weight: 400;
            }

            .stats-section {
                background: linear-gradient(135deg, var(--white), var(--gray-50));
                padding: 5rem 0;
                position: relative;
                overflow: hidden;
            }

            .stats-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="b" cx="50%" cy="50%"><stop offset="0%" style="stop-color:rgba(5,150,105,0.03)"/><stop offset="100%" style="stop-color:rgba(5,150,105,0)"/></radialGradient></defs><circle cx="200" cy="200" r="150" fill="url(%23b)"/><circle cx="800" cy="300" r="200" fill="url(%23b)"/><circle cx="400" cy="700" r="120" fill="url(%23b)"/></svg>');
                opacity: 0.6;
            }

            .stats-content {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 2rem;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 2rem;
                text-align: center;
                position: relative;
                z-index: 2;
            }

            .stat-item {
                padding: 3rem 2rem;
                border-radius: 24px;
                background: linear-gradient(135deg, var(--white), rgba(5, 150, 105, 0.02));
                position: relative;
                overflow: hidden;
                border: 1px solid rgba(5, 150, 105, 0.08);
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
                transition: all 0.3s ease;
            }

            .stat-item:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
                border-color: rgba(5, 150, 105, 0.15);
            }

            .stat-item::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(5, 150, 105, 0.05) 0%, transparent 70%);
                animation: pulse 4s infinite;
            }

            .stat-number {
                font-size: 3.5rem;
                font-weight: 800;
                background: linear-gradient(135deg, var(--primary-green), var(--navy-primary));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 0.75rem;
                position: relative;
                z-index: 2;
            }

            .stat-label {
                font-size: 1.15rem;
                color: var(--gray-600);
                font-weight: 600;
                position: relative;
                z-index: 2;
                letter-spacing: -0.2px;
            }

            .footer {
                background: linear-gradient(135deg, var(--gray-800), var(--gray-900));
                color: white;
                padding: 4rem 0 2.5rem;
                text-align: center;
                position: relative;
            }

            .footer::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 2px;
                background: linear-gradient(90deg, var(--primary-green), var(--navy-primary), var(--gold));
            }

            .footer-content {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 2rem;
            }

            .footer-text {
                font-size: 0.95rem;
                color: var(--gray-400);
                margin-bottom: 1rem;
            }

            .school-info {
                margin-bottom: 2.5rem;
            }

            .school-name {
                font-size: 1.75rem;
                font-weight: 700;
                background: linear-gradient(135deg, var(--primary-green-light), var(--gold-light));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 1rem;
                letter-spacing: -0.5px;
            }

            .school-address {
                color: var(--gray-300);
                line-height: 1.8;
                font-weight: 400;
            }

            @keyframes pulse {
                0%, 100% { transform: scale(1); opacity: 0.05; }
                50% { transform: scale(1.05); opacity: 0.1; }
            }

            /* Responsive Design */
            @media (max-width: 1024px) {
                .hero-content {
                    gap: 4rem;
                }
                
                .hero-title {
                    font-size: 3.2rem;
                }

                .hero-section {
                    padding-top: 130px;
                }

                .btn-cta {
                    padding: 1rem 2rem;
                    font-size: 1rem;
                }
            }

            @media (max-width: 768px) {
                .hero-content {
                    grid-template-columns: 1fr;
                    gap: 3rem;
                    text-align: center;
                }

                .hero-title {
                    font-size: 2.8rem;
                }

                .hero-subtitle {
                    font-size: 1.2rem;
                }

                .hero-description {
                    font-size: 1.05rem;
                }

                .hero-section {
                    padding-top: 160px;
                }

                .cta-buttons {
                    align-items: center;
                }

                .btn-cta {
                    width: 100%;
                    max-width: 400px;
                    justify-content: center;
                }

                .nav-buttons {
                    flex-direction: column;
                    gap: 0.75rem;
                }

                .navbar {
                    padding: 1rem 0;
                }

                .navbar-content {
                    flex-direction: column;
                    gap: 1.5rem;
                }

                .logo-text {
                    font-size: 1.2rem;
                }

                .feature-card {
                    padding: 2rem;
                }

                .stats-content {
                    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                    gap: 1.5rem;
                }

                .stat-item {
                    padding: 2.5rem 1.5rem;
                }

                .stat-number {
                    font-size: 3rem;
                }
            }

            @media (max-width: 480px) {
                .hero-content {
                    padding: 0 1rem;
                }

                .navbar-content {
                    padding: 0 1rem;
                }

                .hero-title {
                    font-size: 2.2rem;
                }

                .hero-subtitle {
                    font-size: 1.1rem;
                }

                .hero-description {
                    font-size: 1rem;
                }

                .hero-section {
                    padding-top: 180px;
                }

                .btn-cta {
                    padding: 1rem 1.5rem;
                    font-size: 0.95rem;
                    max-width: 100%;
                }

                .feature-card {
                    padding: 1.75rem;
                }

                .feature-icon {
                    width: 60px;
                    height: 60px;
                    font-size: 1.5rem;
                }

                .feature-title {
                    font-size: 1.25rem;
                }

                .feature-description {
                    font-size: 0.95rem;
                }

                .stats-content {
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 1.25rem;
                    padding: 0 1rem;
                }

                .stat-item {
                    padding: 2rem 1.25rem;
                }

                .stat-number {
                    font-size: 2.5rem;
                }

                .stat-label {
                    font-size: 1rem;
                }

                .footer-content {
                    padding: 0 1rem;
                }

                .school-name {
                    font-size: 1.5rem;
                }

                .school-address {
                    font-size: 0.95rem;
                }
            }
        </style>
    </head>
    <body>
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-content">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="logo-text">SPP Permata Insani</div>
                </div>
                
                @if (Route::has('login'))
                    <div class="nav-buttons">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Masuk
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">Sistem Pembayaran SPP Digital</h1>
                    <p class="hero-subtitle">Permata Insani Islamic School</p>
                    <p class="hero-description">
                        Platform digital terintegrasi untuk mengelola pembayaran SPP dan administrasi keuangan sekolah dengan mudah, aman, dan efisien. Memberikan transparansi penuh untuk orang tua dan kemudahan administrasi untuk staff sekolah.
                    </p>
                    
                    <div class="cta-buttons">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-cta btn-cta-primary">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard Sistem
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-cta btn-cta-primary">
                                <i class="fas fa-user-shield"></i>
                                Portal Admin & Staff
                            </a>   
                            
                            <a href="{{ route('siswa.login') }}" class="btn-cta btn-cta-secondary">
                                <i class="fas fa-users"></i>
                                Portal Siswa & Orang Tua
                            </a>   
                        @endauth
                    </div>
                </div>

                <div class="hero-cards">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h3 class="feature-title">Pembayaran Digital Aman</h3>
                        <p class="feature-description">
                            Proses pembayaran SPP yang mudah dan aman dengan berbagai metode pembayaran digital yang tersedia dan terenkripsi.
                        </p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h3 class="feature-title">Dashboard & Laporan Real-time</h3>
                        <p class="feature-description">
                            Pantau status pembayaran dan generate laporan keuangan secara real-time dengan dashboard yang intuitif dan komprehensif.
                        </p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bell-concierge"></i>
                        </div>
                        <h3 class="feature-title">Notifikasi & Reminder</h3>
                        <p class="feature-description">
                            Sistem notifikasi otomatis untuk mengingatkan jadwal pembayaran SPP dan update status pembayaran secara real-time.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="stats-content">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Siswa Aktif</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Tingkat Kepuasan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Akses Sistem</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Keamanan Data</div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <div class="school-info">
                    <div class="school-name">SD IT Permata Insani Islamic School Jambi</div>
                    <div class="school-address">
                        Jl. Abdul Muis No. 27, Lingkar Selatan, Paal Merah<br>
                        Kota Jambi, Jambi 36139<br>
                        Telp: — 0811742088
                    </div>
                </div>
                <div class="footer-text">
                    &copy; {{ date('Y') }} SD IT Permata Insani Islamic School Jambi. Sistem Pembayaran SPP Digital.
                </div>
            </div>
        </footer>

    </body>
</html>