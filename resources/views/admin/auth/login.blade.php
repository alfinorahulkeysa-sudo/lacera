<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Lacera Official Store</title>
    <!-- Perbaikan: Menghapus satu 'dist/' pada link CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --lacera-pink: #E91E63;
            --lacera-pink-hover: #D81B60;
            --lacera-light: #FFF0F5;
            --text-dark: #333333;
            --text-muted: #888888;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8F9FA;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Top Notification Bar */
        .top-bar {
            background-color: var(--lacera-pink);
            color: white;
            font-size: 0.85rem;
            padding: 8px 0;
            font-weight: 500;
        }

        /* Layout Kiri (Branding) */
        .left-section {
            background-color: white;
            padding: 40px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--lacera-pink);
            margin-bottom: 30px;
        }
        
        .brand-logo span {
            font-weight: 400;
            color: var(--text-muted);
            font-size: 1rem;
            margin-left: 10px;
            border-left: 1px solid #ccc;
            padding-left: 10px;
        }

        .welcome-text h1 {
            font-size: 2.5rem;
            font-weight: 300;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .welcome-text h1 strong {
            font-weight: 700;
            color: var(--lacera-pink);
        }

        .welcome-text p {
            color: var(--text-muted);
            margin-top: 15px;
            font-size: 0.95rem;
            max-width: 400px;
        }

        .banner-image {
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
            border-radius: 10px;
        }

        /* Fitur Badges */
        .feature-badges {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .badge-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid #eee;
            padding: 10px 15px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-dark);
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        .badge-item i {
            color: var(--lacera-pink);
        }

        /* Layout Kanan (Form Login) */
        .right-section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background-color: var(--lacera-light);
            position: relative;
        }

        /* Lingkaran Pink Dekorasi di Belakang Card */
        .decor-circle {
            position: absolute;
            width: 500px;
            height: 500px;
            background: #FFE4EC;
            border-radius: 50%;
            left: -250px;
            z-index: 0;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 15px 40px rgba(233, 30, 99, 0.08);
            z-index: 1;
            position: relative;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .icon-lock-bg {
            background-color: var(--lacera-light);
            color: var(--lacera-pink);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 15px auto;
        }

        .login-header h3 {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .login-header p {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Form Controls */
        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .input-group-text {
            background-color: white;
            border-right: none;
            color: var(--text-muted);
            border-radius: 10px 0 0 10px;
        }

        .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
            padding: 12px 15px;
            font-size: 0.9rem;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 0.25rem rgba(233, 30, 99, 0.15);
            border-radius: 10px;
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: var(--lacera-pink);
        }

        /* Buttons */
        .btn-primary-lacera {
            background-color: var(--lacera-pink);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary-lacera:hover {
            background-color: var(--lacera-pink-hover);
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-lacera {
            background-color: white;
            color: var(--lacera-pink);
            border: 1px solid var(--lacera-pink);
            border-radius: 10px;
            padding: 12px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-outline-lacera:hover {
            background-color: var(--lacera-light);
            color: var(--lacera-pink);
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }

        .divider::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background-color: #eee;
            z-index: 1;
        }

        .divider span {
            background-color: white;
            padding: 0 15px;
            color: var(--text-muted);
            font-size: 0.85rem;
            position: relative;
            z-index: 2;
        }

        .footer-text {
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-muted);
            position: absolute;
            bottom: 20px;
            width: 100%;
            left: 0;
        }

        /* Responsive hiding untuk layar kecil */
        @media (max-width: 991px) {
            .left-section {
                display: none;
            }
            .decor-circle {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="top-bar d-flex justify-content-between px-4">
        <div>
            <i class="fas fa-truck me-2"></i> Gratis Ongkir Seluruh Indonesia
        </div>
        <div>
            <i class="fas fa-bolt me-2"></i> Flash Sale Hingga 70%
        </div>
        <div>
            <a href="/" class="text-white text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Kembali ke Website</a>
        </div>
    </div>

    <div class="container-fluid p-0">
        <div class="row g-0" style="min-height: calc(100vh - 38px);">
            
            <div class="col-lg-7 left-section">
                <div class="brand-logo">
                    LACERA <span>OFFICIAL<br>STORE</span>
                </div>

                <div class="welcome-text">
                    <h1>Selamat Datang di<br><strong>LACERA ADMIN</strong></h1>
                    <p>Kelola produk, pesanan, pelanggan, dan laporan penjualan dengan mudah dalam satu sistem.</p>
                </div>

                <img src="{{ asset('images/banner-login-admin.jpeg') }}" alt="Produk Lacera" class="banner-image">

                <div class="feature-badges">
                    <div class="badge-item">
                        <i class="fas fa-shield-alt"></i> Aman & Terpercaya
                    </div>
                    <div class="badge-item">
                        <i class="fas fa-chart-bar"></i> Data Akurat
                    </div>
                    <div class="badge-item">
                        <i class="fas fa-cog"></i> Mudah Digunakan
                    </div>
                </div>
                
                <div class="footer-text mt-5 position-relative">
                    &copy; 2026 Lacera Official Store. All rights reserved.
                </div>
            </div>

            <div class="col-lg-5 right-section">
                <div class="decor-circle"></div>
                
                <div class="login-card">
                    <div class="login-header">
                        <div class="icon-lock-bg">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h3>Login Admin</h3>
                        <p>Masuk untuk mengakses dashboard admin</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger" style="font-size: 0.85rem;">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.login') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Email Admin</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-user"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="Masukkan email admin" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                                <span class="input-group-text bg-white" style="border-left: none; cursor: pointer; border-radius: 0 10px 10px 0;">
                                    <i class="far fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" style="accent-color: var(--lacera-pink);">
                                <label class="form-check-label text-muted" for="remember" style="font-size: 0.85rem;">
                                    Ingat Saya
                                </label>
                            </div>
                            <a href="#" class="text-decoration-none" style="color: var(--lacera-pink); font-size: 0.85rem; font-weight: 500;">Lupa Password?</a>
                        </div>

                        <button type="submit" class="btn btn-primary-lacera w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i> Masuk Dashboard
                        </button>

                        <div class="divider">
                            <span>atau</span>
                        </div>

                        <a href="/" class="btn btn-outline-lacera w-100 text-center text-decoration-none">
                            <i class="fas fa-globe me-2"></i> Kembali ke Website
                        </a>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Perbaikan: Menghapus satu 'dist/' pada link JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>