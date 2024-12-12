<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{


    public function getBestSellers()
    {
        $bestSellers = Product::orderBy('stock', 'asc')
            ->take(2)
            ->get();

        return $bestSellers;
    }

    public function showWelcomePage()
    {
        $bestSellers = $this->getBestSellers();
        $products = Product::take(6)->get(); // Fetch maximum 6 products
        return view('welcome', compact('bestSellers', 'products'));
    }

    public function index(Request $request)
    {

        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'You are not authorized to access this page.');
        }
        $query = $request->input('search');

        if ($query) {
            $products = Product::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->get();
        } else {
            $products = Product::all();
        }

        return view('admin.product', ['products' => $products, 'search' => $query]);
        return view('welcome', [
            'products' => $products,
            'bestsellerProducts' => $bestsellerProducts,
            'search' => $query
        ]);
    }

    public function create()
    {
        return view('admin.create_product'); 
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Menyimpan gambar
            $imagePath = $request->file('image')->store('images', 'public');

            // Membuat produk baru
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'image' => $imagePath,
            ]);

            // Cek apakah produk berhasil dibuat
            if (!$product) {
                throw new \Exception('Gagal membuat produk');
            }

            // Redirect ke halaman produk dengan pesan sukses
            return redirect()->route('admin.product')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            // Log error
            Log::error('Product creation error: ' . $e->getMessage());

            // Redirect kembali dengan pesan error
            return back()->withErrors(['msg' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.edit_product', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;

        // Jika ada gambar baru yang diupload
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            // Simpan gambar baru
            $path = $request->file('image')->store('images', 'public');
            $product->image = $path;
        }

        $product->save();

        return redirect()->route('admin.product')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus gambar dari storage jika ada
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }

        $product->delete();

        return redirect()->route('admin.product')->with('success', 'Product deleted successfully.');
    }
}
