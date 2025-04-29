<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil 10 produk secara acak untuk Flash Sale
        $product_flash_sale = Product::inRandomOrder()->limit(10)->get();

        // Ambil 10 produk terbaik berdasarkan stok terbanyak (atau bisa pakai kriteria lain)
        $best_items = Product::where('status', 'active')
            ->orderBy('stock', 'desc')
            ->limit(10)
            ->get();

        // Ambil 3 kategori dengan jumlah produk terbanyak
        $top_categories = \App\Models\Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(3)
            ->get();

        // Ambil produk dari 3 kategori yang terpilih
        $category_products = [];
        foreach ($top_categories as $category) {
            $category_products[$category->id] = Product::where('category_id', $category->id)
                ->limit(8)
                ->get();
        }

        return view('landing.index', compact(
            'product_flash_sale',
            'best_items',
            'top_categories',
            'category_products'
        ));
    }


    public function contact()
    {
        return view('pages.contact');
    }

    public function chat()
    {
        return view('pages.chat');
    }
}
