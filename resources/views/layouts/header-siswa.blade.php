<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Header Component -->
    <header class="header">
        <div class="header-content">
            <div class="welcome-section">
                <h1 class="greeting">Assalamu'alaikum, {{ Auth::guard('siswa')->user()->nama }}</h1>
                <p class="subtitle">Selamat datang! Semoga harimu menyenangkan</p>
            </div>
            
            <div class="user-section">
                <!-- User Avatar -->
                <div class="user-avatar">
                    <span class="avatar-text">
                        {{ strtoupper(substr(Auth::user()->nama_siswa,0,1)) }}
                    </span>
                    <div class="status-indicator"></div>
                </div>

                <!-- User Info -->
                <div class="user-info-display">
                    <span class="user-name">{{ Auth::guard('siswa')->user()->nama }}</span>
                    <span class="user-role">Siswa</span>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="/" class="action-btn profile-btn">
                        <div class="btn-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="btn-text">Menuju Halaman Welcome</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Decorative Elements -->
        <div class="header-decoration">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            <div class="decoration-circle circle-3"></div>
        </div>
    </header>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
        }

        .header {
            position: relative;
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.95) 0%, 
                rgba(255, 255, 255, 0.9) 100%);
            backdrop-filter: blur(30px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 4px 32px rgba(0, 0, 0, 0.08),
                0 1px 2px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 900;
            overflow: hidden;
        }

        .header-content {
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .welcome-section {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .greeting {
            font-size: 2.25rem;
            font-weight: 800;
            background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 0.95rem;
            color: #64748b;
            font-weight: 500;
            opacity: 0.8;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-avatar {
            position: relative;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 
                0 8px 32px rgba(22, 163, 74, 0.3),
                0 0 0 3px rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 12px 40px rgba(22, 163, 74, 0.4),
                0 0 0 3px rgba(255, 255, 255, 0.9);
        }

        .avatar-ring {
            position: absolute;
            width: 72px;
            height: 72px;
            border: 2px solid transparent;
            border-radius: 50%;
            background: linear-gradient(45deg, #16a34a, #15803d) border-box;
            mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            animation: rotate 8s linear infinite;
        }

        .avatar-text {
            color: white;
            font-weight: 800;
            font-size: 1.4rem;
            z-index: 1;
        }

        .status-indicator {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 16px;
            height: 16px;
            background: #10b981;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .user-info-display {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 2px;
        }

        .user-name {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.3;
        }

        .user-role {
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
        }

        .header .action-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header .action-btn {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            gap: 10px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            color: #374151;
            text-align: center;
        }

        .header .action-btn:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        }

        .header .profile-btn:hover {
            border-color: rgba(22, 163, 74, 0.3);
            color: #16a34a;
        }

        .header .profile-btn:hover .btn-icon {
            background: rgba(22, 163, 74, 0.15);
            transform: scale(1.1);
        }

        .header .profile-btn:hover .btn-icon i {
            color: #16a34a;
        }

        .header .logout-btn {
            border: 1px solid rgba(239, 68, 68, 0.2);
            background: rgba(255, 255, 255, 0.8);
        }

        .header .logout-btn:hover {
            border-color: rgba(239, 68, 68, 0.3);
            background: rgba(255, 255, 255, 0.95);
            color: #ef4444;
        }

        .header .logout-btn:hover .btn-icon {
            background: rgba(239, 68, 68, 0.15);
            transform: scale(1.1);
        }

        .header .logout-btn:hover .btn-icon i {
            color: #ef4444;
        }

        .header .btn-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: rgba(22, 163, 74, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .header .logout-btn .btn-icon {
            background: rgba(239, 68, 68, 0.1);
        }

        .header .btn-icon i {
            color: #16a34a;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .header .logout-btn .btn-icon i {
            color: #ef4444;
        }

        .header .btn-text {
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        /* Decorative Elements */
        .header-decoration {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 1;
        }

        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(22, 163, 74, 0.1), rgba(22, 163, 74, 0.05));
            animation: float 6s ease-in-out infinite;
        }

        .circle-1 {
            width: 100px;
            height: 100px;
            top: -50px;
            right: 20%;
            animation-delay: 0s;
        }

        .circle-2 {
            width: 60px;
            height: 60px;
            top: 20px;
            right: 40%;
            animation-delay: 2s;
        }

        .circle-3 {
            width: 80px;
            height: 80px;
            top: -40px;
            right: 60%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                padding: 1.5rem 1rem;
                gap: 1rem;
            }
            
            .greeting {
                font-size: 1.75rem;
            }
            
            .user-section {
                gap: 1rem;
            }
            
            .user-avatar {
                width: 48px;
                height: 48px;
            }

            .avatar-text {
                font-size: 1.2rem;
            }
            
            .header .action-btn {
                padding: 12px 16px;
                font-size: 13px;
            }

            .header .btn-text {
                display: none;
            }

            .header .btn-icon {
                width: 36px;
                height: 36px;
            }

            .welcome-section .subtitle {
                display: none;
            }

            .user-info-display {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .header-content {
                flex-direction: column;
                gap: 1.5rem;
                padding: 1.5rem 1rem;
            }
            
            .greeting {
                font-size: 1.5rem;
                text-align: center;
            }

            .welcome-section {
                align-items: center;
            }

            .user-section {
                justify-content: center;
                width: 100%;
            }

            .header .action-buttons {
                gap: 16px;
            }
        }

        /* Pulse animation for status indicator */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .status-indicator {
            animation: pulse 2s infinite;
        }

        .main-content > .header .header-content {
            margin: 0 !important;
            max-width: none !important;
            min-height: 86px;
            padding: 0.85rem 1.25rem !important;
            width: 100%;
        }

        .main-content > .header .welcome-section {
            align-items: flex-start;
            left: 1.25rem;
            max-width: min(56vw, 680px);
            position: absolute;
            top: 44%;
            transform: translateY(-50%);
            z-index: 2;
        }

        .main-content > .header .greeting {
            display: block;
            font-size: clamp(1.05rem, 1.65vw, 1.45rem);
            line-height: 1.2;
            max-width: 100%;
            overflow: hidden;
            text-align: left;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .main-content > .header .subtitle {
            font-size: 0.82rem;
            margin: 0;
        }

        .main-content > .header .user-section {
            justify-content: flex-end;
            margin-left: auto;
            max-width: 42%;
            min-width: 280px;
            position: relative;
            z-index: 3;
        }

        .main-content > .header .user-name {
            max-width: 18ch;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .main-content > .header .action-buttons {
            display: inline-flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            gap: 0.5rem;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .main-content > .header .header-content {
                align-items: flex-start;
                flex-direction: row !important;
                min-height: 116px;
                padding: 0.9rem 1rem !important;
            }

            .main-content > .header .welcome-section {
                left: 1rem;
                max-width: calc(100% - 2rem);
                top: 0.9rem;
                transform: none;
            }

            .main-content > .header .greeting {
                font-size: 1.05rem;
            }

            .main-content > .header .user-section {
                margin-left: auto;
                margin-top: 3.5rem;
                max-width: 100%;
                min-width: 0;
                width: auto;
            }
        }

        @media (max-width: 480px) {
            .main-content > .header .header-content {
                min-height: 110px;
                padding: 0.8rem 0.85rem !important;
            }

            .main-content > .header .welcome-section {
                align-items: flex-start;
                left: 0.85rem;
                max-width: calc(100% - 1.7rem);
            }

            .main-content > .header .greeting {
                font-size: 0.98rem;
                text-align: left;
            }

            .main-content > .header .user-section {
                justify-content: flex-end;
                margin-top: 3.35rem;
            }
        }
    </style>

    <script>
        // Ripple effect tetap digunakan
        document.addEventListener('DOMContentLoaded', function() {
            document.documentElement.style.scrollBehavior = 'smooth';
            const actionButtons = document.querySelectorAll('.action-btn');
            actionButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    this.appendChild(ripple);
                    setTimeout(() => { ripple.remove(); }, 600);
                });
            });
        });
    </script>
