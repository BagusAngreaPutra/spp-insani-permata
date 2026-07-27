<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Add animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <header class="header animate__animated animate__fadeIn">
        <div class="header-glass"></div>
        <div class="header-content">
            <!-- Replace the existing greeting section -->
            <div class="welcome-section animate__animated animate__slideInLeft">
                <div class="greeting-wrapper">
                    <h1 class="greeting">
                        <div class="greeting-line">
                            <span class="greeting-text">Assalamu'alaikum,</span>
                            <span class="admin-name">{{ Auth::user()->nama_admin }}</span>
                        </div>
                    </h1>
                    <div class="additional-info">
                        <p class="subtitle">
                            <i class="far fa-clock clock-icon"></i>
                            <span class="current-time"></span> - 
                            <span class="time-greeting"></span>
                        </p>
                        <p class="date-info">
                            <i class="far fa-calendar-alt calendar-icon"></i>
                            <span class="current-date"></span>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="user-section animate__animated animate__slideInRight">
                <div class="user-avatar">
                    <span class="avatar-text">{{ substr(Auth::user()->nama_admin, 0, 1) }}</span>
                    <div class="status-indicator"></div>
                </div>

                <div class="user-info-display">
                    <span class="user-name">{{ Auth::user()->nama_admin }}</span>
                    <span class="user-role">Administrator</span>
                </div>

                <div class="action-buttons">
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="action-btn logout-btn hover-effect">
                            <div class="btn-icon">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <span class="btn-text">Logout</span>
                            <div class="btn-background"></div>
                        </button>
                    </form>
                    
                    <a href="/" class="action-btn profile-btn hover-effect">
                        <div class="btn-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <span class="btn-text">Beranda</span>
                        <div class="btn-background"></div>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="header-decoration">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            <div class="decoration-circle circle-3"></div>
            <div class="geometric-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>
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
            background: transparent;
            position: relative;
            padding: 1rem;
            overflow: hidden;
        }

        .header-glass {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            box-shadow: 
                0 10px 30px -10px rgba(0, 0, 0, 0.1),
                0 1px 2px rgba(0, 0, 0, 0.05);
            z-index: 0;
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

        .greeting-wrapper {
            position: relative;
            overflow: hidden;
        }

        .greeting {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .greeting-text {
            color: #374151;
            font-weight: 600;
        }

        .header .admin-name {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        .current-time {
            font-family: 'Inter', monospace;
            font-weight: 600;
            color: #059669;
            background: rgba(5, 150, 105, 0.1);
            padding: 2px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        .subtitle {
            font-size: 0.95rem;
            color: #64748b;
            font-weight: 500;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .clock-icon {
            animation: pulse 2s infinite;
            margin-right: 0.5rem;
            color: #059669;
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

        .geometric-shapes {
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            background: linear-gradient(135deg, rgba(5, 150, 105, 0.1), rgba(4, 120, 87, 0.05));
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -150px;
            animation: floatShape 15s infinite;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            bottom: -100px;
            right: 20%;
            animation: floatShape 20s infinite reverse;
        }

        .shape-3 {
            width: 150px;
            height: 150px;
            top: 20%;
            left: -75px;
            animation: floatShape 17s infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes floatShape {
            0% {
                transform: rotate(0deg) translate(0, 0);
            }
            50% {
                transform: rotate(180deg) translate(50px, 50px);
            }
            100% {
                transform: rotate(360deg) translate(0, 0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-glass {
                background: rgba(255, 255, 255, 0.9);
            }
            
            .greeting {
                font-size: 2rem;
            }
        }

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

            .current-time {
                font-size: 0.9rem;
                padding: 2px 6px;
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

        .greeting-line {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .additional-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .date-info {
            font-size: 0.95rem;
            color: #64748b;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .calendar-icon {
            color: #059669;
        }

        .subtitle, .date-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            opacity: 0.9;
            transition: all 0.3s ease;
        }

        .subtitle:hover, .date-info:hover {
            opacity: 1;
            transform: translateX(5px);
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

        .main-content > .header .greeting-wrapper {
            max-width: 100%;
            overflow: visible;
        }

        .main-content > .header .greeting {
            font-size: clamp(1.05rem, 1.65vw, 1.45rem);
            line-height: 1.2;
            margin-bottom: 0.15rem;
        }

        .main-content > .header .greeting-line {
            flex-wrap: nowrap;
            justify-content: flex-start;
            margin-bottom: 0.25rem;
            max-width: 100%;
            min-width: 0;
        }

        .main-content > .header .admin-name {
            display: inline-block;
            max-width: 34ch;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .main-content > .header .additional-info {
            align-items: flex-start;
            gap: 0.25rem;
            margin-top: 0.15rem;
        }

        .main-content > .header .subtitle,
        .main-content > .header .date-info {
            font-size: 0.82rem;
            justify-content: flex-start;
            margin: 0;
        }

        .main-content > .header .user-section {
            justify-content: flex-end;
            margin-left: auto;
            max-width: 42%;
            min-width: 300px;
            position: relative;
            z-index: 3;
        }

        .main-content > .header .user-info-display {
            min-width: 0;
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
            overflow: visible;
            white-space: nowrap;
        }

        .main-content > .header .action-buttons > * {
            flex: 0 0 auto;
        }

        @media (max-width: 768px) {
            .main-content > .header .header-content {
                align-items: flex-start;
                flex-direction: row !important;
                min-height: 124px;
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
                text-align: left;
            }

            .main-content > .header .admin-name {
                max-width: calc(100vw - 12rem);
            }

            .main-content > .header .user-section {
                margin-left: auto;
                margin-top: 3.9rem;
                max-width: 100%;
                min-width: 0;
                width: auto;
            }

            .main-content > .header .action-buttons {
                gap: 0.45rem;
            }
        }

        @media (max-width: 480px) {
            .main-content > .header .header-content {
                gap: 0;
                min-height: 118px;
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
                margin-top: 3.7rem;
            }

            .main-content > .header .action-buttons {
                gap: 0.4rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dynamic time-based greeting
            function updateGreeting() {
                const hour = new Date().getHours();
                const greetingElement = document.querySelector('.time-greeting');
                let greeting = '';
                
                if (hour >= 5 && hour < 12) {
                    greeting = 'Selamat Pagi! Have a great morning.';
                } else if (hour >= 12 && hour < 15) {
                    greeting = 'Selamat Siang! Keep up the good work.';
                } else if (hour >= 15 && hour < 19) {
                    greeting = 'Selamat Sore! Finish strong today.';
                } else {
                    greeting = 'Selamat Malam! Time to wrap up.';
                }
                
                greetingElement.textContent = greeting;
            }
            
            updateGreeting();
            setInterval(updateGreeting, 60000); // Update every minute

            // Add current date
            function updateDate() {
                const dateElement = document.querySelector('.current-date');
                const options = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                };
                const date = new Date().toLocaleDateString('id-ID', options);
                dateElement.textContent = date;
            }

            updateDate();
            // Update date at midnight
            setInterval(() => {
                if (new Date().getHours() === 0) {
                    updateDate();
                }
            }, 60000);

            // Add clock function
            function updateClock() {
                const timeElement = document.querySelector('.current-time');
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                timeElement.textContent = `${hours}:${minutes}:${seconds}`;
            }

            // Update clock every second
            updateClock();
            setInterval(updateClock, 1000);

            // Enhanced hover effects
            const buttons = document.querySelectorAll('.hover-effect');
            buttons.forEach(button => {
                button.addEventListener('mousemove', (e) => {
                    const rect = button.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    button.style.setProperty('--x', `${x}px`);
                    button.style.setProperty('--y', `${y}px`);
                });
            });
        });
    </script>
