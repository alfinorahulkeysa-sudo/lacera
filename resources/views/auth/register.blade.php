@extends('layouts.app')

@section('title', 'Daftar Akun Baru - Lacera Official Store')

@section('content')
<div class="bg-gray-50 min-h-screen">

    {{-- Breadcrumb --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex text-xs text-gray-500 font-medium">
            <a href="{{ route('home') }}" class="hover:text-pink-600 transition">🏠 Beranda</a>
            <span class="mx-2 text-gray-300">&gt;</span>
            <span class="text-gray-400">Akun</span>
            <span class="mx-2 text-gray-300">&gt;</span>
            <span class="text-pink-600 font-bold">Daftar</span>
        </nav>
    </div>

    {{-- Title --}}
    <div class="text-center mb-8 px-4">
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Daftar Akun Baru</h1>
        <p class="text-sm text-gray-500 mt-2">Buat akun baru untuk kemudahan berbelanja dan nikmati berbagai promo menarik.</p>
    </div>

    {{-- Main Content: 2 Columns --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- ================= KOLOM KIRI: FORM REGISTER ================= --}}
            <div class="lg:col-span-7">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Formulir Pendaftaran</h2>
                            <p class="text-xs text-gray-500 mt-1">Lengkapi data diri Anda di bawah ini secara benar.</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-pink-50 flex items-center justify-center text-pink-500 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </div>
                    </div>

                    {{-- Register Errors --}}
                    @if($errors->any())
                        <div class="mb-4 text-xs text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">
                            <ul class="list-disc pl-4 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Lengkap</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </span>
                                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                       placeholder="Masukkan nama lengkap"
                                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-pink-500 focus:ring-2 focus:ring-pink-100 transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       placeholder="customer@gmail.com"
                                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-pink-500 focus:ring-2 focus:ring-pink-100 transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Nomor Telepon</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </span>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                       placeholder="08xxxxxxxxxx"
                                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-pink-500 focus:ring-2 focus:ring-pink-100 transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Kata Sandi</label>
                            <div class="relative" x-data="{ show: false }">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </span>
                                <input :type="show ? 'text' : 'password'" name="password" required
                                       placeholder="•••••••••••"
                                       class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-pink-500 focus:ring-2 focus:ring-pink-100 transition">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Konfirmasi Kata Sandi</label>
                            <div class="relative" x-data="{ show: false }">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </span>
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                                       placeholder="Ulangi kata sandi Anda"
                                       class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-pink-500 focus:ring-2 focus:ring-pink-100 transition">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" name="terms" required class="rounded border-gray-300 text-pink-600 focus:ring-pink-500 w-4 h-4 mt-0.5">
                            <span class="ml-2 text-xs text-gray-600">Saya setuju dengan <a href="#" class="text-pink-600 font-bold hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="text-pink-600 font-bold hover:underline">Kebijakan Privasi</a></span>
                        </div>

                        <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-pink-200 text-sm">
                            Daftar
                        </button>
                    </form>



                    <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                        <p class="text-xs text-gray-500">Sudah punya akun? <a href="{{ route('login') }}" class="text-pink-600 font-bold hover:underline">Masuk di sini</a></p>
                    </div>
                </div>
            </div>

            {{-- ================= KOLOM KANAN: BENEFITS SIDEBAR ================= --}}
            <div class="lg:col-span-5">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                    <h3 class="text-sm font-bold text-gray-900 mb-5">Dengan akun Lacera, kamu bisa:</h3>

                    <div class="space-y-5">
                        <div class="flex gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-500 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-800">Belanja Lebih Cepat</p>
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">Checkout lebih cepat dengan data yang tersimpan</p>
                            </div>
                        </div>

                        <div class="flex gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-500 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-800">Dapatkan Promo Eksklusif</p>
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">Akses promo dan diskon spesial untuk member</p>
                            </div>
                        </div>

                        <div class="flex gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-800">Lacak Pesanan</p>
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">Pantau status pesanan dengan mudah dan cepat</p>
                            </div>
                        </div>

                        <div class="flex gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-400 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-800">Simpan Produk Favorit</p>
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">Simpan produk favorit untuk belanja nanti</p>
                            </div>
                        </div>

                        <div class="flex gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-800">Tulis Review</p>
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">Bagikan pengalamanmu dan dapatkan poin rewards</p>
                            </div>
                        </div>
                    </div>

                    {{-- Product Image Banner --}}
                    <div class="mt-6 rounded-xl overflow-hidden bg-gradient-to-br from-pink-50 to-pink-100 p-4 text-center">
                        <p class="text-[10px] text-pink-600 font-bold uppercase tracking-wider mb-2">Produk Pilihan Lacera</p>
                        <img src="{{ asset('images/banner-login-admin.jpeg') }}" alt="Lacera Products" class="rounded-lg w-full h-36 object-cover shadow-sm">
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Bottom Trust Bar --}}
    <div class="bg-white border-t border-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-50 text-green-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-800">100% Produk Original</p>
                    <p class="text-[11px] text-gray-400">Garansi uang kembali</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-800">Gratis Ongkir</p>
                    <p class="text-[11px] text-gray-400">Seluruh Indonesia</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-800">Pembayaran Aman</p>
                    <p class="text-[11px] text-gray-400">Verifikasi sistem</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-pink-50 text-pink-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-800">Customer Service</p>
                    <p class="text-[11px] text-gray-400">08.00 - 21.00 WIB</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
