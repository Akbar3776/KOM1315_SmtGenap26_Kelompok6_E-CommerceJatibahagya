<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk menangani proses pemesanan.
 */
class OrderController extends Controller
{
    /**
     * Proses checkout dan pembuatan pesanan baru.
     *
     * @param Request $request Data yang dikirim dari form checkout.
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman sukses atau kembali dengan error.
     */
    public function checkout(Request $request)
    {
        // Validasi data input
        $request->validate([
            'address_id' => 'required|exists:user_addresses,id',
            'payment_method' => 'required|in:COD,Transfer Bank',
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil alamat pengiriman berdasarkan ID yang dipilih
        $address = UserAddress::find($request->address_id);

        // Ambil produk dalam keranjang
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        // Hitung total harga pesanan
        $total_order = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
        $total_shipping = 75000;
        $total_fee = 2000;
        $amount = $total_order + $total_shipping + $total_fee;

        // Simpan pesanan menggunakan transaksi database untuk menghindari error data tidak konsisten
        DB::beginTransaction();
        try {
            // Buat order baru
            $order = Order::create([
                'user_id' => $user->id,
                'user_address_id' => $address->id,
                'total_order' => $total_order,
                'total_shipping' => $total_shipping,
                'total_fee' => $total_fee,
                'amount' => $amount,
                'status' => 'pending', // Status default 'pending' saat order dibuat
                'payment_status' => 'unpaid', // Status pembayaran awal 'unpaid'
                'shipping_address' => $address->full_address,
                'tracking_number' => null, // Tracking number belum tersedia saat checkout
            ]);

            // Simpan detail pesanan ke tabel OrderItem
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price_per_item' => $cartItem->product->price,
                    'total_price' => ($cartItem->quantity) * ($cartItem->product->price),
                ]);

                // Kurangi stok produk sesuai jumlah yang dibeli
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Hapus semua item dalam keranjang setelah checkout berhasil
            Cart::where('user_id', $user->id)->delete();

            // Commit transaksi database
            DB::commit();

            // Redirect ke halaman sukses dengan pesan berhasil
            return redirect()->route('orders.success', ['order' => $order->id])->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollback(); // Batalkan transaksi

            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage()); // Tampilkan pesan error yang lebih jelas
        }
    }

    /**
     * Menampilkan halaman sukses setelah checkout berhasil.
     *
     * @param Order $order Objek pesanan yang telah dibuat.
     * @return \Illuminate\Contracts\View\View Tampilan halaman sukses checkout.
     */
    public function success(Order $order)
    {
        return view('transactions.success', compact('order'));
    }

    /**
     * Ambil semua pesanan berdasarkan user dengan filter status.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function getOrders(Request $request)
    {
        $user = Auth::user();

        // Ambil filter dari request
        $orderStatus = $request->query('status');
        $paymentStatus = $request->query('payment_status');

        // Query pesanan user dengan opsi filter
        $orders = Order::where('user_id', $user->id)
            ->when($orderStatus, fn($query) => $query->where('status', $orderStatus))
            ->when($paymentStatus, fn($query) => $query->where('payment_status', $paymentStatus))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Ambil detail pesanan berdasarkan ID.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function getOrderDetail($id)
    {
        $user = Auth::user();

        // Ambil pesanan berdasarkan ID dan user
        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->with('orderItems.product')
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')->withErrors('Pesanan tidak ditemukan.');
        }

        return view('orders.detail', compact('order'));
    }

    /**
     * Batalkan pesanan hanya jika status pembayaran masih 'unpaid'.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelOrder($id)
    {
        $user = Auth::user();

        // Ambil pesanan user yang belum dibayar
        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->where('payment_status', 'unpaid')
            ->first();

        if (!$order) {
            return back()->withErrors('Pesanan tidak bisa dibatalkan atau tidak ditemukan.');
        }

        // Gunakan transaksi untuk menjaga data tetap konsisten
        DB::beginTransaction();
        try {
            // Kembalikan stok produk
            foreach ($order->orderItems as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            // Ubah status pesanan menjadi 'canceled'
            $order->update([
                'status' => 'canceled',
                'payment_status' => 'unpaid', // Status pembayaran tetap 'unpaid'
            ]);

            DB::commit();
            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }
}
