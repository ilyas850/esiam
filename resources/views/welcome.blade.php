<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politeknik META Industri Cikarang</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Meta.png') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            overflow-x: hidden;
        }

        /* Navigation */
        .navigation {
            position: fixed;
            top: 0;
            right: 0;
            padding: 2rem;
            z-index: 1000;
        }

        .nav-button {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .nav-button:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Main container */
        .main-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Hero section */
        .hero-section {
            text-align: center;
            margin-bottom: 4rem;
            width: 100%;
        }

        /* Logo section */
        .logo-section {
            margin-bottom: 2rem;
        }

        .logo-wrapper {
            display: inline-block;
            margin-bottom: 0;
            position: relative;
        }

        .logo-img {
            width: 350px;
            height: 150px;
            object-fit: contain;
            filter: drop-shadow(0 4px 20px rgba(0, 0, 0, 0.2));
            transition: all 0.3s ease;
        }

        .logo-img:hover {
            transform: scale(1.02);
            filter: drop-shadow(0 6px 25px rgba(0, 0, 0, 0.3));
        }

        .logo-fallback {
            width: 160px;
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
            color: #ffffff;
            opacity: 0.9;
        }

        /* Typography */
        .main-title {
            font-size: clamp(4rem, 12vw, 8rem);
            font-weight: 900;
            color: #ffffff;
            margin-bottom: 1rem;
            line-height: 0.9;
            letter-spacing: -0.02em;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .subtitle {
            font-size: clamp(1.8rem, 6vw, 3rem);
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            opacity: 0.95;
        }

        .tagline {
            font-size: clamp(1.1rem, 3vw, 1.4rem);
            color: #ffffff;
            margin-bottom: 3rem;
            font-weight: 400;
            line-height: 1.6;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0.9;
        }

        /* Academic info section */
        .academic-info {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 600px;
        }

        .academic-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 1rem;
            text-align: center;
        }

        .academic-desc {
            font-size: 1rem;
            color: #ffffff;
            line-height: 1.6;
            text-align: center;
            opacity: 0.9;
        }

        /* Action section */
        .action-section {
            margin-bottom: 3rem;
        }

        .action-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 18px 36px;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
            color: white;
            text-decoration: none;
        }

        /* Quick access section */
        .quick-access {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            backdrop-filter: blur(10px);
            width: 100%;
        }

        .quick-access-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .access-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .access-item {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 1.5rem 1rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .access-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .access-icon {
            font-size: 2rem;
            color: #ffffff;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .access-label {
            font-size: 0.9rem;
            color: #ffffff;
            font-weight: 500;
            opacity: 0.9;
        }

        /* Contact section */
        .contact-section {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            backdrop-filter: blur(10px);
        }

        .contact-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 2rem;
            text-align: center;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #ffffff;
            font-size: 1rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.15);
            word-break: break-word;
        }

        .contact-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .contact-item i {
            color: #ffffff;
            font-size: 1.3rem;
            min-width: 24px;
            opacity: 0.8;
            flex-shrink: 0;
        }

        .contact-item span {
            flex: 1;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navigation {
                padding: 1rem;
            }

            .main-container {
                padding: 2rem 1rem;
            }

            .hero-section {
                margin-bottom: 3rem;
            }

            .logo-img, .logo-fallback {
                width: 250px;
                height: 120px;
            }

            .logo-fallback {
                font-size: 3.5rem;
            }

            .academic-info {
                padding: 1.5rem;
                margin-bottom: 2rem;
            }

            .academic-title {
                font-size: 1.2rem;
            }

            .academic-desc {
                font-size: 0.95rem;
            }

            .quick-access {
                padding: 1.5rem;
                margin-bottom: 2rem;
            }

            .quick-access-title {
                font-size: 1.3rem;
            }

            .access-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.8rem;
            }

            .access-item {
                padding: 1rem 0.8rem;
            }

            .access-icon {
                font-size: 1.5rem;
            }

            .access-label {
                font-size: 0.8rem;
            }

            .action-btn {
                padding: 16px 28px;
                font-size: 1rem;
            }

            .contact-section {
                padding: 2rem;
            }

            .contact-title {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .contact-item {
                padding: 1.25rem;
                font-size: 0.9rem;
                gap: 12px;
            }

            .contact-item i {
                font-size: 1.2rem;
                min-width: 20px;
            }
        }

        @media (max-width: 480px) {
            .main-container {
                padding: 1.5rem 1rem;
            }

            .navigation {
                padding: 0.5rem;
            }

            .nav-button {
                padding: 10px 16px;
                font-size: 0.85rem;
            }

            .academic-info {
                padding: 1.25rem;
            }

            .quick-access {
                padding: 1.25rem;
            }

            .access-grid {
                grid-template-columns: 1fr;
            }

            .action-btn {
                padding: 14px 24px;
                font-size: 0.95rem;
            }

            .contact-section {
                padding: 1.5rem;
            }

            .contact-item {
                padding: 1rem;
                font-size: 0.85rem;
                gap: 10px;
            }
        }

        /* Animations */
        .hero-section {
            animation: fadeInUp 0.8s ease-out;
        }

        .academic-info {
            animation: fadeInUp 0.8s ease-out 0.1s both;
        }

        .action-section {
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .quick-access {
            animation: fadeInUp 0.8s ease-out 0.3s both;
        }

        .contact-section {
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    @if (Route::has('login'))
        <div class="navigation">
            @auth
                <a href="{{ url('/home') }}" class="nav-button">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-button">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </a>
            @endauth
        </div>
    @endif

    <!-- Main content -->
    <div class="main-container">
        <!-- Hero section -->
        <div class="hero-section">
            <!-- Logo section -->
            <div class="logo-section">
                <div class="logo-wrapper">
                    <!-- <img src="{{ asset('images/Logo Poltek Jernih.png') }}" 
                         alt="Logo META" 
                         class="logo-img"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"> -->
                    <img src="{{ asset('images/Logo Poltek Atas Bawah 1png.png') }}" 
                         alt="Logo META" 
                         class="logo-img"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="logo-fallback" style="display: none;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <h1 class="main-title">eSIAM</h1>
            <h2 class="subtitle">Politeknik META Industri</h2>
            <p class="tagline">
                Elektronik Sistem Informasi Akademik Meta<br>
                Empowering the Future of Industrial Education
            </p>
        </div>

        <!-- Academic info section -->
        <div class="academic-info">
            <h3 class="academic-title">Portal Akademik Terpadu</h3>
            <p class="academic-desc">
                Sistem informasi akademik yang dirancang khusus untuk mendukung kegiatan belajar mengajar, 
                administrasi akademik, dan interaksi antara dosen, mahasiswa, dan tenaga kependidikan.
            </p>
        </div>

        <!-- Action section -->
        <div class="action-section">
            @auth
                <a href="{{ url('/home') }}" class="action-btn">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="action-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk ke Sistem
                </a>
            @endauth
        </div>

        <!-- Quick access section -->
        <div class="quick-access">
            <h3 class="quick-access-title">Akses Cepat</h3>
            <div class="access-grid">
                <div class="access-item">
                    <div class="access-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="access-label">Portal Mahasiswa</div>
                </div>
                <div class="access-item">
                    <div class="access-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="access-label">Portal Dosen</div>
                </div>
                <div class="access-item">
                    <div class="access-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="access-label">Jadwal Kuliah</div>
                </div>
                <div class="access-item">
                    <div class="access-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="access-label">Hasil Studi</div>
                </div>
            </div>
        </div>

        <!-- Contact section -->
        <div class="contact-section">
            <h3 class="contact-title">Informasi Kontak</h3>
            <div class="contact-grid">
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>esiam@politeknikmeta.ac.id</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>Bantuan Teknis</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Cikarang, Indonesia</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>