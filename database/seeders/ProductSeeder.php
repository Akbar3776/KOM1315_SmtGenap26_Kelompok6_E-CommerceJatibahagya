<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Seed Brands
        $brands = [
            ['name' => 'IKEA Swedish'],
            ['name' => 'Goto Hardware'],
            ['name' => 'Tjandra Karya'],
            ['name' => 'Azko'],
        ];

        foreach ($brands as $brandData) {
            Brand::create($brandData);
        }

        // Seed Categories
        $categories = [
            ['name' => 'Kursi & Bangku', 'slug' => 'kursi-bangku'],
            ['name' => 'Lemari', 'slug' => 'lemari'],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
        
        // Data Produk
        $products = [
            // Kategori Kursi & Bangku
            [
                'name' => 'Lorem - Kursi Kayu Santai - Hitam',
                'description' => 'Kursi kayu santai ini dibuat dari bahan kayu solid yang kuat dan tahan lama. Dengan desain yang minimalis, kursi ini sangat cocok untuk ditempatkan di ruang tamu atau ruang keluarga. Warna hitam yang elegan memberikan kesan mewah dan nyaman untuk bersantai.',
                'price' => 1200000,
                'stock' => 10,
                'category_name' => 'Kursi & Bangku',
                'brand_name' => 'IKEA Swedish',
                'image' => null,
                'logo' => null,
            ],
            [
                'name' => 'Verum - Kursi Sofa Santai - Biru',
                'description' => 'Kursi sofa santai dengan desain modern dan ergonomis, memberikan kenyamanan maksimal saat bersantai. Dengan bahan berkualitas tinggi, warna biru yang cerah akan memberikan nuansa segar di ruang tamu Anda.',
                'price' => 1300000,
                'stock' => 8,
                'category_name' => 'Kursi & Bangku',
                'brand_name' => 'Goto Hardware',
                'image' => null,
                'logo' => null,
            ],
            [
                'name' => 'Aureus - Bangku Kayu Minimalis - Coklat',
                'description' => 'Bangku kayu minimalis dengan desain yang sederhana namun elegan. Cocok untuk diletakkan di ruang makan atau teras. Terbuat dari bahan kayu pilihan yang kuat dan tahan lama.',
                'price' => 1100000,
                'stock' => 12,
                'category_name' => 'Kursi & Bangku',
                'brand_name' => 'Tjandra Karya',
                'image' => null,
                'logo' => null,
            ],
            [
                'name' => 'Fructus - Kursi Plastik Ergonomis - Merah',
                'description' => 'Kursi plastik ergonomis yang nyaman untuk digunakan sehari-hari. Memiliki desain yang ringan dan mudah dipindahkan, sangat cocok untuk keperluan indoor maupun outdoor.',
                'price' => 1000000,
                'stock' => 20,
                'category_name' => 'Kursi & Bangku',
                'brand_name' => 'Azko',
                'image' => null,
                'logo' => null,
            ],
            [
                'name' => 'Stellaris - Bangku Luar Ruangan - Putih',
                'description' => 'Bangku luar ruangan dengan bahan tahan cuaca yang cocok untuk taman atau teras. Warna putih yang elegan akan memberikan kesan bersih dan menambah kenyamanan di luar ruangan.',
                'price' => 1400000,
                'stock' => 15,
                'category_name' => 'Kursi & Bangku',
                'brand_name' => 'IKEA Swedish',
                'image' => null,
                'logo' => null,
            ],

            // Kategori Lemari
            [
                'name' => 'Alatus - Lemari Kayu - Coklat',
                'description' => 'Lemari kayu dengan desain elegan dan kapasitas besar, cocok untuk menyimpan pakaian atau barang-barang lainnya. Bahan kayu yang kuat dan tahan lama memberikan kesan mewah.',
                'price' => 2500000,
                'stock' => 5,
                'category_name' => 'Lemari',
                'brand_name' => 'Goto Hardware',
                'image' => null,
                'logo' => null,
            ],
            [
                'name' => 'Natura - Lemari Pakaian Minimalis - Putih',
                'description' => 'Lemari pakaian minimalis dengan desain modern. Sangat cocok untuk ruang tidur dengan kapasitas yang besar. Dilengkapi dengan rak yang mudah diatur untuk menyimpan pakaian lebih rapi.',
                'price' => 3000000,
                'stock' => 7,
                'category_name' => 'Lemari',
                'brand_name' => 'Tjandra Karya',
                'image' => null,
                'logo' => null,
            ],
            [
                'name' => 'Orbis - Lemari TV - Hitam',
                'description' => 'Lemari TV dengan desain ramping dan stylish. Dilengkapi dengan ruang penyimpanan untuk berbagai perangkat elektronik dan aksesori lainnya.',
                'price' => 3500000,
                'stock' => 6,
                'category_name' => 'Lemari',
                'brand_name' => 'Azko',
                'image' => null,
                'logo' => null,
            ],
            [
                'name' => 'Herbalis - Lemari Buku - Coklat Muda',
                'description' => 'Lemari buku dengan banyak rak yang dapat digunakan untuk menyimpan koleksi buku Anda. Bahan kayu pilihan memberikan kesan klasik dan elegan.',
                'price' => 2200000,
                'stock' => 5,
                'category_name' => 'Lemari',
                'brand_name' => 'IKEA Swedish',
                'image' => null,
                'logo' => null,
            ],
            [
                'name' => 'Argon - Lemari Penyimpanan - Abu-abu',
                'description' => 'Lemari penyimpanan multifungsi dengan banyak laci dan rak untuk keperluan organisasi barang. Desain yang ramping dan praktis, cocok untuk ruang kerja atau rumah minimalis.',
                'price' => 2700000,
                'stock' => 8,
                'category_name' => 'Lemari',
                'brand_name' => 'Goto Hardware',
                'image' => null,
                'logo' => null,
            ],
            [
                'name' => 'Elora - Lemari Hias - Emas',
                'description' => 'Lemari hias dengan desain elegan dan bahan premium. Cocok untuk menyimpan barang-barang koleksi berharga dan mempercantik ruang tamu Anda.',
                'price' => 4000000,
                'stock' => 4,
                'category_name' => 'Lemari',
                'brand_name' => 'Tjandra Karya',
                'image' => null,
                'logo' => null,
            ]
        ];

        // Looping untuk insert produk
        foreach ($products as $product) {
            // Menentukan category dan brand berdasarkan nama
            $category = Category::where('name', $product['category_name'])->first();
            $brand = Brand::where('name', $product['brand_name'])->first();

            // Tentukan gambar acak dari dummy-1.png hingga dummy-5.png
            $imageNumber = mt_rand(1, 5);  // Pilih angka acak antara 1 hingga 5
            $imagePath = 'images/dummy/dummy-' . $imageNumber . '.png';  // Path gambar acak
            $newImageName = Str::random(10) . '.png';  // Nama file acak untuk disimpan di storage
            $newImagePath = 'products/' . $newImageName;  // Path untuk penyimpanan di storage

            // Salin gambar ke storage dengan nama baru
            Storage::disk('public')->put($newImagePath, file_get_contents(public_path($imagePath)));

            // Insert produk ke database
            Product::create([
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'image' => $newImagePath,
                'status' => 'active',
            ]);
        }
    }
}
