<?php

// namespace Database\Seeders;

// use App\Models\Product;
// use App\Models\Brand;
// use App\Models\Category;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Str;

// class ProductSeeder extends Seeder
// {
//     public function run()
//     {
//         // Seed Brands
//         $brands = [
//             ['name' => 'IKEA Swedish'],
//             ['name' => 'Goto Hardware'],
//             ['name' => 'Tjandra Karya'],
//             ['name' => 'Azko'],
//         ];

//         foreach ($brands as $brandData) {
//             Brand::create($brandData);
//         }

//         // Seed Categories
//         $categories = [
//             ['name' => 'Kursi & Bangku', 'slug' => 'kursi-bangku'],
//             ['name' => 'Lemari', 'slug' => 'lemari'],
//         ];

//         foreach ($categories as $categoryData) {
//             Category::create($categoryData);
//         }

//         // Data Produk
//         $products = [
//             // Kategori Kursi & Bangku
//             [
//                 'name' => 'Lorem - Kursi Kayu Santai - Hitam',
//                 'description' => 'Kursi kayu santai ini dibuat dari bahan kayu solid yang kuat dan tahan lama. Dengan desain yang minimalis, kursi ini sangat cocok untuk ditempatkan di ruang tamu atau ruang keluarga. Warna hitam yang elegan memberikan kesan mewah dan nyaman untuk bersantai.',
//                 'price' => 1200000,
//                 'stock' => 10,
//                 'category_name' => 'Kursi & Bangku',
//                 'brand_name' => 'IKEA Swedish',
//                 'image' => null,
//                 'logo' => null,
//             ],
//             [
//                 'name' => 'Verum - Kursi Sofa Santai - Biru',
//                 'description' => 'Kursi sofa santai dengan desain modern dan ergonomis, memberikan kenyamanan maksimal saat bersantai. Dengan bahan berkualitas tinggi, warna biru yang cerah akan memberikan nuansa segar di ruang tamu Anda.',
//                 'price' => 1300000,
//                 'stock' => 8,
//                 'category_name' => 'Kursi & Bangku',
//                 'brand_name' => 'Goto Hardware',
//                 'image' => null,
//                 'logo' => null,
//             ],
//             [
//                 'name' => 'Aureus - Bangku Kayu Minimalis - Coklat',
//                 'description' => 'Bangku kayu minimalis dengan desain yang sederhana namun elegan. Cocok untuk diletakkan di ruang makan atau teras. Terbuat dari bahan kayu pilihan yang kuat dan tahan lama.',
//                 'price' => 1100000,
//                 'stock' => 12,
//                 'category_name' => 'Kursi & Bangku',
//                 'brand_name' => 'Tjandra Karya',
//                 'image' => null,
//                 'logo' => null,
//             ],
//             [
//                 'name' => 'Fructus - Kursi Plastik Ergonomis - Merah',
//                 'description' => 'Kursi plastik ergonomis yang nyaman untuk digunakan sehari-hari. Memiliki desain yang ringan dan mudah dipindahkan, sangat cocok untuk keperluan indoor maupun outdoor.',
//                 'price' => 1000000,
//                 'stock' => 20,
//                 'category_name' => 'Kursi & Bangku',
//                 'brand_name' => 'Azko',
//                 'image' => null,
//                 'logo' => null,
//             ],
//             [
//                 'name' => 'Stellaris - Bangku Luar Ruangan - Putih',
//                 'description' => 'Bangku luar ruangan dengan bahan tahan cuaca yang cocok untuk taman atau teras. Warna putih yang elegan akan memberikan kesan bersih dan menambah kenyamanan di luar ruangan.',
//                 'price' => 1400000,
//                 'stock' => 15,
//                 'category_name' => 'Kursi & Bangku',
//                 'brand_name' => 'IKEA Swedish',
//                 'image' => null,
//                 'logo' => null,
//             ],

