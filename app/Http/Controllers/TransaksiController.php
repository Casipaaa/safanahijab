<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {

        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'You are not authorized to access this page.');
        }
        // Ambil status dari query string
        $status = $request->input('status');
        $searchId = $request->input('search_id');

        // Ambil semua order untuk admin, dengan filter berdasarkan status dan ID jika ada
        $orders = Order::when($status && $status !== 'all', function ($query) use ($status) {
            return $query->where('status', $status);
        })->when($searchId, function ($query) use ($searchId) {
            return $query->where('id', $searchId);
        })->get();

        // Daftar semua status order
        $statuses = ['all', 'Pending', 'Completed', 'Canceled'];

        return view('admin.transaksi', compact('orders', 'statuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            // Mulai transaksi database
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            $newStatus = $request->input('status');

            // Jika status berubah menjadi 'Completed'
            if ($newStatus === 'Completed') {
                // Ambil detail order
                $orderDetails = $order->details;

                // Loop melalui setiap detail order
                foreach ($orderDetails as $detail) {
                    $product = Product::findOrFail($detail->product_id);

                    // Kurangi stok sesuai qty yang dibeli
                    if ($product->stock >= $detail->quantity) {
                        $product->stock -= $detail->quantity;
                        $product->save();
                    } else {
                        // Jika stok tidak cukup, batalkan transaksi
                        throw new \Exception("Stok produk {$product->name} tidak mencukupi");
                    }
                }
            }

            // Update status order
            $order->status = $newStatus;
            $order->save();

            // Commit transaksi
            DB::commit();

            return redirect()->route('admin.transaksi')->with('success', 'Status order berhasil diupdate!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            return redirect()->route('admin.transaksi')->with('error', $e->getMessage());
        }
    }
    // Di TransaksiController
    public function getOrderDetail($id)
    {
        $order = Order::with(['details.product'])->findOrFail($id);

        $orderDetails = $order->details->map(function ($detail) {
            return [
                'product' => [
                    'name' => $detail->product->name,
                ],
                'quantity' => $detail->quantity,
                'price' => $detail->price,
            ];
        });

        return response()->json([
            'id' => $order->id,
            'total' => $order->total,
            'status' => $order->status,
            'details' => $orderDetails,
        ]);
    }
}
