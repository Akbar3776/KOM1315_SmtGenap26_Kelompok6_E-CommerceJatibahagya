<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Menampilkan semua produk yang tersedia.
     *
     * @return View Halaman daftar semua produk.
     */
    public function getAll(Request $request)
    {
        // Ambil data kategori dan brand untuk dropdown
        $categories = Category::all();
        $brands = Brand::all();

        // Ambil query filter dari request
        $categoryId = $request->input('category');
        $brandId = $request->input('brand');
        $sortBy = $request->input('sort_by');
        $search = $request->input('search');

        // Query produk berdasarkan filter
        $query = Product::query();

        // Filter berdasarkan kategori
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Filter berdasarkan brand
        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        // Filter berdasarkan keyword
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Sorting produk
        if ($sortBy == 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sortBy == 'name_desc') {
            $query->orderBy('name', 'desc');
        } elseif ($sortBy == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sortBy == 'price_desc') {
            $query->orderBy('price', 'desc');
        }

        $products = $query->paginate(8);

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Menampilkan detail produk berdasarkan ID.
     *
     * @param int $productId ID produk yang akan ditampilkan.
     * @return View Halaman detail produk.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika produk tidak ditemukan.
     */
    public function getDetail(int $productId): View
    {
        $product = Product::findOrFail($productId);

        return view('products.detail', compact('product'));
    }

}