//             // Kategori Lemari
//             [
//                 'name' => 'Alatus - Lemari Kayu - Coklat',
//                 'description' => 'Lemari kayu dengan desain elegan dan kapasitas besar, cocok untuk menyimpan pakaian atau barang-barang lainnya. Bahan kayu yang kuat dan tahan lama memberikan kesan mewah.',
//                 'price' => 2500000,
//                 'stock' => 5,
//                 'category_name' => 'Lemari',
//                 'brand_name' => 'Goto Hardware',
//                 'image' => null,
//                 'logo' => null,
//             ],
//             [
//                 'name' => 'Natura - Lemari Pakaian Minimalis - Putih',
//                 'description' => 'Lemari pakaian minimalis dengan desain modern. Sangat cocok untuk ruang tidur dengan kapasitas yang besar. Dilengkapi dengan rak yang mudah diatur untuk menyimpan pakaian lebih rapi.',
//                 'price' => 3000000,
//                 'stock' => 7,
//                 'category_name' => 'Lemari',
//                 'brand_name' => 'Tjandra Karya',
//                 'image' => null,
//                 'logo' => null,
//             ],
//             [
//                 'name' => 'Orbis - Lemari TV - Hitam',
//                 'description' => 'Lemari TV dengan desain ramping dan stylish. Dilengkapi dengan ruang penyimpanan untuk berbagai perangkat elektronik dan aksesori lainnya.',
//                 'price' => 3500000,
//                 'stock' => 6,
//                 'category_name' => 'Lemari',
//                 'brand_name' => 'Azko',
//                 'image' => null,
//                 'logo' => null,
//             ],
//             [
//                 'name' => 'Herbalis - Lemari Buku - Coklat Muda',
//                 'description' => 'Lemari buku dengan banyak rak yang dapat digunakan untuk menyimpan koleksi buku Anda. Bahan kayu pilihan memberikan kesan klasik dan elegan.',
//                 'price' => 2200000,
//                 'stock' => 5,
//                 'category_name' => 'Lemari',
//                 'brand_name' => 'IKEA Swedish',
//                 'image' => null,
//                 'logo' => null,
//             ],
//             [
//                 'name' => 'Argon - Lemari Penyimpanan - Abu-abu',
//                 'description' => 'Lemari penyimpanan multifungsi dengan banyak laci dan rak untuk keperluan organisasi barang. Desain yang ramping dan praktis, cocok untuk ruang kerja atau rumah minimalis.',
//                 'price' => 2700000,
//                 'stock' => 8,
//                 'category_name' => 'Lemari',
//                 'brand_name' => 'Goto Hardware',
//                 'image' => null,
//                 'logo' => null,
//             ],
//             [
//                 'name' => 'Elora - Lemari Hias - Emas',
//                 'description' => 'Lemari hias dengan desain elegan dan bahan premium. Cocok untuk menyimpan barang-barang koleksi berharga dan mempercantik ruang tamu Anda.',
//                 'price' => 4000000,
//                 'stock' => 4,
//                 'category_name' => 'Lemari',
//                 'brand_name' => 'Tjandra Karya',
//                 'image' => null,
//                 'logo' => null,
//             ]
//         ];

//         // Looping untuk insert produk
//         foreach ($products as $product) {
//             // Menentukan category dan brand berdasarkan nama
//             $category = Category::where('name', $product['category_name'])->first();
//             $brand = Brand::where('name', $product['brand_name'])->first();

//             // Tentukan gambar acak dari dummy-1.png hingga dummy-5.png
//             $imageNumber = mt_rand(1, 5);  // Pilih angka acak antara 1 hingga 5
//             $imagePath = 'images/dummy/dummy-' . $imageNumber . '.png';  // Path gambar acak
//             $newImageName = Str::random(10) . '.png';  // Nama file acak untuk disimpan di storage
//             $newImagePath = 'products/' . $newImageName;  // Path untuk penyimpanan di storage

//             // Salin gambar ke storage dengan nama baru
//             Storage::disk('public')->put($newImagePath, file_get_contents(public_path($imagePath)));

