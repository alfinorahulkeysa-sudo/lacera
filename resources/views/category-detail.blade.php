<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacera Official Store - Kategori {{ $category->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fcfcfc;
            color: #333;
        }
        :root {
            --lacera-pink: #de005d;
            --lacera-light-pink: #fff0f5;
            --lacera-banner-bg: #ffe3ec;
        }
        .text-pink { color: var(--lacera-pink); }
        .bg-pink { background-color: var(--lacera-pink); }
        
        /* Topbar & Header */
        .topbar {
            background-color: var(--lacera-pink);
            color: white;
            font-size: 12px;
            padding: 8px 0;
        }
        .search-bar {
            border-radius: 20px 0 0 20px;
            border: 1px solid #e0e0e0;
            background-color: #f5f5f5;
        }
        .search-bar:focus {
            box-shadow: none;
            border-color: var(--lacera-pink);
            background-color: #fff;
        }
        .search-btn {
            border-radius: 0 20px 20px 0;
            background-color: var(--lacera-pink);
            color: white;
            border: none;
        }
        .search-btn:hover { background-color: #bc004f; color: white; }
        
        /* Navigation */
        .nav-link {
            color: #555;
            font-weight: 500;
            font-size: 14px;
            padding: 8px 16px !important;
        }
        .nav-link:hover { color: var(--lacera-pink); }
        .nav-link.active {
            color: var(--lacera-pink) !important;
            font-weight: 600;
            border-bottom: 3px solid var(--lacera-pink);
        }

        /* Sidebar Kategori */
        .category-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.02);
            border: 1px solid #f0f0f0;
        }
        .category-list .list-group-item {
            border: none;
            padding: 12px 15px;
            font-size: 14px;
            color: #666;
            background: transparent;
            margin-bottom: 4px;
            transition: all 0.2s;
        }
        .category-list .list-group-item:hover {
            color: var(--lacera-pink);
            background-color: #fff5f7;
            border-radius: 10px;
        }
        .category-list .list-group-item.active {
            background-color: #fff0f4;
            color: var(--lacera-pink);
            font-weight: 600;
            border-radius: 10px;
        }
        
        /* Banner Kategori Soft Pastel (Sesuai Mockup) */
        .category-banner {
            background-color: var(--lacera-banner-bg);
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            min-height: 180px;
        }

        /* Card Produk Premium */
        .product-card {
            border: 1px solid #f3f3f3;
            border-radius: 16px;
            transition: all 0.3s ease;
            background: #fff;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(222, 0, 93, 0.06);
            border-color: #ffdae5;
        }
        .img-container {
            background-color: #fafafa;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            position: relative;
        }
        .badge-promo {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--lacera-pink);
            color: white;
            font-size: 11px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 20px;
        }
        .btn-add-cart {
            background-color: var(--lacera-pink);
            color: white;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 500;
            border: none;
            padding: 8px 0;
            transition: all 0.2s;
        }
        .btn-add-cart:hover {
            background-color: #bc004f;
            color: white;
            box-shadow: 0 4px 12px rgba(222, 0, 93, 0.2);
        }

        .user-dropdown .dropdown-toggle::after { display: none; }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <span class="me-3">🏠 Gratis Ongkir Seluruh Indonesia</span>
                <span>⚡ Flash Sale Hingga 70%</span>
            </div>
            <div class="d-flex align-items-center">
                <a href="#" class="text-white text-decoration-none me-3 small">Bantuan</a>
                <a href="#" class="text-white text-decoration-none me-3 small">Lacak Pesanan</a>
                <span class="small fw-bold">ID <i class="fas fa-chevron-down ms-1" style="font-size: 10px;"></i></span>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-white pt-3 pb-3 border-bottom sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ auth()->check() ? route('dashboard') : url('/') }}">
                <span class="fw-bold fs-3 tracking-wide text-pink" style="letter-spacing: 2px;">LACERA</span>
                <span class="text-muted ms-2 ps-2 border-start text-uppercase fw-light" style="font-size: 11px; line-height: 1.2;">Official<br>Store</span>
            </a>

            <form action="#" method="GET" class="d-flex mx-auto" style="width: 45%;">
                <input class="form-control search-bar px-4 py-2 text-sm" type="search" name="search" value="{{ request('search') }}" placeholder="Cari produk Lacera..." aria-label="Search">
                <button class="btn search-btn px-4" type="submit"><i class="fas fa-search"></i></button>
            </form>

            <div class="d-flex align-items-center">
                @auth
                    <div class="dropdown user-dropdown me-4">
                        <a class="text-dark text-decoration-none dropdown-toggle d-flex align-items-center" href="#" role="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="far fa-user-circle fs-4 me-2 text-pink"></i> Halo, {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3" aria-labelledby="userMenu">
                            <li><a class="dropdown-item px-3 py-2 text-dark text-decoration-none d-block small" href="{{ route('profile.edit') }}"><i class="fas fa-cog me-2 text-muted"></i> Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item px-3 py-2 text-danger border-0 bg-transparent w-100 text-start small">
                                        <i class="fas fa-sign-out-alt me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-dark text-decoration-none me-4 small fw-medium">
                        <i class="far fa-user fs-5 me-2"></i> Masuk / Daftar
                    </a>
                @endauth

                <a href="{{ route('cart.index') }}" class="text-dark text-decoration-none position-relative ms-2">
                    <i class="fas fa-shopping-cart fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-pink" style="font-size: 9px; padding: 4px 6px;">
                        @auth
                            {{ \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity') }}
                        @else
                            0
                        @endauth
                    </span>
                </a>
            </div>
        </div>
    </nav>

    <div class="bg-white border-bottom mb-4">
        <div class="container">
            <div class="d-flex justify-content-center py-1">
                <a class="nav-link mx-2" href="{{ auth()->check() ? route('dashboard') : url('/') }}">Beranda</a>
                <a class="nav-link mx-2 active" href="{{ route('category.index') }}">Kategori</a>
                <a class="nav-link mx-2" href="{{ route('promo.index') }}">Promo</a>
                <a class="nav-link mx-2" href="{{ route('best-seller.index') }}">Best Seller</a>
                <a class="nav-link mx-2" href="{{ route('bundle.index') }}">Bundle</a>
                <a class="nav-link mx-2" href="{{ route('review.index') }}">Review</a>
                <a class="nav-link mx-2" href="{{ route('orders.index') }}">Lihat Pesanan</a>
                <a class="nav-link mx-2" href="{{ route('track.index') }}">Lacak Pesanan</a>
            </div>
        </div>
    </div>

    <div class="container my-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4" style="font-size: 13px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted"><i class="fas fa-home"></i> Beranda</a></li>
                <li class="breadcrumb-item text-muted">Kategori</li>
                <li class="breadcrumb-item active text-pink fw-semibold" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-3 pe-lg-4 mb-4">
                <div class="category-box p-3 mb-4">
                    <h6 class="fw-bold mb-3 text-uppercase p-2 pb-1 border-bottom" style="font-size: 12px; letter-spacing: 1px; color:#aaa;">Kategori</h6>
                    <div class="list-group category-list">
                        @php
                            $iconMap = [
                                'sparkles'   => 'fa-solid fa-wand-magic-sparkles',
                                'beaker'     => 'fa-solid fa-flask',
                                'water'      => 'fa-solid fa-droplet',
                                'heart'      => 'fa-solid fa-heart',
                                'face-smile' => 'fa-solid fa-face-smile',
                                'gift'       => 'fa-solid fa-gift',
                            ];
                        @endphp
                        @foreach($categories as $cat)
                            <a href="{{ route('category.show', $cat->slug) }}" class="list-group-item list-group-item-action d-flex align-items-center {{ $category->id == $cat->id ? 'active' : '' }}">
                                <span class="me-3 fs-5 d-flex align-items-center justify-content-center" style="width: 24px;">
                                    <i class="{{ $iconMap[$cat->icon] ?? 'fa-solid fa-star' }}" style="color: var(--lacera-pink);"></i>
                                </span> 
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="category-box p-3">
                    <h6 class="fw-bold mb-3 text-uppercase p-2 pb-1 border-bottom" style="font-size: 12px; letter-spacing: 1px; color:#aaa;">Filter Produk</h6>
                    
                    <div class="mb-4 px-2">
                        <label class="form-label fw-semibold text-muted small mb-1">Harga</label>
                        <input type="range" class="form-range" min="0" max="300000" step="10000" id="customRange1" value="300000">
                        <div class="d-flex justify-content-between small text-muted" style="font-size: 11px;">
                            <span>Rp0</span>
                            <span id="rangeValue" class="fw-bold text-pink">Rp300.000+</span>
                        </div>
                    </div>

                    <div class="mb-2 px-2">
                        <label class="form-label fw-semibold text-muted small mb-1">Rating</label>
                        @for ($i = 5; $i >= 1; $i--)
                            <div class="form-check small mb-1">
                                <input class="form-check-input" type="checkbox" id="rate{{ $i }}" {{ $i == 5 ? 'checked' : '' }}>
                                <label class="form-check-label text-warning" for="rate{{ $i }}">
                                    @for ($j = 1; $j <= 5; $j++)
                                        <i class="{{ $j <= $i ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                    <span class="text-muted ms-1" style="font-size: 11px;">{{ $i == 5 ? '(5)' : 'ke atas' }}</span>
                                </label>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="category-banner p-4 p-md-5 mb-4 d-flex align-items-center shadow-sm position-relative">
                    <div class="ps-2" style="max-width: 60%; z-index: 2;">
                        <h1 class="fw-bold text-uppercase text-pink mb-2" style="letter-spacing: 1px; font-size: 32px;">{{ $category->name }}</h1>
                        <p class="text-muted mb-0 small" style="line-height: 1.6; font-size: 14px;">{{ $category->description ?? 'Kulit sehat, cerah dan terhidrasi setiap hari.' }}</p>
                    </div>
                    <div class="position-absolute end-0 bottom-0 top-0 d-none d-md-block pe-4 my-auto h-100 d-flex align-items-center">
                        <img src="https://images.unsplash.com/photo-1608248597481-496100c80836?w=400&auto=format&fit=crop&q=60" alt="" style="max-height: 140px; border-radius: 12px; mix-blend-mode: multiply;">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                    <div class="text-muted small">
                        Menampilkan <span class="fw-semibold text-dark">{{ $products->count() }}</span> produk
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="text-muted small me-2 text-nowrap">Urutkan:</span>
                        <select class="form-select form-select-sm rounded-3 border-secondary-subtle px-3" style="font-size: 13px; width: 140px;">
                            <option>Terbaru</option>
                            <option>Harga Terendah</option>
                            <option>Harga Tertinggi</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3">
                    @forelse($products as $product)
                        <div class="col-6 col-md-4">
                            <div class="card product-card h-100 p-2 d-flex flex-column justify-content-between">
                                <a href="{{ route('product.show', $product->id) }}" class="text-decoration-none text-dark d-block">
                                    <div class="img-container rounded-4 mb-2">
                                        <img src="{{ \Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" 
                                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400&auto=format&fit=crop&q=60';"
                                             class="img-fluid h-100 object-fit-contain rounded-3" 
                                             alt="{{ $product->name }}">
                                        
                                        @if($loop->first)
                                            <span class="badge-promo">15x Brightening</span>
                                        @elseif($loop->iteration == 2)
                                            <span class="badge-promo bg-danger">45% OFF</span>
                                        @endif
                                    </div>
                                    
                                    <div class="p-2">
                                        <h6 class="text-dark fw-medium mb-1 text-truncate" style="font-size: 14px;" title="{{ $product->name }}">{{ $product->name }}</h6>
                                        <div class="text-warning mb-2" style="font-size: 12px;">
                                            <i class="fas fa-star"></i> <span class="text-muted fw-semibold ms-1">4.9</span> <span class="text-muted fw-light" style="font-size: 11px;">(1.2K)</span>
                                        </div>
                                        <h5 class="text-pink fw-bold mb-2" style="font-size: 16px;">Rp{{ number_format($product->price, 0, ',', '.') }}</h5>
                                    </div>
                                </a>
                                <div class="px-2 pb-2">
                                    <form action="{{ route('cart.store', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-add-cart w-100 py-2"><i class="fas fa-plus me-1"></i> Keranjang</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center my-5 py-5 bg-white rounded-4 border">
                            <i class="fas fa-box-open text-muted fs-1 mb-3"></i>
                            <p class="text-muted mb-0">Belum ada produk terdaftar di kategori ini.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const priceRange = document.getElementById('customRange1');
        const rangeValue = document.getElementById('rangeValue');
        
        priceRange.addEventListener('input', function() {
            const val = parseInt(this.value);
            if (val >= 300000) {
                rangeValue.textContent = 'Rp300.000+';
            } else {
                rangeValue.textContent = 'Rp' + val.toLocaleString('id-ID');
            }
        });
    </script>
</body>
</html>