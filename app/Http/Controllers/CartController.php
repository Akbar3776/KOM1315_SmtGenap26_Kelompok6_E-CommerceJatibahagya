<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     *
     * @return \Illuminate\View\View
     */
    public function getCartPage()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        return view('transactions.cart', compact('cartItems'));
    }

    /**
     * Menampilkan halaman keranjang belanja.
     *
     * @return \Illuminate\View\View
     */
    public function getCheckoutPage()
    {
        $cartItems = Cart::where('user_id', auth()->id())->get();

        return view('transactions.checkout', compact('cartItems'));
    }

    /**
     * Mengambil data keranjang dalam format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCart()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        return response()->json(['cart' => $cartItems], 200);
    }

    /**
     * Menambahkan produk ke dalam keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json(['message' => 'Produk ditambahkan ke keranjang'], 201);
    }

    /**
     * Memperbarui jumlah produk dalam keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Jumlah produk diperbarui'], 200);
    }

    /**
     * Menghapus produk dari keranjang.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeCart($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return response()->json(['message' => 'Produk dihapus dari keranjang'], 200);
    }
}