//             // Insert produk ke database
//             Product::create([
//                 'name' => $product['name'],
//                 'description' => $product['description'],
//                 'price' => $product['price'],
//                 'stock' => $product['stock'],
//                 'category_id' => $category->id,
//                 'brand_id' => $brand->id,
//                 'image' => $newImagePath,
//                 'status' => 'active',
//             ]);
//         }
//     }
// }


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan Kategori Baru
        $categories = [
            'Kamar Tidur',
            'Lemari',
            'Ruang Tamu',
            'Dapur',
            'Kamar Mandi',
            'Kantor'
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);
        }

        // Menambahkan Brand
        $brands = [
            'IKEA Swedish',
            'Goto Hardware',
            'Tjandra Karya',
            'Azko'
        ];

        foreach ($brands as $brandName) {
            Brand::firstOrCreate([
                'name' => $brandName,
                // 'slug' => Str::slug($brandName),
            ]);
        }

        // Produk per kategori (Nama + Nama Latin Unik)
        $products = [
            'Kamar Tidur' => [
                'Tempat Tidur Luxor (Lectus Magnificus)',
                'Kasur Orthopedic (Stratum Medico)',
                'Bantal Lateks Hypnos (Cervicalis Mollis)',
                'Selimut Bulu Alpaca (Pellis Andinus)',
                'Lemari Kamar Estetis (Armarium Pulchra)',
                'Meja Rias Venice (Speculum Artifex)',
                'Lampu Tidur Moonlight (Lucerna Lunaris)',
                'Cermin Hias Gilded (Speculum Auratum)',
                'Karpet Wol Persia (Tapes Trychophyta)',
                'Nakas Minimalis (Scrinium Simplicis)',
                'Hanger Kayu Ebony (Pendens Ebanus)',
                'Set Sprei Bamboo (Stratum Bambus)',
                'Dekorasi Dinding Maroko (Muralis Marocanum)',
                'Rak Sudut Modular (Angulus Stratum)',
                'Gorden Blackout Premium (Velum Opacum)'
            ],
            'Lemari' => [
                'Lemari Pakaian Tokyo (Vestibulum Nipponicus)',
                'Lemari Sliding Bavaria (Armarium Movens)',
                'Rak Sepatu Besi Oxford (Scapus Ferreus)',
                'Lemari Serbaguna Aspen (Armarium Versatile)',
                'Lemari Arsip Titan (Scrinium Archivum)',
                'Lemari Pajangan Paris (Armarium Ostentus)',
                'Lemari Sudut Vienna (Angulus Scrinium)',
                'Lemari Anak Animasi (Armarium Puerilis)',
                'Rak Gantung Serba Guna (Pluteus Suspendens)',
                'Lemari TV Contempo (Scrinium Visionis)',
                'Lemari Plastik Xylo (Armarium Syntheticus)',
                'Rak Dinding Eiffel (Pluteus Parisiensis)',
                'Lemari Kayu Oakwood (Scrinium Quercus)',
                'Lemari Laci Verona (Scrinium Cassettis)',
                'Lemari Pintu Geser Berlin (Scrinium Movens Urbanus)'
            ],
            'Ruang Tamu' => [
                'Sofa Bed Milano (Lectus Expandens)',
                'Meja Kopi Walnut (Mensa Juglandis)',
                'Rak TV Aluminium (Pluteus Aluminatus)',
                'Karpet Lantai Woolrich (Tapes Trychophyta)',
                'Dekorasi Dinding Art Deco (Muralis Deco)',
                'Meja Sudut Skandinavia (Mensa Scandinavica)',
                'Kursi Rotan Kyoto (Sella Palmata)',
                'Set Sofa Elegance (Lectus Pulchritudo)',
                'Lemari Pajangan Antik (Scrinium Antiquus)',
                'Jam Dinding Roman (Horologium Romanum)',
                'Rak Buku Redwood (Pluteus Libris)',
                'Lampu Meja Klasik (Lucerna Tabulae)',
                'Set Cushion Sofa Velvet (Pulvinar Mollis)',
                'Meja Konsol Madrid (Mensa Consola)',
                'Gorden Ruang Tamu Imperial (Velum Regalis)'
            ],
            'Dapur' => [
                'Kompor Gas Austria (Fornax Gasium)',
                'Oven Listrik Berlin (Clibanus Electricus)',
                'Rak Piring Eropa (Pluteus Tabularum)',
                'Set Pisau Dapur Damascus (Culter Gastronomic)',
                'Lemari Dapur Modular (Scrinium Coquina)',
                'Meja Makan French (Mensa Gaster)',
                'Wajan Teflon (Sartago Antihaesit)',
                'Blender Otomatis (Frangitor Electricus)',
                'Rice Cooker Smart (Coquus Orizae)',
                'Set Gelas Kristal (Calices Cristallum)',
                'Rak Dapur Kayu Oak (Pluteus Quercus)',
                'Sendok Garpu Stainless (Coclearia Ferrea)',
                'Dispenser Air Digital (Aquam Distribuens)',
                'Tempat Sampah Kompos (Receptaculum Compostum)',
                'Penggiling Bumbu Japan (Mola Aromatica)'
            ],
            'Kamar Mandi' => [
                'Shower Set AquaFlow (Imber Apparatus)',
                'Wastafel Marmer Classic (Labrum Marmoreum)',
                'Cermin LED Reflex (Speculum Illuminatum)',
                'Rak Handuk Bamboo (Pluteus Bambus)',
                'Tirai Kamar Mandi Hydrophobic (Velum Impermeabilis)',
                'Rak Sabun Modern (Pluteus Sapones)',
                'Shower Digital Thermo (Imber Thermostaticus)',
                'Toilet Jepang Auto (Latrina Automatica)',
                'Gantungan Handuk Premium (Ansa Pannorum)',
                'Set Alat Kebersihan Hygiene (Instrumenta Puritatis)',
                'Karpet Anti Slip Natura (Tapes Antiderapum)',
                'Rak Sudut Aluminium (Pluteus Angulus)',
                'Dispenser Sabun Infrared (Sapones Dispensator)',
                'Bak Mandi Granite (Balneum Granitum)',
                'Pintu Kamar Mandi Frosted (Porta Opaca)'
            ],
            'Kantor' => [
                'Meja Kerja Walnut (Mensa Operae)',
                'Kursi Kantor Ergonomis (Sella Officii)',
                'Lemari Arsip Profesional (Scrinium Documentum)',
                'Rak Buku Kantor (Pluteus Librorum)',
                'Lampu Meja Fokus (Lucerna Operis)',
                'Whiteboard Magnetik (Tabula Alba Magneticum)',
                'Organizer Meja Kantor (Ordo Officii)',
                'Set Alat Tulis OfficePro (Instrumenta Scripturae)',
                'Printer LaserTech (Typographus Electricus)',
                'Laptop Stand Adjustable (Sustentaculum Computatorium)',
                'Rak Dinding File (Pluteus Fasciculorum)',
                'Kabel Organizer HideTech (Organisator Filorum)',
                'Jam Dinding Digital SmartTime (Horologium Electricum)',
                'Cermin Kantor Elegan (Speculum Officii)',
                'Meja Meeting Konferensi (Mensa Congressus)'
            ]
        ];

        // Simpan Produk ke Database
        foreach ($products as $categoryName => $productList) {
            $category = Category::where('name', $categoryName)->first();
            $brand = Brand::inRandomOrder()->first();

            foreach ($productList as $productName) {
                // Tentukan gambar acak dari dummy-1.png hingga dummy-5.png
                $imageNumber = mt_rand(1, 5);  // Pilih angka acak antara 1 hingga 5
                $imagePath = 'images/dummy/dummy-' . $imageNumber . '.png';  // Path gambar acak
                $newImageName = Str::random(10) . '.png';  // Nama file acak untuk disimpan di storage
                $newImagePath = 'products/' . $newImageName;  // Path untuk penyimpanan di storage

                // Salin gambar ke storage dengan nama baru
                Storage::disk('public')->put($newImagePath, file_get_contents(public_path($imagePath)));

                Product::create([
                    'name' => $productName,
                    // 'slug' => Str::slug($productName),
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'price' => rand(50000, 5000000),
                    'stock' => rand(5, 100),
                    'image' => $newImagePath,
                    'description' => 'Produk ' . strtolower($categoryName) . ' berkualitas tinggi dan desain modern.',
                ]);
            }
        }
    }
}
