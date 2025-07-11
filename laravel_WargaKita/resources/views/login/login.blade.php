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

        .logo-icon {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .logo-icon i {
            color: var(--primary);
            font-size: 1.8rem;
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        .form-control {
            height: 50px;
            font-size: 1rem;
            border-radius: 10px;
            border: 1px solid var(--border);
            padding: 0 18px;
            color: #334155;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
            border-color: var(--primary);
        }

        .form-floating>label {
            padding: 0 18px;
            color: var(--secondary);
            font-weight: 400;
            font-size: 0.95rem;
        }

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
            z-index: 10;
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

            .logo-text {
                font-size: 1.6rem;
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
                            <div class="logo-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="logo-text">SiWar</div>
                        </div>
                        <h3>Sistem Informasi Warga</h3>
                        <p>Selamat datang di aplikasi Sistem Informasi Warga</p>
                    </div>

                    <div class="card-body">
                        <h3 class="form-title">Log In</h3>

                        <form method="POST" action="{{ route('login') }}" novalidate class="needs-validation"
                            id="loginForm">
                            @csrf

                            <div class="form-floating mb-3">
                                <input type="text" name="nik" class="form-control" id="floatingNik"
                                    placeholder="NIK" required>
                                <label for="floatingNik">
                                    <i class="bi bi-person-badge me-2"></i>NIK
                                </label>
                                <div class="invalid-feedback">
                                    Harap masukkan NIK.
                                </div>
                            </div>

                            <div class="form-floating mb-3 position-relative">
                                <input type="password" name="password" class="form-control" id="floatingPassword"
                                    placeholder="Password" required minlength="6">
                                <label for="floatingPassword">
                                    <i class="bi bi-lock me-2"></i>Password
                                </label>
                                <button type="button"
                                    class="toggle-password-btn position-absolute top-50 end-0 translate-middle-y me-3"
                                    onclick="togglePassword()">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                                <div class="invalid-feedback">
                                    Password harus minimal 6 karakter.
                                </div>
                            </div>

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

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');

            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
