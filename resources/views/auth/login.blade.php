<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Absensi BPOM Kota Kendari">
    <title>Sistem Absensi BPOM Kota Kendari - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* BPOM Official Colors */
            --primary-green: #27ae60;
            --primary-dark-green: #1e8449;
            --secondary-blue: #2980b9;
            --secondary-dark-blue: #1f618d;
            --accent-teal: #16a085;
            --background-gradient: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            --card-background: rgba(255, 255, 255, 0.95);
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --border-color: #e0e6ed;
            --success-green: #27ae60;
            --shadow-lg: 0 20px 60px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--background-gradient);
            color: var(--text-dark);
            overflow-x: hidden;
            padding: 20px;
            position: relative;
        }

        /* Animated Background Elements */
        .background-decoration {
            position: fixed;
            width: 100vw;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .medical-pattern {
            position: absolute;
            opacity: 0.05;
            animation: float 25s infinite ease-in-out;
        }

        .medical-pattern:nth-child(1) {
            width: 400px;
            height: 400px;
            top: -100px;
            left: -100px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="%2327ae60"/><rect x="45" y="30" width="10" height="40" fill="white"/><rect x="30" y="45" width="40" height="10" fill="white"/></svg>');
            background-size: contain;
            animation-delay: 0s;
        }

        .medical-pattern:nth-child(2) {
            width: 350px;
            height: 350px;
            bottom: -80px;
            right: -80px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M30 70 Q50 30 70 70" stroke="%232980b9" stroke-width="8" fill="none"/><circle cx="50" cy="50" r="15" fill="%232980b9"/></svg>');
            background-size: contain;
            animation-delay: -10s;
        }

        .medical-pattern:nth-child(3) {
            width: 300px;
            height: 300px;
            top: 40%;
            right: 5%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect x="25" y="40" width="50" height="30" rx="15" fill="%2316a085"/><circle cx="35" cy="55" r="8" fill="white"/><circle cx="65" cy="55" r="8" fill="white"/></svg>');
            background-size: contain;
            animation-delay: -5s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }

            33% {
                transform: translate(50px, -50px) rotate(120deg) scale(1.1);
            }

            66% {
                transform: translate(-30px, 30px) rotate(240deg) scale(0.9);
            }
        }

        /* Glowing Orbs */
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.3;
            animation: pulse 8s infinite ease-in-out;
        }

        .glow-orb:nth-child(4) {
            width: 500px;
            height: 500px;
            background: var(--primary-green);
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        .glow-orb:nth-child(5) {
            width: 400px;
            height: 400px;
            background: var(--secondary-blue);
            bottom: -100px;
            right: -100px;
            animation-delay: -4s;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.3;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.5;
            }
        }

        .container {
            width: 100%;
            max-width: 1200px;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 0;
            background: white;
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            position: relative;
            z-index: 1;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Left Side - Branding */
        .branding-section {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-blue) 100%);
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .branding-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .logo-container {
            position: relative;
            z-index: 1;
            margin-bottom: 25px;
            display: flex;
            gap: 30px;
            align-items: center;
            justify-content: center;
        }

        .logo-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: logoFloat 3s infinite ease-in-out;
        }

        .logo-wrapper:nth-child(2) {
            animation-delay: -1.5s;
        }

        @keyframes logoFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .logo-wrapper img {
            height: 120px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 8px 20px rgba(0, 0, 0, 0.15));
            transition: transform 0.3s ease;
        }

        .logo-wrapper:hover img {
            transform: scale(1.05);
        }

        .branding-section h2 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            letter-spacing: 0.5px;
        }

        .branding-section .subtitle {
            font-size: 15px;
            line-height: 1.5;
            opacity: 0.95;
            position: relative;
            z-index: 1;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .branding-section .tagline {
            font-size: 19px;
            font-weight: 600;
            opacity: 1;
            position: relative;
            z-index: 1;
            margin-bottom: 35px;
            background: rgba(255, 255, 255, 0.15);
            padding: 12px 24px;
            border-radius: 25px;
            display: inline-block;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Animated Statistics */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 400px;
            margin-top: 10px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .stat-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 5px;
            color: white;
            display: block;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .stat-label {
            font-size: 12px;
            opacity: 0.95;
            font-weight: 500;
            line-height: 1.3;
        }

        .stat-icon {
            margin-bottom: 10px;
            opacity: 0.9;
        }

        /* Counting Animation */
        @keyframes countUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .stat-number {
            animation: countUp 0.8s ease-out;
        }

        /* Right Side - Login Form */
        .login-section {
            padding: 60px 50px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            margin-bottom: 35px;
        }

        .login-header h1 {
            font-size: 30px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .login-header p {
            font-size: 15px;
            color: var(--text-light);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            color: var(--text-light);
            pointer-events: none;
            transition: color 0.3s ease;
            z-index: 1;
        }

        .input-wrapper input {
            width: 100%;
            padding: 16px 18px 16px 55px;
            background: #f8f9fa;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 15px;
            color: var(--text-dark);
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .input-wrapper input::placeholder {
            color: #bdc3c7;
        }

        .input-wrapper input:focus {
            outline: none;
            background: white;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 4px rgba(39, 174, 96, 0.1);
        }

        .input-wrapper input:focus~.input-icon,
        .input-wrapper:focus-within .input-icon {
            color: var(--primary-green);
        }

        .toggle-password {
            position: absolute;
            right: 18px;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-light);
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border-radius: 8px;
            z-index: 2;
        }

        .toggle-password:hover {
            color: var(--primary-green);
            background: rgba(39, 174, 96, 0.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-wrapper input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .checkmark {
            position: relative;
            width: 22px;
            height: 22px;
            background: #f8f9fa;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .checkbox-wrapper:hover .checkmark {
            border-color: var(--primary-green);
            background: rgba(39, 174, 96, 0.05);
        }

        .checkbox-wrapper input:checked~.checkmark {
            background: linear-gradient(135deg, var(--primary-green), var(--accent-teal));
            border-color: transparent;
        }

        .checkmark::after {
            content: '';
            position: absolute;
            display: none;
            left: 7px;
            top: 3px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2.5px 2.5px 0;
            transform: rotate(45deg);
        }

        .checkbox-wrapper input:checked~.checkmark::after {
            display: block;
        }

        .checkbox-label {
            font-size: 14px;
            color: var(--text-dark);
        }

        .forgot-password {
            font-size: 14px;
            color: var(--secondary-blue);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--secondary-dark-blue);
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 17px 24px;
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--accent-teal) 100%);
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.3);
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(39, 174, 96, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .arrow-icon {
            transition: transform 0.3s ease;
        }

        .btn-login:hover .arrow-icon {
            transform: translateX(5px);
        }

        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            font-size: 13px;
            color: var(--text-light);
            margin-top: 20px;
        }

        .security-badge svg {
            color: var(--success-green);
        }

        /* Enhanced Responsive Design */
        @media (max-width: 1100px) {
            .container {
                grid-template-columns: 1fr 1fr;
            }

            .stats-container {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 968px) {
            .container {
                grid-template-columns: 1fr;
                max-width: 550px;
            }

            .branding-section {
                padding: 50px 40px;
            }

            .branding-section h2 {
                font-size: 28px;
            }

            .branding-section .tagline {
                font-size: 17px;
            }

            .stats-container {
                grid-template-columns: repeat(4, 1fr);
                max-width: 100%;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-number {
                font-size: 26px;
            }

            .stat-label {
                font-size: 11px;
            }

            .login-section {
                padding: 45px 40px;
            }

            .login-header h1 {
                font-size: 26px;
            }
        }

        @media (max-width: 640px) {
            body {
                padding: 15px;
            }

            .branding-section {
                padding: 40px 30px;
            }

            .logo-container {
                gap: 20px;
            }

            .logo-wrapper img {
                height: 80px;
            }

            .bpom-logo {
                width: 120px;
                height: 120px;
                padding: 14px;
            }

            .bpom-logo img {
                width: 92px;
                height: 92px;
            }

            .branding-section h2 {
                font-size: 24px;
            }

            .branding-section .subtitle {
                font-size: 14px;
            }

            .branding-section .tagline {
                font-size: 16px;
                padding: 10px 20px;
            }

            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .login-section {
                padding: 35px 28px;
            }

            .login-header h1 {
                font-size: 24px;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
            }

            .input-wrapper input {
                padding: 15px 16px 15px 50px;
            }

            .btn-login {
                padding: 15px 20px;
            }
        }

        @media (max-width: 400px) {
            .branding-section {
                padding: 35px 25px;
            }

            .login-section {
                padding: 30px 22px;
            }

            .stats-container {
                gap: 12px;
            }

            .stat-card {
                padding: 12px;
            }

            .stat-number {
                font-size: 24px;
            }

            .stat-label {
                font-size: 10px;
            }
        }

        /* Loading Animation */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .btn-login.loading::after {
            content: '';
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        .btn-login.loading .arrow-icon {
            display: none;
        }
    </style>
</head>

<body>
    <div class="background-decoration">
        <div class="medical-pattern"></div>
        <div class="medical-pattern"></div>
        <div class="medical-pattern"></div>
        <div class="glow-orb"></div>
        <div class="glow-orb"></div>
    </div>

    <div class="container">
        <!-- Left Side - Branding -->
        <div class="branding-section">
            <div class="logo-container">
                <div class="logo-wrapper">
                    <img src="{{ asset('assets/img/kaiadmin/logo-bpom.png') }}" alt="Logo BPOM">
                </div>
                <div class="logo-wrapper">
                    <img src="{{ asset('assets/img/kaiadmin/Lambang_Kota_Kendari.png') }}" alt="Logo Kota Kendari">
                </div>
            </div>

            <h2>BPOM</h2>
            <p class="subtitle">Kota Kendari</p>
            <div class="tagline">📋 Sistem Absensi BPOM</div>

        </div>

        <!-- Right Side - Login Form -->
        <div class="login-section">
            <div class="login-header">
                <h1>Portal Absensi</h1>
                <p>Silakan masukkan kredensial Anda untuk mengakses sistem absensi</p>
            </div>

            <form id="loginForm" class="login-form" method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                        <label for="email">NIP / Email</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <input type="text" id="email" name="email" placeholder="Masukkan NIP atau email" required
                            autocomplete="username" value="{{ old('email') }}">
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required
                            autocomplete="current-password">
                        @error('password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <button type="button" class="toggle-password" id="togglePassword"
                            aria-label="Toggle password visibility">
                            <svg class="eye-icon" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        <span class="checkbox-label">Ingat saya</span>
                    </label>
                    <a href="#" class="forgot-password">Lupa kata sandi?</a>
                </div>

                <button type="submit" class="btn-login">
                    <span id="btnText">Masuk ke Sistem</span>
                    <svg class="arrow-icon" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M12 5L19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>

                <div class="security-badge">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span>Koneksi aman dengan enkripsi SSL 256-bit</span>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Animated Counter for Statistics
        function animateCounter(element) {
            const target = parseFloat(element.getAttribute('data-target'));
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;
            const isDecimal = target % 1 !== 0;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = isDecimal ? target.toFixed(1) : target;
                    clearInterval(timer);
                } else {
                    element.textContent = isDecimal ? current.toFixed(1) : Math.floor(current);
                }
            }, 16);
        }

        // Start counter animation when page loads
        document.addEventListener('DOMContentLoaded', () => {
            const statNumbers = document.querySelectorAll('.stat-number');
            setTimeout(() => {
                statNumbers.forEach(animateCounter);
            }, 500);
        });

        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Update icon
            const eyeIcon = this.querySelector('.eye-icon');
            if (type === 'text') {
                eyeIcon.innerHTML = `
                    <path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="2" y1="2" x2="22" y2="22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                `;
            } else {
                eyeIcon.innerHTML = `
                    <path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                `;
            }

            // Add animation
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
        });

        // Form submission handler
        const loginForm = document.getElementById('loginForm');
        const btnLogin = document.querySelector('.btn-login');
        const btnText = document.getElementById('btnText');

        loginForm.addEventListener('submit', function (e) {
            // show loading state and allow normal form submission
            try {
                btnText.textContent = 'Memproses...';
                btnLogin.classList.add('loading');
                btnLogin.style.pointerEvents = 'none';
            } catch (err) {
                // ignore if elements missing
            }
        });

        // Input focus animation
        const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');

        inputs.forEach(input => {
            input.addEventListener('focus', function () {
                this.parentElement.style.transform = 'translateY(-2px)';
                this.parentElement.style.transition = 'transform 0.3s ease';
            });

            input.addEventListener('blur', function () {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });

        // Add keyboard navigation
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && document.activeElement.tagName !== 'BUTTON' && document.activeElement.type !== 'submit') {
                e.preventDefault();
                btnLogin.click();
            }
        });

        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // Add hover effect to stat cards
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function () {
                const number = this.querySelector('.stat-number');
                number.style.transform = 'scale(1.1)';
                number.style.transition = 'transform 0.3s ease';
            });

            card.addEventListener('mouseleave', function () {
                const number = this.querySelector('.stat-number');
                number.style.transform = 'scale(1)';
            });
        });
    </script>
</body>

</html>