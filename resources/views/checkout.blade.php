<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Lacera Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4 text-center fw-bold text-dark">Formulir Checkout Pembayaran</h4>
                        
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('checkout.process') }}" method="POST" id="form-checkout">
                            @csrf

                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label text-secondary">Nama Lengkap Penerima</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                       value="{{ $user->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label text-secondary">Alamat Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ $user->email }}" required>
                            </div>

                            <div class="p-3 bg-light rounded mb-3 border">
                                <h6 class="fw-bold text-dark mb-3">Wilayah & Alamat Pengiriman</h6>
                                
                                <div class="mb-3">
                                    <label for="province_id" class="form-label text-secondary fs-7">Provinsi Tujuan</label>
                                    <select id="province_id" name="province_id" class="form-select" required>
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov['province_id'] }}">{{ $prov['province'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="city_id" class="form-label text-secondary fs-7">Kota / Kabupaten Tujuan</label>
                                    <select id="city_id" name="city_id" class="form-select" required disabled>
                                        <option value="">-- Pilih Kota/Kabupaten --</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="courier" class="form-label text-secondary fs-7">Pilih Kurir Pengiriman</label>
                                    <select id="courier" name="courier" class="form-select" required disabled>
                                        <option value="">-- Pilih Kurir --</option>
                                        <option value="jne">JNE (Jalur Nugraha Ekakurir)</option>
                                        <option value="tiki">TIKI (Titipan Kilat)</option>
                                        <option value="pos">POS Indonesia</option>
                                    </select>
                                </div>

                                <div class="mb-3 d-none" id="shipping_service_container">
                                    <label for="shipping_service" class="form-label text-secondary fs-7">Pilih Layanan & Tarif</label>
                                    <select id="shipping_service" name="shipping_service" class="form-select" disabled>
                                        <option value="">-- Pilih Layanan Tarif --</option>
                                    </select>
                                </div>

                                <div class="mb-1">
                                    <label for="detail_address" class="form-label text-secondary fs-7">Detail Alamat</label>
                                    <textarea id="detail_address" name="detail_address" class="form-control" rows="3" 
                                              placeholder="Nama jalan, RT/RW, nomor rumah, atau patokan lokasi" required></textarea>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="payment_method" class="form-label text-secondary">Pilih Metode Pembayaran</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
                                    <option value="qris">QRIS (All E-Wallet & M-Banking)</option>
                                </select>
                            </div>

                            <div class="p-3 bg-light rounded mb-4 border">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Subtotal Produk</span>
                                    <span class="fw-semibold">Rp{{ number_format($totalHarga, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Ongkos Kirim</span>
                                    <span class="fw-semibold text-success" id="display_ongkir">Rp0</span>
                                </div>
                                <hr>
                                <div class="d-content">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-dark">Total Pembayaran</span>
                                        <span class="fw-bold text-primary fs-5" id="display_total">Rp{{ number_format($totalHarga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="subtotal" value="{{ $totalHarga }}">
                            <input type="hidden" id="shipping_cost" name="shipping_cost" value="0">
                            <input type="hidden" id="total_harga" name="total_harga" value="{{ $totalHarga }}">
                            <input type="hidden" id="total_weight" value="{{ $totalBerat ?? 1000 }}">

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg fw-semibold shadow-sm">
                                    Pesan & Bayar Sekarang
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('cart.index') }}" class="text-decoration-none text-secondary">← Kembali ke Keranjang</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const subtotal = parseInt(document.getElementById('subtotal').value);
        const weight = document.getElementById('total_weight').value;

        // 1. EVENT: Ketika Provinsi dipilih -> Ambil Data Kota
        document.getElementById('province_id').addEventListener('change', function() {
            let provinceId = this.value;
            let citySelect = document.getElementById('city_id');
            let courierSelect = document.getElementById('courier');
            
            citySelect.innerHTML = '<option value="">Memuat data kota/kabupaten...</option>';
            citySelect.disabled = true;
            courierSelect.disabled = true;
            courierSelect.value = "";
            resetOngkir();

            if (provinceId) {
                fetch(`/api/cities/${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        citySelect.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
                        data.forEach(city => {
                            let typePrefix = city.type === 'Kabupaten' ? 'Kab.' : city.type;
                            citySelect.innerHTML += `<option value="${city.city_id}">${typePrefix} ${city.city_name}</option>`;
                        });
                        citySelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        citySelect.innerHTML = '<option value="">Gagal memuat kota.</option>';
                    });
            } else {
                citySelect.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
            }
        });

        // 2. EVENT: Ketika Kota dipilih -> Aktifkan Dropdown Kurir
        document.getElementById('city_id').addEventListener('change', function() {
            let courierSelect = document.getElementById('courier');
            courierSelect.value = "";
            resetOngkir();
            if (this.value) {
                courierSelect.disabled = false;
            } else {
                courierSelect.disabled = true;
            }
        });

        // 3. EVENT: Ketika Kurir dipilih -> Tembak API Hitung Ongkir RajaOngkir
        document.getElementById('courier').addEventListener('change', function() {
            let cityId = document.getElementById('city_id').value;
            let courier = this.value;
            let serviceContainer = document.getElementById('shipping_service_container');
            let serviceSelect = document.getElementById('shipping_service');

            if (!cityId || !courier) return;

            resetOngkir();
            serviceContainer.classList.remove('d-none');
            serviceSelect.disabled = false;
            serviceSelect.required = true;
            serviceSelect.innerHTML = '<option value="">Sedang menghitung ongkir...</option>';

            // Lakukan POST request membawa CSRF Token Laravel agar aman
            fetch('/api/shipping-cost', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    city_id: cityId,
                    weight: weight,
                    courier: courier
                })
            })
            .then(response => response.json())
            .then(data => {
                serviceSelect.innerHTML = '<option value="">-- Pilih Layanan Tarif --</option>';
                
                if(data.length > 0 && data[0].costs.length > 0) {
                    let courierCosts = data[0].costs;
                    courierCosts.forEach(ser => {
                        let costValue = ser.cost[0].value;
                        let etd = ser.cost[0].etd ? `(${ser.cost[0].etd} Hari)` : '';
                        serviceSelect.innerHTML += `<option value="${costValue}">${ser.service} - Rp${costValue.toLocaleString('id-ID')} ${etd}</option>`;
                    });
                } else {
                    serviceSelect.innerHTML = '<option value="">Layanan pengiriman tidak tersedia.</option>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                serviceSelect.innerHTML = '<option value="">Gagal menghitung ongkir.</option>';
            });
        });

        // 4. EVENT: Ketika Layanan/Tarif Ongkir dipilih -> Update Total Belanja Akhir secara Real-time
        document.getElementById('shipping_service').addEventListener('change', function() {
            let ongkirValue = parseInt(this.value) || 0;
            let totalValue = subtotal + ongkirValue;

            // Update UI Tampilan Harga
            document.getElementById('display_ongkir').innerText = 'Rp' + ongkirValue.toLocaleString('id-ID');
            document.getElementById('display_total').innerText = 'Rp' + totalValue.toLocaleString('id-ID');

            // Set nilai ke input hidden untuk dikirimkan ke form backend Laravel -> Paymenku
            document.getElementById('shipping_cost').value = ongkirValue;
            document.getElementById('total_harga').value = totalValue;
        });

        function resetOngkir() {
            document.getElementById('shipping_service_container').classList.add('d-none');
            document.getElementById('display_ongkir').innerText = 'Rp0';
            document.getElementById('display_total').innerText = 'Rp' + subtotal.toLocaleString('id-ID');
            document.getElementById('shipping_cost').value = 0;
            document.getElementById('total_harga').value = subtotal;
            let svc = document.getElementById('shipping_service');
            if (svc) {
                svc.disabled = true;
                svc.required = false;
                svc.value = '';
                svc.innerHTML = '<option value="">-- Pilih Layanan Tarif --</option>';
            }
        }
    </script>
</body>
</html>