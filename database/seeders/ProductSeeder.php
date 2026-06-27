<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua kategori yang ada di database
        $categories = Category::all();

        // Jika kategori masih kosong, kita buatkan sesuai dengan menu kategori asli di website Lacera
        if ($categories->isEmpty()) {
            $categories = collect([
                Category::create(['name' => 'Body Lotion', 'slug' => 'body-lotion', 'description' => 'Kulit sehat, cerah dan terhidrasi setiap hari.']),
                Category::create(['name' => 'Body Serum', 'slug' => 'body-serum', 'description' => 'Nutrisi mendalam untuk perlindungan kulit maksimal.']),
                Category::create(['name' => 'Body Wash', 'slug' => 'body-wash', 'description' => 'Membersihkan kulit secara menyeluruh dengan kesegaran alami.']),
                Category::create(['name' => 'Collagen Soap', 'slug' => 'collagen-soap', 'description' => 'Sabun kolagen untuk menjaga kekencangan dan kecerahan kulit.']),
                Category::create(['name' => 'Lip Treatment', 'slug' => 'lip-treatment', 'description' => 'Perawatan intensif untuk bibir dan area sensitif lainnya.']),
            ]);
        }

        // Definisikan sampel produk riil berdasarkan file gambar di folder storage Anda
        $sampleProducts = [
            // ==========================================
            // 1. KATEGORI: BODY LOTION (Keywords: 'lotion')
            // ==========================================
            [
                'name' => 'Lacera Body Lotion Booster With UV Protection',
                'price' => 85000,
                'sold_count' => 150,
                'keywords' => 'lotion',
                'days_ago' => 10,
                'image' => 'products/lacera-body-lotion-booster-with-u-protection.jpeg',
            ],
            [
                'name' => 'Lacera Deep Moisturizing Body Lotion',
                'price' => 75000,
                'sold_count' => 40,
                'keywords' => 'lotion',
                'days_ago' => 12,
                'image' => 'products/lacera-deep-smooth-bright.jpeg',
            ],
            [
                'name' => 'Lacera Double Bright & Glow Body Lotion',
                'price' => 85000,
                'sold_count' => 185,
                'keywords' => 'lotion',
                'days_ago' => 5,
                'image' => 'products/lacera-double-bright-glow.jpeg',
            ],
            [
                'name' => 'Lacera Glow Scent Body Lotion',
                'price' => 80000,
                'sold_count' => 62,
                'keywords' => 'lotion',
                'days_ago' => 7,
                'image' => 'products/lacera-glow-scent-body-lotion.jpeg',
            ],

            // ==========================================
            // 2. KATEGORI: BODY SERUM (Keywords: 'serum')
            // ==========================================
            [
                'name' => 'Lacera Bliss Body Serum',
                'price' => 120000,
                'sold_count' => 320,
                'keywords' => 'serum',
                'days_ago' => 2,
                'image' => 'products/lacera-bliss-body-serum.jpeg',
            ],

            // ==========================================
            // 3. KATEGORI: BODY WASH (Keywords: 'wash')
            // ==========================================
            [
                'name' => 'Lacera The Dark Secret Body Wash',
                'price' => 65000,
                'sold_count' => 110,
                'keywords' => 'wash',
                'days_ago' => 4,
                'image' => 'products/lacera-the-dark-secret-body-wash.jpeg',
            ],

            // ==========================================
            // 4. KATEGORI: COLLAGEN SOAP (Keywords: 'soap')
            // ==========================================
            [
                'name' => 'Lacera Brightening Collagen Bar Soap',
                'price' => 35000,
                'sold_count' => 210,
                'keywords' => 'soap',
                'days_ago' => 15,
                'image' => 'products/lacera-bar-soap-brightening-collagen.jpeg',
            ],

            // ==========================================
            // 5. KATEGORI: LIP TREATMENT (Keywords: 'treatment')
            // ==========================================
            [
                'name' => 'Lacera Axillary Cream',
                'price' => 55000,
                'sold_count' => 135,
                'keywords' => 'treatment',
                'days_ago' => 20,
                'image' => 'products/lacera-axillary-cream.jpeg',
            ],
        ];

        // Masukkan data ke dalam tabel products
        foreach ($sampleProducts as $data) {
            // Cari kategori yang cocok berdasarkan keyword nama
            $matchedCategory = $categories->first(function ($cat) use ($data) {
                return Str::contains(Str::lower($cat->name), $data['keywords']);
            }) ?? $categories->first(); // jika tidak ada yang cocok, gunakan kategori pertama

            Product::create([
                'category_id' => $matchedCategory->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => 'Formula premium dari Lacera untuk perawatan kulit maksimal Anda sehari-hari.',
                'price' => $data['price'],
                'stock' => rand(10, 50),
                'weight' => 200,
                'sold_count' => $data['sold_count'],
                'image' => $data['image'], // Menggunakan file gambar asli dari array seeder
                'created_at' => now()->subDays($data['days_ago']),
                'updated_at' => now()->subDays($data['days_ago']),
            ]);
        }
    }
}