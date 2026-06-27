<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Body Lotion', 'icon' => 'fas fa-pump-soap', 'desc' => 'Kulit sehat, cerah dan terhidrasi setiap hari'],
            ['name' => 'Body Serum', 'icon' => 'fas fa-hand-holding-droplet', 'desc' => 'Nutrisi mendalam untuk mencerahkan kulit secara intensif'],
            ['name' => 'Body Wash', 'icon' => 'fas fa-shower', 'desc' => 'Kesegaran maksimal membersihkan kotoran dan bakteri'],
            ['name' => 'Collagen Soap', 'icon' => 'fas fa-soap', 'desc' => 'Menjaga kekenyalan dan elastisitas kulit remaja harian'],
            ['name' => 'Lip Treatment', 'icon' => 'fas fa-lips', 'desc' => 'Merawat bibir pecah-pecah agar tetap pink merona alami'],
            ['name' => 'Bundle Package', 'icon' => 'fas fa-box-open', 'desc' => 'Paket hemat perawatan lengkap kulit cantik Anda']
        ];

        foreach ($categories as $cat) {
            $slug = Str::slug($cat['name']);

            // Menggunakan updateOrCreate untuk menghindari eror duplicate entry
            Category::updateOrCreate(
                ['slug' => $slug], // Mencari berdasarkan slug unik
                [
                    'name' => $cat['name'],
                    'icon' => $cat['icon'],
                    'description' => $cat['desc'],
                    'banner' => 'default-banner.png'
                ]
            );
        }
    }
}