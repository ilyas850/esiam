<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politeknik META Industri Cikarang</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Meta.png') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --text-white: #ffffff;
            --text-light: rgba(255, 255, 255, 0.9);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --shadow-light: 0 8px 32px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 12px 40px rgba(0, 0, 0, 0.15);
            --shadow-heavy: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Background with overlay */
        .background-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
        }

        .background-image {
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("images/walpaper welcome page.jpg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            /* Menghilangkan filter brightness agar background asli terlihat */
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.2); /* Overlay yang sangat subtle */
            z-index: -1;
        }

        /* Navigation */
        .navigation {
            position: absolute;
            top: 0;
            right: 0;
            padding: 2rem;
            z-index: 100;
        }

        .nav-button {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            color: var(--text-white);
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: var(--shadow-light);
        }

        .nav-button:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            color: var(--text-white);
            text-decoration: none;
        }

        /* Main container */
        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }

        /* Hero section */
        .hero-section {
            text-align: center;
            max-width: 900px;
            width: 100%;
            position: relative;
        }

        /* Logo container */
        .logo-container {
            margin-bottom: 3rem;
            position: relative;
        }

        .logo-wrapper {
            display: inline-block;
            padding: 1rem;
            margin-bottom: 2rem;
            transition: all 0.4s ease;
            /* Menghilangkan animasi float */
        }

        .logo-wrapper:hover {
            transform: scale(1.05);
        }

        .logo-img {
            width: 120px;
            height: 120px;
            object-fit: contain;
            filter: drop-shadow(0 8px 25px rgba(0, 0, 0, 0.3));
            transition: all 0.3s ease;
        }

        .logo-wrapper:hover .logo-img {
            filter: drop-shadow(0 12px 35px rgba(0, 0, 0, 0.4));
        }

        .logo-fallback {
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: var(--text-white);
            filter: drop-shadow(0 8px 25px rgba(0, 0, 0, 0.3));
            transition: all 0.3s ease;
        }

        .logo-wrapper:hover .logo-fallback {
            filter: drop-shadow(0 12px 35px rgba(0, 0, 0, 0.4));
        }

        /* Typography */
        .main-title {
            font-size: clamp(2.5rem, 8vw, 5rem);
            font-weight: 800;
            color: var(--text-white);
            margin-bottom: 1rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .subtitle {
            font-size: clamp(1.2rem, 4vw, 2rem);
            font-weight: 300;
            color: var(--text-light);
            margin-bottom: 2rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            line-height: 1.3;
        }

        .tagline {
            font-size: clamp(1rem, 2.5vw, 1.3rem);
            color: var(--text-light);
            margin-bottom: 3rem;
            font-weight: 400;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        /* Login section - Simplified */
        .login-section {
            display: flex;
            justify-content: center;
            margin-bottom: 4rem;
        }

        .action-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-white);
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-light);
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-heavy);
            color: var(--text-white);
            text-decoration: none;
        }

        .action-btn.secondary {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
        }

        /* Contact section */
        .contact-section {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: var(--shadow-light);
        }

        .contact-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-white);
            margin-bottom: 1rem;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .contact-item i {
            color: var(--accent-color);
            font-size: 1.1rem;
        }

        /* Floating shapes animation */
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03); /* Sangat subtle */
            animation: floatShapes 25s infinite linear; /* Lebih lambat */
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 5s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 30%;
            left: 20%;
            animation-delay: 10s;
        }

        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            top: 40%;
            right: 30%;
            animation-delay: 15s;
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        @keyframes floatShapes {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navigation {
                padding: 1rem;
            }

            .main-container {
                padding: 1rem;
            }

            .logo-wrapper {
                padding: 0.8rem;
                margin-bottom: 1.5rem;
            }

            .logo-img, .logo-fallback {
                width: 80px;
                height: 80px;
            }

            .logo-fallback {
                font-size: 2.5rem;
            }

            .login-section {
                margin-bottom: 3rem;
            }

            .action-btn {
                width: auto;
                min-width: 200px;
                justify-content: center;
            }

            .contact-info {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .contact-section {
                padding: 1.5rem;
                margin-top: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .navigation {
                padding: 0.5rem;
            }

            .nav-button {
                padding: 10px 20px;
                font-size: 0.85rem;
            }

            .contact-section {
                padding: 1rem;
            }

            .tagline {
                margin-bottom: 2rem;
            }
        }

        /* High-resolution displays */
        @media (min-width: 1400px) {
            .main-container {
                padding: 4rem;
            }

            .hero-section {
                max-width: 1100px;
            }
        }

        /* Landscape mobile orientation */
        @media (max-height: 500px) and (orientation: landscape) {
            .main-container {
                padding: 1rem;
            }

            .logo-wrapper {
                padding: 0.5rem;
                margin-bottom: 1rem;
            }

            .logo-img, .logo-fallback {
                width: 60px;
                height: 60px;
            }

            .main-title {
                margin-bottom: 0.5rem;
            }

            .subtitle {
                margin-bottom: 1rem;
            }

            .tagline {
                margin-bottom: 1.5rem;
            }

            .login-section {
                margin-bottom: 2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Background -->
    <div class="background-container">
        <div class="background-image"></div>
        <div class="background-overlay"></div>
    </div>

    <!-- Floating shapes - Dibuat lebih subtle -->
    <div class="floating-shapes" style="opacity: 0.3;">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Navigation -->
    @if (Route::has('login'))
        <div class="navigation">
            @auth
                <a href="{{ url('/home') }}" class="nav-button">
                    <i class="fas fa-home"></i>
                    Home
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-button">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </a>
                <!-- <a href="{{ route('login') }}" class="action-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </a> -->
                {{-- @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="nav-button" style="margin-left: 1rem;">
                        <i class="fas fa-user-plus"></i>
                        Register
                    </a>
                @endif --}}
            @endauth
        </div>
    @endif

    <!-- Main content -->
    <div class="main-container">
        <div class="hero-section">
            <!-- Logo -->
            <!-- <div class="logo-container">
                <div class="logo-wrapper">
                    <img src="{{ asset('images/Logo Meta.png') }}" 
                         alt="Logo META" 
                         class="logo-img"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="logo-fallback" style="display: none;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
            </div> -->

            <!-- Main content - Simplified -->
            <!-- <h1 class="main-title">eSIAM</h1>
            <h2 class="subtitle">Politeknik META Industri</h2>
            <p class="tagline">
                Elektronik Sistem Informasi Akademik Meta
            </p> -->

            <!-- Login section - Only essential button -->
            <!-- <div class="login-section">
                @auth
                    <a href="{{ url('/home') }}" class="action-btn">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="action-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </a>
                @endauth
            </div> -->
           
            <!-- Contact section -->
            <!-- <div class="contact-section" id="contact">
                <h3 class="contact-title">Contact Information</h3>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>esiam@politeknikmeta.ac.id</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>Contact Us</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Cikarang, Indonesia</span>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</body>

</html>