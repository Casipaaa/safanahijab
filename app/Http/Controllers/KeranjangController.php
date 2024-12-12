<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Details;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class KeranjangController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'customer') {
            return back()->with('error', 'You are not authorized to access this page.');
        }

        $keranjang = session()->get('keranjang', []);
        return view('customer.keranjang', compact('keranjang'));
    }

    public function add(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Ambil produk dari database
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan.'], 404);
        }

        // Ambil keranjang dari sesi
        $keranjang = session()->get('keranjang', []);

        // Tambahkan atau perbarui produk di keranjang
        if (isset($keranjang[$id])) {
            $keranjang[$id]['quantity'] += $request->quantity;
        } else {
            $keranjang[$id] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $product->price,
                "image" => $product->image,
            ];
        }

        // Simpan kembali ke sesi
        session()->put('keranjang', $keranjang);

        return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang.']);
    }

    public function update(Request $request, $key)
    {
        // Validasi input
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $keranjang = session()->get('keranjang', []);
        if (isset($keranjang[$key])) {
            $keranjang[$key]['quantity'] = $request->input('quantity');
            session()->put('keranjang', $keranjang);
            return redirect()->back()->with('success', 'Jumlah produk berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    public function destroy($key)
    {
        $keranjang = session()->get('keranjang', []);
        if (isset($keranjang[$key])) {
            unset($keranjang[$key]);
            session()->put('keranjang', $keranjang);
            return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    public function buy(Request $request)
    {
        // Ambil keranjang dari sesi
        $keranjang = session()->get('keranjang', []);
        if (empty($keranjang)) {
            return redirect()->back()->with('error', 'Keranjang Anda kosong.');
        }

        // Pastikan pengguna sudah login
        $user = Auth::user();
        if (!$user || $user->role !== 'customer') {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melakukan pembelian.');
        }

        // Buat order baru
        $order = new Order();
        $order->user_id = $user->id;
        $order->total = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $keranjang));
        $order->status = 'pending';
        $order->save();

        // Simpan detail pesanan
        foreach ($keranjang as $item) {
            $detail = new Details();
            $detail->order_id = $order->id;
            $detail->product_id = $item['id'];
            $detail->quantity = $item['quantity'];
            $detail->price = $item['price'];
            $detail->user_id = $user->id; // Menyimpan user_id
            $detail->save();
        }

        // Hapus keranjang setelah pembelian
        session()->forget('keranjang');

        return redirect()->route('customer.transaksi')->with('success', 'Pembelian berhasil dilakukan.');
    }
}
