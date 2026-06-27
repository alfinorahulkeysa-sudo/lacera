<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================
        // 1. SEED DATA USERS (Admin & Customer + Role)
        // ==========================================
        User::create([
            'name' => 'Admin Lacera',
            'email' => 'admin@lacera.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Customer Lacera',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // ==========================================
        // 2. SEED DATA KATEGORI
        // ==========================================
        $categories = [
            'Body Lotion' => 'sparkles',
            'Body Serum' => 'beaker',
            'Body Wash' => 'water',
            'Collagen Soap' => 'heart',
            'Lip Treatment' => 'face-smile',
            'Bundle Package' => 'gift'
        ];

        $categoryIds = [];
        foreach ($categories as $cat => $icon) {
            $createdCat = Category::create([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'icon' => $icon,
            ]);
            $categoryIds[$cat] = $createdCat->id;
        }

        // ==========================================
        // 3. SEED 8 DATA PRODUK (Lengkap dengan Stock, Weight, & Sold Count)
        // ==========================================
        
        // --- KATEGORI: BODY LOTION ---
        Product::create([
            'category_id' => $categoryIds['Body Lotion'],
            'name' => 'Lacera Double Bright & Glow Body Lotion',
            'slug' => Str::slug('Lacera Double Bright & Glow Body Lotion'),
            'price' => 43999,
            'stock' => 50,
            'weight' => 250, // dalam satuan gram
            'sold_count' => 0,
            'description' => 'Mencerahkan kulit tubuh 2x lebih cepat dengan kandungan esensial glow.',
            'image' => 'products/lacera-double-bright-glow.jpeg',
        ]);

        Product::create([
            'category_id' => $categoryIds['Body Lotion'],
            'name' => 'Lacera Glow Scent Body Lotion',
            'slug' => Str::slug('Lacera Glow Scent Body Lotion'),
            'price' => 43999,
            'stock' => 45,
            'weight' => 250,
            'sold_count' => 0,
            'description' => 'Wangi mewah seharian sekaligus melembapkan kulit kering.',
            'image' => 'products/lacera-glow-scent-body-lotion.jpeg',
        ]);

        Product::create([
            'category_id' => $categoryIds['Body Lotion'],
            'name' => 'Lacera Body Lotion Booster With UV Protection',
            'slug' => Str::slug('Lacera Body Lotion Booster With UV Protection'),
            'price' => 43999,
            'stock' => 60,
            'weight' => 250,
            'sold_count' => 0,
            'description' => 'Melindungi kulit dari paparan sinar UV matahari langsung.',
            'image' => 'products/lacera-body-lotion-booster-with-uv-protection.jpeg',
        ]);

        Product::create([
            'category_id' => $categoryIds['Body Lotion'],
            'name' => 'Lacera Deep Moisturizing Body Lotion',
            'slug' => Str::slug('Lacera Deep Moisturizing Body Lotion'),
            'price' => 43999,
            'stock' => 40,
            'weight' => 250,
            'sold_count' => 0,
            'description' => 'Kelembapan ekstra untuk area kulit yang sangat kering, bersisik, dan kusam.',
            'image' => 'products/lacera-deep-smooth-bright.jpeg',
        ]);


        // --- KATEGORI: BODY SERUM ---
        Product::create([
            'category_id' => $categoryIds['Body Serum'],
            'name' => 'Lacera Bliss Body Serum',
            'slug' => Str::slug('Lacera Bliss Body Serum'),
            'price' => 75999,
            'stock' => 35,
            'weight' => 100,
            'sold_count' => 0,
            'description' => 'Kombinasi Niacinamide, Collagen, dan Kojic Acid untuk menutrisi intensif.',
            'image' => 'products/lacera-bliss-body-serum.jpeg',
        ]);


        // --- KATEGORI: COLLAGEN SOAP ---
        Product::create([
            'category_id' => $categoryIds['Collagen Soap'],
            'name' => 'Lacera Brightening Collagen Bar Soap',
            'slug' => Str::slug('Lacera Brightening Collagen Bar Soap'),
            'price' => 75599,
            'stock' => 100,
            'weight' => 80,
            'sold_count' => 0,
            'description' => 'Sabun batang kolagen premium untuk elastisitas dan kecerahan kulit menyeluruh.',
            'image' => 'products/lacera-bar-soap-brightening-collagen.jpeg',
        ]);


        // --- KATEGORI: BODY WASH ---
        Product::create([
            'category_id' => $categoryIds['Body Wash'],
            'name' => 'Lacera The Dark Secret Body Wash',
            'slug' => Str::slug('Lacera The Dark Secret Body Wash'),
            'price' => 109599,
            'stock' => 25,
            'weight' => 300,
            'sold_count' => 0,
            'description' => 'Membersihkan tubuh secara mendalam dengan sensasi wangi eksklusif yang menenangkan.',
            'image' => 'products/lacera-the-dark-secret-body-wash.jpeg',
        ]);


        // --- KATEGORI: LIP TREATMENT ---
        Product::create([
            'category_id' => $categoryIds['Lip Treatment'],
            'name' => 'Lacera Axillary Cream Lightening',
            'slug' => Str::slug('Lacera Axillary Cream Lightening'),
            'price' => 49999,
            'stock' => 80,
            'weight' => 50,
            'sold_count' => 0,
            'description' => 'Krim khusus untuk membantu mencerahkan area lipatan kulit yang gelap.',
            'image' => 'products/lacera-axillary-cream.jpeg',
        ]);
    }
}