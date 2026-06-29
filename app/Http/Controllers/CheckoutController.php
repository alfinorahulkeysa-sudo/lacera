<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout dengan mengirimkan data dasar yang dibutuhkan
     */
    public function index()
    {
        $user = auth()->user();
        
        // Mengambil total harga produk dari session (default Rp150.000 jika kosong)
        $totalHarga = session('cart_total', 150000); 
        $totalBerat = session('cart_weight', 1000);

        // Provide a minimal local provinces list so the view doesn't break.
        // This is a fallback for environments where external RajaOngkir API
        // is unavailable. Replace with real API/db lookup as needed.
        $provinces = [
            ['province_id' => '31', 'province' => 'DKI Jakarta'],
            ['province_id' => '32', 'province' => 'Jawa Barat'],
            ['province_id' => '33', 'province' => 'Jawa Tengah'],
            ['province_id' => '34', 'province' => 'DI Yogyakarta'],
            ['province_id' => '35', 'province' => 'Jawa Timur'],
            ['province_id' => '36', 'province' => 'Banten'],
            ['province_id' => '37', 'province' => 'Bali'],
            ['province_id' => '38', 'province' => 'Nusa Tenggara Barat'],
            ['province_id' => '39', 'province' => 'Nusa Tenggara Timur'],
            ['province_id' => '40', 'province' => 'Kalimantan Barat'],
            ['province_id' => '41', 'province' => 'Kalimantan Tengah'],
            ['province_id' => '42', 'province' => 'Kalimantan Selatan'],
            ['province_id' => '43', 'province' => 'Kalimantan Timur'],
            ['province_id' => '44', 'province' => 'Kalimantan Utara'],
            ['province_id' => '45', 'province' => 'Sulawesi Utara'],
            ['province_id' => '46', 'province' => 'Sulawesi Tengah'],
            ['province_id' => '47', 'province' => 'Sulawesi Selatan'],
            ['province_id' => '48', 'province' => 'Sulawesi Tenggara'],
            ['province_id' => '49', 'province' => 'Gorontalo'],
            ['province_id' => '50', 'province' => 'Sulawesi Barat'],
            ['province_id' => '51', 'province' => 'Maluku'],
            ['province_id' => '52', 'province' => 'Maluku Utara'],
            ['province_id' => '53', 'province' => 'Papua'],
            ['province_id' => '54', 'province' => 'Papua Barat'],
        ];

        return view('checkout', compact('user', 'totalHarga', 'totalBerat', 'provinces'));
    }

    /**
     * Return a small sample list of cities for a given province id.
     * Frontend expects JSON array of cities with `city_id`, `city_name`, and `type`.
     */
    public function getCities($province_id)
    {
    $samples = [
            '31' => [
                ['city_id' => '3171', 'city_name' => 'Kota Jakarta Selatan', 'type' => 'Kota'],
                ['city_id' => '3172', 'city_name' => 'Kota Jakarta Timur', 'type' => 'Kota'],
                ['city_id' => '3173', 'city_name' => 'Kota Jakarta Barat', 'type' => 'Kota'],
                ['city_id' => '3174', 'city_name' => 'Kota Jakarta Pusat', 'type' => 'Kota'],
                ['city_id' => '3175', 'city_name' => 'Kota Jakarta Utara', 'type' => 'Kota'],
                ['city_id' => '3176', 'city_name' => 'Kabupaten Kepulauan Seribu', 'type' => 'Kabupaten'],
            ],
            '32' => [
                ['city_id' => '3201', 'city_name' => 'Kabupaten Bogor', 'type' => 'Kabupaten'],
                ['city_id' => '3202', 'city_name' => 'Kabupaten Sukabumi', 'type' => 'Kabupaten'],
                ['city_id' => '3203', 'city_name' => 'Kabupaten Cianjur', 'type' => 'Kabupaten'],
                ['city_id' => '3204', 'city_name' => 'Kabupaten Bandung', 'type' => 'Kabupaten'],
                ['city_id' => '3205', 'city_name' => 'Kabupaten Garut', 'type' => 'Kabupaten'],
                ['city_id' => '3206', 'city_name' => 'Kabupaten Tasikmalaya', 'type' => 'Kabupaten'],
                ['city_id' => '3207', 'city_name' => 'Kabupaten Ciamis', 'type' => 'Kabupaten'],
                ['city_id' => '3208', 'city_name' => 'Kabupaten Kuningan', 'type' => 'Kabupaten'],
                ['city_id' => '3209', 'city_name' => 'Kabupaten Cirebon', 'type' => 'Kabupaten'],
                ['city_id' => '3210', 'city_name' => 'Kabupaten Majalengka', 'type' => 'Kabupaten'],
                ['city_id' => '3211', 'city_name' => 'Kabupaten Sumedang', 'type' => 'Kabupaten'],
                ['city_id' => '3212', 'city_name' => 'Kabupaten Indramayu', 'type' => 'Kabupaten'],
                ['city_id' => '3213', 'city_name' => 'Kabupaten Subang', 'type' => 'Kabupaten'],
                ['city_id' => '3214', 'city_name' => 'Kabupaten Purwakarta', 'type' => 'Kabupaten'],
                ['city_id' => '3215', 'city_name' => 'Kabupaten Karawang', 'type' => 'Kabupaten'],
                ['city_id' => '3216', 'city_name' => 'Kabupaten Bekasi', 'type' => 'Kabupaten'],
                ['city_id' => '3217', 'city_name' => 'Kabupaten Bandung Barat', 'type' => 'Kabupaten'],
                ['city_id' => '3218', 'city_name' => 'Kabupaten Pangandaran', 'type' => 'Kabupaten'],
                ['city_id' => '3271', 'city_name' => 'Kota Bogor', 'type' => 'Kota'],
                ['city_id' => '3272', 'city_name' => 'Kota Sukabumi', 'type' => 'Kota'],
                ['city_id' => '3273', 'city_name' => 'Kota Bandung', 'type' => 'Kota'],
                ['city_id' => '3274', 'city_name' => 'Kota Cirebon', 'type' => 'Kota'],
                ['city_id' => '3275', 'city_name' => 'Kota Bekasi', 'type' => 'Kota'],
                ['city_id' => '3276', 'city_name' => 'Kota Depok', 'type' => 'Kota'],
                ['city_id' => '3277', 'city_name' => 'Kota Cimahi', 'type' => 'Kota'],
                ['city_id' => '3278', 'city_name' => 'Kota Tasikmalaya', 'type' => 'Kota'],
                ['city_id' => '3279', 'city_name' => 'Kota Banjar', 'type' => 'Kota'],
            ],
            '33' => [
                ['city_id' => '3301', 'city_name' => 'Kabupaten Cilacap', 'type' => 'Kabupaten'],
                ['city_id' => '3302', 'city_name' => 'Kabupaten Banyumas', 'type' => 'Kabupaten'],
                ['city_id' => '3303', 'city_name' => 'Kabupaten Purbalingga', 'type' => 'Kabupaten'],
                ['city_id' => '3304', 'city_name' => 'Kabupaten Banjarnegara', 'type' => 'Kabupaten'],
                ['city_id' => '3305', 'city_name' => 'Kabupaten Kebumen', 'type' => 'Kabupaten'],
                ['city_id' => '3306', 'city_name' => 'Kabupaten Purworejo', 'type' => 'Kabupaten'],
                ['city_id' => '3307', 'city_name' => 'Kabupaten Wonosobo', 'type' => 'Kabupaten'],
                ['city_id' => '3308', 'city_name' => 'Kabupaten Magelang', 'type' => 'Kabupaten'],
                ['city_id' => '3309', 'city_name' => 'Kabupaten Boyolali', 'type' => 'Kabupaten'],
                ['city_id' => '3310', 'city_name' => 'Kabupaten Klaten', 'type' => 'Kabupaten'],
                ['city_id' => '3311', 'city_name' => 'Kabupaten Sukoharjo', 'type' => 'Kabupaten'],
                ['city_id' => '3312', 'city_name' => 'Kabupaten Wonogiri', 'type' => 'Kabupaten'],
                ['city_id' => '3313', 'city_name' => 'Kabupaten Karanganyar', 'type' => 'Kabupaten'],
                ['city_id' => '3314', 'city_name' => 'Kabupaten Sragen', 'type' => 'Kabupaten'],
                ['city_id' => '3315', 'city_name' => 'Kabupaten Grobogan', 'type' => 'Kabupaten'],
                ['city_id' => '3316', 'city_name' => 'Kabupaten Blora', 'type' => 'Kabupaten'],
                ['city_id' => '3317', 'city_name' => 'Kabupaten Rembang', 'type' => 'Kabupaten'],
                ['city_id' => '3318', 'city_name' => 'Kabupaten Pati', 'type' => 'Kabupaten'],
                ['city_id' => '3319', 'city_name' => 'Kabupaten Kudus', 'type' => 'Kabupaten'],
                ['city_id' => '3320', 'city_name' => 'Kabupaten Jepara', 'type' => 'Kabupaten'],
                ['city_id' => '3321', 'city_name' => 'Kabupaten Demak', 'type' => 'Kabupaten'],
                ['city_id' => '3322', 'city_name' => 'Kabupaten Semarang', 'type' => 'Kabupaten'],
                ['city_id' => '3323', 'city_name' => 'Kabupaten Temanggung', 'type' => 'Kabupaten'],
                ['city_id' => '3324', 'city_name' => 'Kabupaten Kendal', 'type' => 'Kabupaten'],
                ['city_id' => '3325', 'city_name' => 'Kabupaten Batang', 'type' => 'Kabupaten'],
                ['city_id' => '3326', 'city_name' => 'Kabupaten Pekalongan', 'type' => 'Kabupaten'],
                ['city_id' => '3327', 'city_name' => 'Kabupaten Pemalang', 'type' => 'Kabupaten'],
                ['city_id' => '3328', 'city_name' => 'Kabupaten Tegal', 'type' => 'Kabupaten'],
                ['city_id' => '3329', 'city_name' => 'Kabupaten Brebes', 'type' => 'Kabupaten'],
                ['city_id' => '3371', 'city_name' => 'Kota Magelang', 'type' => 'Kota'],
                ['city_id' => '3372', 'city_name' => 'Kota Surakarta', 'type' => 'Kota'],
                ['city_id' => '3373', 'city_name' => 'Kota Salatiga', 'type' => 'Kota'],
                ['city_id' => '3374', 'city_name' => 'Kota Semarang', 'type' => 'Kota'],
                ['city_id' => '3375', 'city_name' => 'Kota Pekalongan', 'type' => 'Kota'],
                ['city_id' => '3376', 'city_name' => 'Kota Tegal', 'type' => 'Kota'],
            ],
            '34' => [
                ['city_id' => '3401', 'city_name' => 'Kabupaten Kulon Progo', 'type' => 'Kabupaten'],
                ['city_id' => '3402', 'city_name' => 'Kabupaten Bantul', 'type' => 'Kabupaten'],
                ['city_id' => '3403', 'city_name' => 'Kabupaten Gunungkidul', 'type' => 'Kabupaten'],
                ['city_id' => '3404', 'city_name' => 'Kabupaten Sleman', 'type' => 'Kabupaten'],
                ['city_id' => '3471', 'city_name' => 'Kota Yogyakarta', 'type' => 'Kota'],
            ],
            '35' => [
                ['city_id' => '3501', 'city_name' => 'Kabupaten Pacitan', 'type' => 'Kabupaten'],
                ['city_id' => '3502', 'city_name' => 'Kabupaten Ponorogo', 'type' => 'Kabupaten'],
                ['city_id' => '3503', 'city_name' => 'Kabupaten Trenggalek', 'type' => 'Kabupaten'],
                ['city_id' => '3504', 'city_name' => 'Kabupaten Tulungagung', 'type' => 'Kabupaten'],
                ['city_id' => '3505', 'city_name' => 'Kabupaten Blitar', 'type' => 'Kabupaten'],
                ['city_id' => '3506', 'city_name' => 'Kabupaten Kediri', 'type' => 'Kabupaten'],
                ['city_id' => '3507', 'city_name' => 'Kabupaten Malang', 'type' => 'Kabupaten'],
                ['city_id' => '3508', 'city_name' => 'Kabupaten Lumajang', 'type' => 'Kabupaten'],
                ['city_id' => '3509', 'city_name' => 'Kabupaten Jember', 'type' => 'Kabupaten'],
                ['city_id' => '3510', 'city_name' => 'Kabupaten Banyuwangi', 'type' => 'Kabupaten'],
                ['city_id' => '3511', 'city_name' => 'Kabupaten Bondowoso', 'type' => 'Kabupaten'],
                ['city_id' => '3512', 'city_name' => 'Kabupaten Situbondo', 'type' => 'Kabupaten'],
                ['city_id' => '3513', 'city_name' => 'Kabupaten Probolinggo', 'type' => 'Kabupaten'],
                ['city_id' => '3514', 'city_name' => 'Kabupaten Pasuruan', 'type' => 'Kabupaten'],
                ['city_id' => '3515', 'city_name' => 'Kabupaten Sidoarjo', 'type' => 'Kabupaten'],
                ['city_id' => '3516', 'city_name' => 'Kabupaten Mojokerto', 'type' => 'Kabupaten'],
                ['city_id' => '3517', 'city_name' => 'Kabupaten Jombang', 'type' => 'Kabupaten'],
                ['city_id' => '3518', 'city_name' => 'Kabupaten Nganjuk', 'type' => 'Kabupaten'],
                ['city_id' => '3519', 'city_name' => 'Kabupaten Madiun', 'type' => 'Kabupaten'],
                ['city_id' => '3520', 'city_name' => 'Kabupaten Magetan', 'type' => 'Kabupaten'],
                ['city_id' => '3521', 'city_name' => 'Kabupaten Ngawi', 'type' => 'Kabupaten'],
                ['city_id' => '3522', 'city_name' => 'Kabupaten Bojonegoro', 'type' => 'Kabupaten'],
                ['city_id' => '3523', 'city_name' => 'Kabupaten Tuban', 'type' => 'Kabupaten'],
                ['city_id' => '3524', 'city_name' => 'Kabupaten Lamongan', 'type' => 'Kabupaten'],
                ['city_id' => '3525', 'city_name' => 'Kabupaten Gresik', 'type' => 'Kabupaten'],
                ['city_id' => '3526', 'city_name' => 'Kabupaten Bangkalan', 'type' => 'Kabupaten'],
                ['city_id' => '3527', 'city_name' => 'Kabupaten Sampang', 'type' => 'Kabupaten'],
                ['city_id' => '3528', 'city_name' => 'Kabupaten Pamekasan', 'type' => 'Kabupaten'],
                ['city_id' => '3529', 'city_name' => 'Kabupaten Sumenep', 'type' => 'Kabupaten'],
                ['city_id' => '3571', 'city_name' => 'Kota Kediri', 'type' => 'Kota'],
                ['city_id' => '3572', 'city_name' => 'Kota Blitar', 'type' => 'Kota'],
                ['city_id' => '3573', 'city_name' => 'Kota Malang', 'type' => 'Kota'],
                ['city_id' => '3574', 'city_name' => 'Kota Probolinggo', 'type' => 'Kota'],
                ['city_id' => '3575', 'city_name' => 'Kota Pasuruan', 'type' => 'Kota'],
                ['city_id' => '3576', 'city_name' => 'Kota Mojokerto', 'type' => 'Kota'],
                ['city_id' => '3577', 'city_name' => 'Kota Madiun', 'type' => 'Kota'],
                ['city_id' => '3578', 'city_name' => 'Kota Surabaya', 'type' => 'Kota'],
                ['city_id' => '3579', 'city_name' => 'Kota Batu', 'type' => 'Kota'],
            ],
            '36' => [
                ['city_id' => '3601', 'city_name' => 'Kabupaten Pandeglang', 'type' => 'Kabupaten'],
                ['city_id' => '3602', 'city_name' => 'Kabupaten Lebak', 'type' => 'Kabupaten'],
                ['city_id' => '3603', 'city_name' => 'Kabupaten Tangerang', 'type' => 'Kabupaten'],
                ['city_id' => '3604', 'city_name' => 'Kabupaten Serang', 'type' => 'Kabupaten'],
                ['city_id' => '3671', 'city_name' => 'Kota Tangerang', 'type' => 'Kota'],
                ['city_id' => '3672', 'city_name' => 'Kota Cilegon', 'type' => 'Kota'],
                ['city_id' => '3673', 'city_name' => 'Kota Serang', 'type' => 'Kota'],
                ['city_id' => '3674', 'city_name' => 'Kota Tangerang Selatan', 'type' => 'Kota'],
            ],
            '37' => [
                ['city_id' => '5101', 'city_name' => 'Kabupaten Jembrana', 'type' => 'Kabupaten'],
                ['city_id' => '5102', 'city_name' => 'Kabupaten Tabanan', 'type' => 'Kabupaten'],
                ['city_id' => '5103', 'city_name' => 'Kabupaten Badung', 'type' => 'Kabupaten'],
                ['city_id' => '5104', 'city_name' => 'Kabupaten Gianyar', 'type' => 'Kabupaten'],
                ['city_id' => '5105', 'city_name' => 'Kabupaten Klungkung', 'type' => 'Kabupaten'],
                ['city_id' => '5106', 'city_name' => 'Kabupaten Bangli', 'type' => 'Kabupaten'],
                ['city_id' => '5107', 'city_name' => 'Kabupaten Karangasem', 'type' => 'Kabupaten'],
                ['city_id' => '5108', 'city_name' => 'Kabupaten Buleleng', 'type' => 'Kabupaten'],
                ['city_id' => '5171', 'city_name' => 'Kota Denpasar', 'type' => 'Kota'],
            ],
            '38' => [
                ['city_id' => '5201', 'city_name' => 'Kabupaten Lombok Barat', 'type' => 'Kabupaten'],
                ['city_id' => '5202', 'city_name' => 'Kabupaten Lombok Tengah', 'type' => 'Kabupaten'],
                ['city_id' => '5203', 'city_name' => 'Kabupaten Lombok Timur', 'type' => 'Kabupaten'],
                ['city_id' => '5204', 'city_name' => 'Kabupaten Sumbawa', 'type' => 'Kabupaten'],
                ['city_id' => '5205', 'city_name' => 'Kabupaten Dompu', 'type' => 'Kabupaten'],
                ['city_id' => '5206', 'city_name' => 'Kabupaten Bima', 'type' => 'Kabupaten'],
                ['city_id' => '5207', 'city_name' => 'Kabupaten Sumbawa Barat', 'type' => 'Kabupaten'],
                ['city_id' => '5208', 'city_name' => 'Kabupaten Lombok Utara', 'type' => 'Kabupaten'],
                ['city_id' => '5271', 'city_name' => 'Kota Mataram', 'type' => 'Kota'],
                ['city_id' => '5272', 'city_name' => 'Kota Bima', 'type' => 'Kota'],
            ],
            '39' => [
                ['city_id' => '5301', 'city_name' => 'Kabupaten Sumba Barat', 'type' => 'Kabupaten'],
                ['city_id' => '5302', 'city_name' => 'Kabupaten Sumba Timur', 'type' => 'Kabupaten'],
                ['city_id' => '5303', 'city_name' => 'Kabupaten Kupang', 'type' => 'Kabupaten'],
                ['city_id' => '5304', 'city_name' => 'Kabupaten Timor Tengah Selatan', 'type' => 'Kabupaten'],
                ['city_id' => '5305', 'city_name' => 'Kabupaten Timor Tengah Utara', 'type' => 'Kabupaten'],
                ['city_id' => '5306', 'city_name' => 'Kabupaten Belu', 'type' => 'Kabupaten'],
                ['city_id' => '5307', 'city_name' => 'Kabupaten Alor', 'type' => 'Kabupaten'],
                ['city_id' => '5308', 'city_name' => 'Kabupaten Lembata', 'type' => 'Kabupaten'],
                ['city_id' => '5309', 'city_name' => 'Kabupaten Flores Timur', 'type' => 'Kabupaten'],
                ['city_id' => '5310', 'city_name' => 'Kabupaten Sikka', 'type' => 'Kabupaten'],
                ['city_id' => '5311', 'city_name' => 'Kabupaten Ende', 'type' => 'Kabupaten'],
                ['city_id' => '5312', 'city_name' => 'Kabupaten Ngada', 'type' => 'Kabupaten'],
                ['city_id' => '5313', 'city_name' => 'Kabupaten Manggarai', 'type' => 'Kabupaten'],
                ['city_id' => '5314', 'city_name' => 'Kabupaten Rote Ndao', 'type' => 'Kabupaten'],
                ['city_id' => '5315', 'city_name' => 'Kabupaten Manggarai Barat', 'type' => 'Kabupaten'],
                ['city_id' => '5316', 'city_name' => 'Kabupaten Sumba Tengah', 'type' => 'Kabupaten'],
                ['city_id' => '5317', 'city_name' => 'Kabupaten Sumba Barat Daya', 'type' => 'Kabupaten'],
                ['city_id' => '5318', 'city_name' => 'Kabupaten Nagekeo', 'type' => 'Kabupaten'],
                ['city_id' => '5319', 'city_name' => 'Kabupaten Manggarai Timur', 'type' => 'Kabupaten'],
                ['city_id' => '5320', 'city_name' => 'Kabupaten Sabu Raijua', 'type' => 'Kabupaten'],
                ['city_id' => '5321', 'city_name' => 'Kabupaten Malaka', 'type' => 'Kabupaten'],
                ['city_id' => '5371', 'city_name' => 'Kota Kupang', 'type' => 'Kota'],
            ],
            '40' => [
                ['city_id' => '6101', 'city_name' => 'Kabupaten Sambas', 'type' => 'Kabupaten'],
                ['city_id' => '6102', 'city_name' => 'Kabupaten Bengkayang', 'type' => 'Kabupaten'],
                ['city_id' => '6103', 'city_name' => 'Kabupaten Landak', 'type' => 'Kabupaten'],
                ['city_id' => '6104', 'city_name' => 'Kabupaten Mempawah', 'type' => 'Kabupaten'],
                ['city_id' => '6105', 'city_name' => 'Kabupaten Sanggau', 'type' => 'Kabupaten'],
                ['city_id' => '6106', 'city_name' => 'Kabupaten Ketapang', 'type' => 'Kabupaten'],
                ['city_id' => '6107', 'city_name' => 'Kabupaten Sintang', 'type' => 'Kabupaten'],
                ['city_id' => '6108', 'city_name' => 'Kabupaten Kapuas Hulu', 'type' => 'Kabupaten'],
                ['city_id' => '6109', 'city_name' => 'Kabupaten Sekadau', 'type' => 'Kabupaten'],
                ['city_id' => '6110', 'city_name' => 'Kabupaten Melawi', 'type' => 'Kabupaten'],
                ['city_id' => '6111', 'city_name' => 'Kabupaten Kayong Utara', 'type' => 'Kabupaten'],
                ['city_id' => '6112', 'city_name' => 'Kabupaten Kubu Raya', 'type' => 'Kabupaten'],
                ['city_id' => '6171', 'city_name' => 'Kota Pontianak', 'type' => 'Kota'],
                ['city_id' => '6172', 'city_name' => 'Kota Singkawang', 'type' => 'Kota'],
            ],
            '41' => [
                ['city_id' => '6201', 'city_name' => 'Kabupaten Kotawaringin Barat', 'type' => 'Kabupaten'],
                ['city_id' => '6202', 'city_name' => 'Kabupaten Kotawaringin Timur', 'type' => 'Kabupaten'],
                ['city_id' => '6203', 'city_name' => 'Kabupaten Kapuas', 'type' => 'Kabupaten'],
                ['city_id' => '6204', 'city_name' => 'Kabupaten Barito Selatan', 'type' => 'Kabupaten'],
                ['city_id' => '6205', 'city_name' => 'Kabupaten Barito Utara', 'type' => 'Kabupaten'],
                ['city_id' => '6206', 'city_name' => 'Kabupaten Sukamara', 'type' => 'Kabupaten'],
                ['city_id' => '6207', 'city_name' => 'Kabupaten Lamandau', 'type' => 'Kabupaten'],
                ['city_id' => '6208', 'city_name' => 'Kabupaten Seruyan', 'type' => 'Kabupaten'],
                ['city_id' => '6209', 'city_name' => 'Kabupaten Katingan', 'type' => 'Kabupaten'],
                ['city_id' => '6210', 'city_name' => 'Kabupaten Pulang Pisau', 'type' => 'Kabupaten'],
                ['city_id' => '6211', 'city_name' => 'Kabupaten Gunung Mas', 'type' => 'Kabupaten'],
                ['city_id' => '6212', 'city_name' => 'Kabupaten Barito Timur', 'type' => 'Kabupaten'],
                ['city_id' => '6213', 'city_name' => 'Kabupaten Murung Raya', 'type' => 'Kabupaten'],
                ['city_id' => '6271', 'city_name' => 'Kota Palangkaraya', 'type' => 'Kota'],
            ],
            '42' => [
                ['city_id' => '6301', 'city_name' => 'Kabupaten Tanah Laut', 'type' => 'Kabupaten'],
                ['city_id' => '6302', 'city_name' => 'Kabupaten Kotabaru', 'type' => 'Kabupaten'],
                ['city_id' => '6303', 'city_name' => 'Kabupaten Banjar', 'type' => 'Kabupaten'],
                ['city_id' => '6304', 'city_name' => 'Kabupaten Barito Kuala', 'type' => 'Kabupaten'],
                ['city_id' => '6305', 'city_name' => 'Kabupaten Tapin', 'type' => 'Kabupaten'],
                ['city_id' => '6306', 'city_name' => 'Kabupaten Hulu Sungai Selatan', 'type' => 'Kabupaten'],
                ['city_id' => '6307', 'city_name' => 'Kabupaten Hulu Sungai Tengah', 'type' => 'Kabupaten'],
                ['city_id' => '6308', 'city_name' => 'Kabupaten Hulu Sungai Utara', 'type' => 'Kabupaten'],
                ['city_id' => '6309', 'city_name' => 'Kabupaten Tabalong', 'type' => 'Kabupaten'],
                ['city_id' => '6310', 'city_name' => 'Kabupaten Tanah Bumbu', 'type' => 'Kabupaten'],
                ['city_id' => '6311', 'city_name' => 'Kabupaten Balangan', 'type' => 'Kabupaten'],
                ['city_id' => '6371', 'city_name' => 'Kota Banjarmasin', 'type' => 'Kota'],
                ['city_id' => '6372', 'city_name' => 'Kota Banjarbaru', 'type' => 'Kota'],
            ],
            '43' => [
                ['city_id' => '6401', 'city_name' => 'Kabupaten Paser', 'type' => 'Kabupaten'],
                ['city_id' => '6402', 'city_name' => 'Kabupaten Kutai Barat', 'type' => 'Kabupaten'],
                ['city_id' => '6403', 'city_name' => 'Kabupaten Kutai Kartanegara', 'type' => 'Kabupaten'],
                ['city_id' => '6404', 'city_name' => 'Kabupaten Kutai Timur', 'type' => 'Kabupaten'],
                ['city_id' => '6405', 'city_name' => 'Kabupaten Berau', 'type' => 'Kabupaten'],
                ['city_id' => '6406', 'city_name' => 'Kabupaten Penajam Paser Utara', 'type' => 'Kabupaten'],
                ['city_id' => '6407', 'city_name' => 'Kabupaten Mahakam Ulu', 'type' => 'Kabupaten'],
                ['city_id' => '6471', 'city_name' => 'Kota Balikpapan', 'type' => 'Kota'],
                ['city_id' => '6472', 'city_name' => 'Kota Samarinda', 'type' => 'Kota'],
                ['city_id' => '6474', 'city_name' => 'Kota Bontang', 'type' => 'Kota'],
            ],
            '44' => [
                ['city_id' => '6501', 'city_name' => 'Kabupaten Malinau', 'type' => 'Kabupaten'],
                ['city_id' => '6502', 'city_name' => 'Kabupaten Bulungan', 'type' => 'Kabupaten'],
                ['city_id' => '6503', 'city_name' => 'Kabupaten Tana Tidung', 'type' => 'Kabupaten'],
                ['city_id' => '6504', 'city_name' => 'Kabupaten Nunukan', 'type' => 'Kabupaten'],
                ['city_id' => '6571', 'city_name' => 'Kota Tarakan', 'type' => 'Kota'],
            ],
            '45' => [
                ['city_id' => '7101', 'city_name' => 'Kabupaten Bolaang Mongondow', 'type' => 'Kabupaten'],
                ['city_id' => '7102', 'city_name' => 'Kabupaten Minahasa', 'type' => 'Kabupaten'],
                ['city_id' => '7103', 'city_name' => 'Kabupaten Kepulauan Sangihe', 'type' => 'Kabupaten'],
                ['city_id' => '7104', 'city_name' => 'Kabupaten Kepulauan Talaud', 'type' => 'Kabupaten'],
                ['city_id' => '7105', 'city_name' => 'Kabupaten Minahasa Selatan', 'type' => 'Kabupaten'],
                ['city_id' => '7106', 'city_name' => 'Kabupaten Minahasa Utara', 'type' => 'Kabupaten'],
                ['city_id' => '7107', 'city_name' => 'Kabupaten Bolaang Mongondow Utara', 'type' => 'Kabupaten'],
                ['city_id' => '7108', 'city_name' => 'Kabupaten Kepulauan Siau Tagulandang Biaro', 'type' => 'Kabupaten'],
                ['city_id' => '7109', 'city_name' => 'Kabupaten Minahasa Tenggara', 'type' => 'Kabupaten'],
                ['city_id' => '7110', 'city_name' => 'Kabupaten Bolaang Mongondow Selatan', 'type' => 'Kabupaten'],
                ['city_id' => '7111', 'city_name' => 'Kabupaten Bolaang Mongondow Timur', 'type' => 'Kabupaten'],
                ['city_id' => '7171', 'city_name' => 'Kota Manado', 'type' => 'Kota'],
                ['city_id' => '7172', 'city_name' => 'Kota Bitung', 'type' => 'Kota'],
                ['city_id' => '7173', 'city_name' => 'Kota Tomohon', 'type' => 'Kota'],
                ['city_id' => '7174', 'city_name' => 'Kota Kotamobagu', 'type' => 'Kota'],
            ],
            '46' => [
                ['city_id' => '7201', 'city_name' => 'Kabupaten Banggai Kepulauan', 'type' => 'Kabupaten'],
                ['city_id' => '7202', 'city_name' => 'Kabupaten Banggai', 'type' => 'Kabupaten'],
                ['city_id' => '7203', 'city_name' => 'Kabupaten Morowali', 'type' => 'Kabupaten'],
                ['city_id' => '7204', 'city_name' => 'Kabupaten Poso', 'type' => 'Kabupaten'],
                ['city_id' => '7205', 'city_name' => 'Kabupaten Donggala', 'type' => 'Kabupaten'],
                ['city_id' => '7206', 'city_name' => 'Kabupaten Toli-Toli', 'type' => 'Kabupaten'],
                ['city_id' => '7207', 'city_name' => 'Kabupaten Buol', 'type' => 'Kabupaten'],
                ['city_id' => '7208', 'city_name' => 'Kabupaten Parigi Moutong', 'type' => 'Kabupaten'],
                ['city_id' => '7209', 'city_name' => 'Kabupaten Tojo Una-Una', 'type' => 'Kabupaten'],
                ['city_id' => '7210', 'city_name' => 'Kabupaten Sigi', 'type' => 'Kabupaten'],
                ['city_id' => '7211', 'city_name' => 'Kabupaten Banggai Laut', 'type' => 'Kabupaten'],
                ['city_id' => '7212', 'city_name' => 'Kabupaten Morowali Utara', 'type' => 'Kabupaten'],
                ['city_id' => '7271', 'city_name' => 'Kota Palu', 'type' => 'Kota'],
            ],
            '47' => [
                ['city_id' => '7301', 'city_name' => 'Kabupaten Kepulauan Selayar', 'type' => 'Kabupaten'],
                ['city_id' => '7302', 'city_name' => 'Kabupaten Bulukumba', 'type' => 'Kabupaten'],
                ['city_id' => '7303', 'city_name' => 'Kabupaten Bantaeng', 'type' => 'Kabupaten'],
                ['city_id' => '7304', 'city_name' => 'Kabupaten Jeneponto', 'type' => 'Kabupaten'],
                ['city_id' => '7305', 'city_name' => 'Kabupaten Takalar', 'type' => 'Kabupaten'],
                ['city_id' => '7306', 'city_name' => 'Kabupaten Gowa', 'type' => 'Kabupaten'],
                ['city_id' => '7307', 'city_name' => 'Kabupaten Sinjai', 'type' => 'Kabupaten'],
                ['city_id' => '7308', 'city_name' => 'Kabupaten Maros', 'type' => 'Kabupaten'],
                ['city_id' => '7309', 'city_name' => 'Kabupaten Pangkajene dan Kepulauan', 'type' => 'Kabupaten'],
                ['city_id' => '7310', 'city_name' => 'Kabupaten Barru', 'type' => 'Kabupaten'],
                ['city_id' => '7311', 'city_name' => 'Kabupaten Bone', 'type' => 'Kabupaten'],
                ['city_id' => '7312', 'city_name' => 'Kabupaten Soppeng', 'type' => 'Kabupaten'],
                ['city_id' => '7313', 'city_name' => 'Kabupaten Wajo', 'type' => 'Kabupaten'],
                ['city_id' => '7314', 'city_name' => 'Kabupaten Sidenreng Rappang', 'type' => 'Kabupaten'],
                ['city_id' => '7315', 'city_name' => 'Kabupaten Pinrang', 'type' => 'Kabupaten'],
                ['city_id' => '7316', 'city_name' => 'Kabupaten Enrekang', 'type' => 'Kabupaten'],
                ['city_id' => '7317', 'city_name' => 'Kabupaten Luwu', 'type' => 'Kabupaten'],
                ['city_id' => '7318', 'city_name' => 'Kabupaten Tana Toraja', 'type' => 'Kabupaten'],
                ['city_id' => '7322', 'city_name' => 'Kabupaten Luwu Utara', 'type' => 'Kabupaten'],
                ['city_id' => '7325', 'city_name' => 'Kabupaten Luwu Timur', 'type' => 'Kabupaten'],
                ['city_id' => '7326', 'city_name' => 'Kabupaten Toraja Utara', 'type' => 'Kabupaten'],
                ['city_id' => '7371', 'city_name' => 'Kota Makassar', 'type' => 'Kota'],
                ['city_id' => '7372', 'city_name' => 'Kota Parepare', 'type' => 'Kota'],
                ['city_id' => '7373', 'city_name' => 'Kota Palopo', 'type' => 'Kota'],
            ],
            '48' => [
                ['city_id' => '7401', 'city_name' => 'Kabupaten Buton', 'type' => 'Kabupaten'],
                ['city_id' => '7402', 'city_name' => 'Kabupaten Muna', 'type' => 'Kabupaten'],
                ['city_id' => '7403', 'city_name' => 'Kabupaten Konawe', 'type' => 'Kabupaten'],
                ['city_id' => '7404', 'city_name' => 'Kabupaten Kolaka', 'type' => 'Kabupaten'],
                ['city_id' => '7405', 'city_name' => 'Kabupaten Konawe Selatan', 'type' => 'Kabupaten'],
                ['city_id' => '7406', 'city_name' => 'Kabupaten Bombana', 'type' => 'Kabupaten'],
                ['city_id' => '7407', 'city_name' => 'Kabupaten Wakatobi', 'type' => 'Kabupaten'],
                ['city_id' => '7408', 'city_name' => 'Kabupaten Kolaka Utara', 'type' => 'Kabupaten'],
                ['city_id' => '7409', 'city_name' => 'Kabupaten Buton Utara', 'type' => 'Kabupaten'],
                ['city_id' => '7410', 'city_name' => 'Kabupaten Konawe Utara', 'type' => 'Kabupaten'],
                ['city_id' => '7411', 'city_name' => 'Kabupaten Kolaka Timur', 'type' => 'Kabupaten'],
                ['city_id' => '7412', 'city_name' => 'Kabupaten Konawe Kepulauan', 'type' => 'Kabupaten'],
                ['city_id' => '7413', 'city_name' => 'Kabupaten Muna Barat', 'type' => 'Kabupaten'],
                ['city_id' => '7414', 'city_name' => 'Kabupaten Buton Tengah', 'type' => 'Kabupaten'],
                ['city_id' => '7415', 'city_name' => 'Kabupaten Buton Selatan', 'type' => 'Kabupaten'],
                ['city_id' => '7471', 'city_name' => 'Kota Kendari', 'type' => 'Kota'],
                ['city_id' => '7472', 'city_name' => 'Kota Bau-Bau', 'type' => 'Kota'],
            ],
            '49' => [
                ['city_id' => '7501', 'city_name' => 'Kabupaten Boalemo', 'type' => 'Kabupaten'],
                ['city_id' => '7502', 'city_name' => 'Kabupaten Gorontalo', 'type' => 'Kabupaten'],
                ['city_id' => '7503', 'city_name' => 'Kabupaten Pohuwato', 'type' => 'Kabupaten'],
                ['city_id' => '7504', 'city_name' => 'Kabupaten Bone Bolango', 'type' => 'Kabupaten'],
                ['city_id' => '7505', 'city_name' => 'Kabupaten Gorontalo Utara', 'type' => 'Kabupaten'],
                ['city_id' => '7571', 'city_name' => 'Kota Gorontalo', 'type' => 'Kota'],
            ],
            '50' => [
                ['city_id' => '7601', 'city_name' => 'Kabupaten Majene', 'type' => 'Kabupaten'],
                ['city_id' => '7602', 'city_name' => 'Kabupaten Polewali Mandar', 'type' => 'Kabupaten'],
                ['city_id' => '7603', 'city_name' => 'Kabupaten Mamasa', 'type' => 'Kabupaten'],
                ['city_id' => '7604', 'city_name' => 'Kabupaten Mamuju', 'type' => 'Kabupaten'],
                ['city_id' => '7605', 'city_name' => 'Kabupaten Mamuju Utara', 'type' => 'Kabupaten'],
                ['city_id' => '7606', 'city_name' => 'Kabupaten Mamuju Tengah', 'type' => 'Kabupaten'],
            ],
            '51' => [
                ['city_id' => '8101', 'city_name' => 'Kabupaten Maluku Tengah', 'type' => 'Kabupaten'],
                ['city_id' => '8102', 'city_name' => 'Kabupaten Maluku Tenggara', 'type' => 'Kabupaten'],
                ['city_id' => '8103', 'city_name' => 'Kabupaten Maluku Tenggara Barat', 'type' => 'Kabupaten'],
                ['city_id' => '8104', 'city_name' => 'Kabupaten Buru', 'type' => 'Kabupaten'],
                ['city_id' => '8105', 'city_name' => 'Kabupaten Kepulauan Aru', 'type' => 'Kabupaten'],
                ['city_id' => '8106', 'city_name' => 'Kabupaten Seram Bagian Barat', 'type' => 'Kabupaten'],
                ['city_id' => '8107', 'city_name' => 'Kabupaten Seram Bagian Timur', 'type' => 'Kabupaten'],
                ['city_id' => '8108', 'city_name' => 'Kabupaten Maluku Barat Daya', 'type' => 'Kabupaten'],
                ['city_id' => '8109', 'city_name' => 'Kabupaten Buru Selatan', 'type' => 'Kabupaten'],
                ['city_id' => '8171', 'city_name' => 'Kota Ambon', 'type' => 'Kota'],
                ['city_id' => '8172', 'city_name' => 'Kota Tual', 'type' => 'Kota'],
            ],
            '52' => [
                ['city_id' => '8201', 'city_name' => 'Kabupaten Halmahera Barat', 'type' => 'Kabupaten'],
                ['city_id' => '8202', 'city_name' => 'Kabupaten Halmahera Tengah', 'type' => 'Kabupaten'],
                ['city_id' => '8203', 'city_name' => 'Kabupaten Kepulauan Sula', 'type' => 'Kabupaten'],
                ['city_id' => '8204', 'city_name' => 'Kabupaten Halmahera Selatan', 'type' => 'Kabupaten'],
                ['city_id' => '8205', 'city_name' => 'Kabupaten Halmahera Utara', 'type' => 'Kabupaten'],
                ['city_id' => '8206', 'city_name' => 'Kabupaten Halmahera Timur', 'type' => 'Kabupaten'],
                ['city_id' => '8207', 'city_name' => 'Kabupaten Pulau Morotai', 'type' => 'Kabupaten'],
                ['city_id' => '8208', 'city_name' => 'Kabupaten Pulau Taliabu', 'type' => 'Kabupaten'],
                ['city_id' => '8271', 'city_name' => 'Kota Ternate', 'type' => 'Kota'],
                ['city_id' => '8272', 'city_name' => 'Kota Tidore Kepulauan', 'type' => 'Kota'],
            ],
            '53' => [
                ['city_id' => '9101', 'city_name' => 'Kabupaten Merauke', 'type' => 'Kabupaten'],
                ['city_id' => '9102', 'city_name' => 'Kabupaten Jayawijaya', 'type' => 'Kabupaten'],
                ['city_id' => '9103', 'city_name' => 'Kabupaten Jayapura', 'type' => 'Kabupaten'],
                ['city_id' => '9104', 'city_name' => 'Kabupaten Nabire', 'type' => 'Kabupaten'],
                ['city_id' => '9105', 'city_name' => 'Kabupaten Kepulauan Yapen', 'type' => 'Kabupaten'],
                ['city_id' => '9106', 'city_name' => 'Kabupaten Biak Numfor', 'type' => 'Kabupaten'],
                ['city_id' => '9107', 'city_name' => 'Kabupaten Puncak Jaya', 'type' => 'Kabupaten'],
                ['city_id' => '9108', 'city_name' => 'Kabupaten Paniai', 'type' => 'Kabupaten'],
                ['city_id' => '9109', 'city_name' => 'Kabupaten Mimika', 'type' => 'Kabupaten'],
                ['city_id' => '9110', 'city_name' => 'Kabupaten Sarmi', 'type' => 'Kabupaten'],
                ['city_id' => '9111', 'city_name' => 'Kabupaten Keerom', 'type' => 'Kabupaten'],
                ['city_id' => '9112', 'city_name' => 'Kabupaten Pegunungan Bintang', 'type' => 'Kabupaten'],
                ['city_id' => '9113', 'city_name' => 'Kabupaten Yahukimo', 'type' => 'Kabupaten'],
                ['city_id' => '9114', 'city_name' => 'Kabupaten Tolikara', 'type' => 'Kabupaten'],
                ['city_id' => '9115', 'city_name' => 'Kabupaten Waropen', 'type' => 'Kabupaten'],
                ['city_id' => '9116', 'city_name' => 'Kabupaten Supiori', 'type' => 'Kabupaten'],
                ['city_id' => '9117', 'city_name' => 'Kabupaten Mamberamo Raya', 'type' => 'Kabupaten'],
                ['city_id' => '9118', 'city_name' => 'Kabupaten Nduga', 'type' => 'Kabupaten'],
                ['city_id' => '9119', 'city_name' => 'Kabupaten Lanny Jaya', 'type' => 'Kabupaten'],
                ['city_id' => '9120', 'city_name' => 'Kabupaten Mamberamo Tengah', 'type' => 'Kabupaten'],
                ['city_id' => '9121', 'city_name' => 'Kabupaten Yalimo', 'type' => 'Kabupaten'],
                ['city_id' => '9122', 'city_name' => 'Kabupaten Puncak', 'type' => 'Kabupaten'],
                ['city_id' => '9123', 'city_name' => 'Kabupaten Dogiyai', 'type' => 'Kabupaten'],
                ['city_id' => '9124', 'city_name' => 'Kabupaten Intan Jaya', 'type' => 'Kabupaten'],
                ['city_id' => '9125', 'city_name' => 'Kabupaten Deiyai', 'type' => 'Kabupaten'],
                ['city_id' => '9171', 'city_name' => 'Kota Jayapura', 'type' => 'Kota'],
            ],
            '54' => [
                ['city_id' => '9201', 'city_name' => 'Kabupaten Sorong', 'type' => 'Kabupaten'],
                ['city_id' => '9202', 'city_name' => 'Kabupaten Sorong Selatan', 'type' => 'Kabupaten'],
                ['city_id' => '9203', 'city_name' => 'Kabupaten Raja Ampat', 'type' => 'Kabupaten'],
                ['city_id' => '9204', 'city_name' => 'Kabupaten Tambrauw', 'type' => 'Kabupaten'],
                ['city_id' => '9205', 'city_name' => 'Kabupaten Maybrat', 'type' => 'Kabupaten'],
                ['city_id' => '9206', 'city_name' => 'Kabupaten Manokwari', 'type' => 'Kabupaten'],
                ['city_id' => '9207', 'city_name' => 'Kabupaten Manokwari Selatan', 'type' => 'Kabupaten'],
                ['city_id' => '9208', 'city_name' => 'Kabupaten Pegunungan Arfak', 'type' => 'Kabupaten'],
                ['city_id' => '9209', 'city_name' => 'Kabupaten Teluk Bintuni', 'type' => 'Kabupaten'],
                ['city_id' => '9210', 'city_name' => 'Kabupaten Teluk Wondama', 'type' => 'Kabupaten'],
                ['city_id' => '9211', 'city_name' => 'Kabupaten Kaimana', 'type' => 'Kabupaten'],
                ['city_id' => '9212', 'city_name' => 'Kabupaten Fakfak', 'type' => 'Kabupaten'],
                ['city_id' => '9271', 'city_name' => 'Kota Sorong', 'type' => 'Kota'],
            ],
        ];
        return response()->json($samples[$province_id] ?? []);
    }

    /**
     * Return a mock shipping-cost structure compatible with the frontend.
     */
    public function getShippingCost(Request $request)
    {
        $data = $request->validate([
            'city_id' => 'required|string',
            'weight'  => 'required|numeric',
            'courier' => 'required|string',
        ]);

        // Simple mocked costs — replace with real courier API calls when available.
        $base = 10000; // base cost
        $perKg = 5000; // per kg
        $kg = max(1, ceil($data['weight'] / 1000));
        $costValue = $base + ($perKg * $kg);

        $response = [
            [
                'code' => $data['courier'],
                'name' => strtoupper($data['courier']),
                'costs' => [
                    [
                        'service' => 'REG',
                        'cost' => [
                            ['value' => $costValue, 'etd' => '2-3']
                        ]
                    ],
                    [
                        'service' => 'YES',
                        'cost' => [
                            ['value' => $costValue + 15000, 'etd' => '1-1']
                        ]
                    ]
                ]
            ]
        ];

        return response()->json($response);
    }

    /**
     * Memproses data checkout dan mengirimkan data transaksi ke Gateway Paymenku
     */
    public function processCheckout(Request $request)
    {
        // Validasi disesuaikan untuk pengiriman manual (menggunakan teks biasa, bukan ID API)
        $request->validate([
            'nama_lengkap'   => 'required|string',
            'email'          => 'required|email',
            'province_id'    => 'required|string', // match form field names
            'city_id'        => 'required|string', // match form field names
            'detail_address' => 'required|string',
            'shipping_cost'  => 'required|numeric',   // Ongkir manual yang dikirim dari form/session
            'total_harga'    => 'required|numeric', 
            'payment_method' => 'required|string', 
        ]);

        $orderId = 'LACERA-' . time(); 
        $totalHarga = $request->total_harga; 

        $apiKey = env('PAYMENKU_API_KEY');
        $endpointUrl = 'https://paymenku.com/api/v1/transaction/create'; 

        // Proses payload ke Paymenku tetap dipertahankan karena sudah sukses
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ])->post($endpointUrl, [
            'reference_id'   => $orderId,
            'amount'         => $totalHarga,
            'customer_name'  => $request->nama_lengkap,
            'customer_email' => $request->email,
            'channel_code'   => $request->payment_method, 
            'return_url'     => url('/'),
        ]);

        if ($response->successful()) {
            $result = $response->json();
            $paymentUrl = $result['data']['pay_url'] ?? null;

            if ($paymentUrl) {
                // Simpan order lokal sebelum redirect ke pembayaran
                DB::transaction(function () use ($request, $orderId, $totalHarga, $paymentUrl) {
                    $user = Auth::user();

                    $orderData = [
                        'user_id' => $user->id,
                        'order_number' => $orderId,
                        'total_price' => $totalHarga,
                        'shipping_cost' => $request->shipping_cost,
                        'courier' => $request->payment_method,
                        'status' => 'pending',
                        'proof_of_payment' => null,
                        'shipping_receipt' => null,
                    ];

                    if (Schema::hasColumn('orders', 'nama_lengkap')) {
                        $orderData['nama_lengkap'] = $request->nama_lengkap;
                    }
                    if (Schema::hasColumn('orders', 'email')) {
                        $orderData['email'] = $request->email;
                    }
                    if (Schema::hasColumn('orders', 'provinsi')) {
                        $orderData['provinsi'] = $request->province_id;
                    }
                    if (Schema::hasColumn('orders', 'kota')) {
                        $orderData['kota'] = $request->city_id;
                    }
                    if (Schema::hasColumn('orders', 'detail_address')) {
                        $orderData['detail_address'] = $request->detail_address;
                    }
                    if (Schema::hasColumn('orders', 'payment_method')) {
                        $orderData['payment_method'] = $request->payment_method;
                    }
                    if (Schema::hasColumn('orders', 'payment_reference')) {
                        $orderData['payment_reference'] = $orderId;
                    }
                    if (Schema::hasColumn('orders', 'payment_url')) {
                        $orderData['payment_url'] = $paymentUrl;
                    }

                    $order = Order::create($orderData);

                    $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
                    foreach ($cartItems as $item) {
                        OrderItem::create([
                            'order_id'     => $order->id,
                            'product_id'   => $item->product_id,
                            'product_name' => $item->product->name ?? 'Produk',
                            'quantity'     => $item->quantity,
                            'price'        => $item->product->price,
                        ]);
                    }
                });

                return redirect()->away($paymentUrl);
            }
        }

        return back()->with('error', 'Gagal memproses pembayaran ke Paymenku. Keterangan: ' . $response->body());
    }

    /**
     * Menangani callback status pembayaran dari Paymenku.
     * Paymenku mengirim POST dengan JSON payload berisi status transaksi.
     */
    public function handleCallback(Request $request)
    {
        // Log payload mentah untuk debugging
        \Log::info('Paymenku Callback Received', [
            'payload' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        // ── 1. Verifikasi Signature (opsional tapi disarankan) ──
        $webhookSecret = env('PAYMENKU_WEBHOOK_SECRET');
        $signature     = $request->header('X-Signature') ?? $request->header('x-signature');

        if ($webhookSecret && $signature) {
            $rawBody        = file_get_contents('php://input');
            $expectedHash   = hash_hmac('sha256', $rawBody, $webhookSecret);

            if (!hash_equals($expectedHash, $signature)) {
                \Log::warning('Paymenku Callback: signature mismatch', [
                    'expected' => $expectedHash,
                    'received' => $signature,
                ]);
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
            }
        }

        // ── 2. Ambil data dari payload ──
        // Paymenku biasanya mengirim reference_id dan status.
        // Kita coba ambil dari nested "data" dulu, lalu dari root payload.
        $data        = $request->input('data', []);
        $referenceId = $data['reference_id'] ?? $request->input('reference_id');
        $status      = $data['status']       ?? $request->input('status');

        if (!$referenceId) {
            \Log::warning('Paymenku Callback: missing reference_id', $request->all());
            return response()->json(['status' => 'error', 'message' => 'Missing reference_id'], 400);
        }

        // ── 3. Cari order berdasarkan order_number (= reference_id saat create) ──
        $order = Order::where('order_number', $referenceId)->first();

        if (!$order) {
            // Coba cari via payment_reference sebagai fallback
            $order = Order::where('payment_reference', $referenceId)->first();
        }

        if (!$order) {
            \Log::warning('Paymenku Callback: order not found', ['reference_id' => $referenceId]);
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        // ── 4. Update status berdasarkan callback ──
        $paymentStatus = strtolower($status ?? '');

        \Log::info('Paymenku Callback: processing', [
            'order_id'       => $order->id,
            'order_number'   => $order->order_number,
            'current_status' => $order->status,
            'payment_status' => $paymentStatus,
        ]);

        // Mapping status Paymenku ke status internal
        if (in_array($paymentStatus, ['paid', 'success', 'succeeded', 'settlement', 'completed'])) {
            $order->status  = 'paid';
            $order->paid_at = now();
            $order->save();

            \Log::info('Paymenku Callback: order marked as paid', ['order_id' => $order->id]);

        } elseif (in_array($paymentStatus, ['expired', 'failed', 'cancelled', 'denied'])) {
            $order->status = 'failed';
            $order->save();

            \Log::info('Paymenku Callback: order marked as failed', ['order_id' => $order->id]);
        }
        // Status lainnya (pending, processing) → tidak mengubah apa-apa

        return response()->json(['status' => 'success']);
    }
}