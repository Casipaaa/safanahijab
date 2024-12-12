<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class TransaksiCustomerController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan pengguna sudah login
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Periksa apakah pengguna adalah 'customer'
        if ($user->role !== 'customer') {
            return back()->with('error', 'You are not authorized to access this page.');
        }

        // Ambil status dari query string
        $status = $request->input('status');
        $searchId = $request->input('search_id');

        // Ambil semua order untuk customer, dengan filter berdasarkan status dan ID jika ada
        $orders = Order::where('user_id', $user->id) // Gunakan $user->id
            ->when($status && $status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($searchId, function ($query) use ($searchId) {
                return $query->where('id', $searchId);
            })
            ->get();

        // Kembalikan view dengan data order
        return view('customer.transaksi', compact('orders'));
    }
    public function getOrderDetail($id)
    {
        $order = Order::with(['details' => function ($query) {
            $query->with('product');
        }])->findOrFail($id);

        return response()->json([
            'details' => $order->details,
            'total' => $order->total
        ]);
    }
}
