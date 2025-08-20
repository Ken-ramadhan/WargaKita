<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SiWar - Login Sistem Informasi Warga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #dbeafe;
            --secondary: #64748b;
            --light: #f8fafc;
            --border: #e2e8f0;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --success: #10b981;
        }

        * {
            transition: all 0.2s ease;
        }

        body {
            background: linear-gradient(135deg, #f0f4f8, #e2e8f0);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Inter', sans-serif;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            background: #fff;
            position: relative;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            padding: 30px 25px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: "";
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -80px;
            left: -80px;
        }

        .card-header::after {
            content: "";
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            bottom: -60px;
            right: -60px;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .logo-img {
            width: 150px;
            height: auto;
            filter: brightness(0) invert(1);
        }

        .card-header h3 {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 1.4rem;
            position: relative;
            z-index: 1;
            margin-top: 10px;
        }

        .card-header p {
            font-weight: 300;
            opacity: 0.9;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
            max-width: 350px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .card-body {
            padding: 2.5rem 2rem;
            background: #fff;
        }

        .form-title {
            font-weight: 700;
            margin-bottom: 1.8rem;
            color: #1e293b;
            font-size: 1.6rem;
            text-align: center;
            position: relative;
        }

        .form-title::after {
            content: "";
            position: absolute;
            width: 60px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* --- Perbaikan Styling Input dan Error di sini --- */
        .form-floating .form-control {
            height: 58px;
            font-size: 1rem;
            border-radius: 10px;
            border: 1px solid var(--border);
            padding: 1rem 18px 0.5rem 18px;
            color: #334155;
            transition: all 0.3s ease;
        }

        .form-floating .form-control:focus {
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
            border-color: var(--primary);
        }

        .form-floating>label {
            padding: 1rem 18px;
            color: var(--secondary);
            font-weight: 400;
            font-size: 0.95rem;
        }

        /* Styling untuk input dengan ikon di dalam form-floating */
        .form-floating.has-icon .form-control {
            padding-left: 45px;
        }

        .form-floating.has-icon>label {
            padding-left: 45px;
        }

        .form-floating .input-icon {
            position: absolute;
            top: 50%;
            left: 18px;
            transform: translateY(-50%);
            color: var(--secondary);
            font-size: 1.1rem;
            z-index: 2;
        }

        /* Perbaikan utama untuk pesan error */
        .form-floating .invalid-feedback {
            position: absolute; /* Posisi absolut agar tidak mengganggu layout */
            bottom: -20px; /* Atur posisi di bawah input */
            left: 0;
            width: 100%;
            font-size: 0.8rem; /* Sesuaikan ukuran font */
            color: var(--bs-danger); /* Pastikan warna merah dari Bootstrap */
            z-index: 4; /* Pastikan di atas elemen lain jika ada tumpang tindih */
            padding-left: 18px; /* Sesuaikan padding agar sejajar dengan teks input */
            white-space: nowrap; /* Mencegah teks terlalu panjang memecah baris */
            overflow: hidden; /* Sembunyikan jika terlalu panjang */
            text-overflow: ellipsis; /* Tampilkan elipsis jika tersembunyi */
        }
        /* Akhir perbaikan styling error */

        .btn-primary {
            background: var(--primary);
            border: none;
            padding: 14px;
            font-weight: 600;
            border-radius: 10px;
            font-size: 1.05rem;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
        }

        .form-check-label,
        .forgot-password {
            font-size: .9rem;
            color: var(--secondary);
        }

        .forgot-password {
            text-decoration: none;
            font-weight: 500;
            color: var(--primary);
        }

        .forgot-password:hover {
            text-decoration: underline;
            color: var(--primary-dark);
        }

        .toggle-password-btn {
            background: transparent;
            border: none;
            padding: 0;
            color: var(--secondary);
            z-index: 5; /* Tingkatkan z-index agar selalu di atas label, input, dan pesan error */
            position: absolute;
            top: 50%;
            right: 18px;
            transform: translateY(-50%);
        }

        .toggle-password-btn:hover {
            color: var(--primary);
        }

        .toggle-password-btn:focus {
            box-shadow: none;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
            border-color: var(--primary);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0 25px;
        }

        .signup-link {
            text-align: center;
            font-size: 0.95rem;
            color: var(--secondary);
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .signup-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .features {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            text-align: center;
        }

        .feature-item {
            padding: 0 10px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .feature-text {
            font-size: 0.8rem;
            color: var(--secondary);
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 2rem 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.8rem 1.2rem;
            }

            .form-title {
                font-size: 1.4rem;
            }

            .card-header {
                padding: 25px 20px;
            }

            .logo-img {
                width: 120px;
            }
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            font-size: 0.85rem;
            color: var(--secondary);
        }

        .copyright {
            display: block;
            margin-top: 5px;
            font-size: 0.8rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="logo">
                            <img src="{{ asset('img/logo.png') }}" alt="SiWar Logo" class="logo-img">
                        </div>
                        <h3>Sistem Informasi Warga</h3>
                    </div>

                    <div class="card-body">
                        <h3 class="form-title">Log In</h3>

                        <form method="POST" action="{{ route('login') }}" novalidate class="needs-validation"
                            id="loginForm">
                            @csrf

                            <div class="form-floating mb-4 has-icon"> <input type="text" name="nik"
                                    class="form-control @error('nik') is-invalid @enderror" id="floatingNik"
                                    placeholder="NIK" required value="{{ old('nik') }}">
                                <label for="floatingNik">NIK</label>
                                <i class="bi bi-person-badge input-icon"></i>
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-4 position-relative has-icon"> <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="floatingPassword"
                                    placeholder="Password" required minlength="6">
                                <label for="floatingPassword">Kata Sandi</label>
                                <i class="bi bi-lock input-icon"></i>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <button type="button"
                                    class="toggle-password-btn"
                                    onclick="togglePassword()">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            
                            {{-- Global error for NIK/Password (if any) --}}
                            @if ($errors->has('nik') || $errors->has('password'))
                                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                    NIK atau kata sandi salah.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('floatingPassword');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>