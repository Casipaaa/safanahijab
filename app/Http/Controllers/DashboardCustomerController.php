<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardCustomerController extends Controller
{
    public function index(Request $request)
    {

        if (Auth::user()->role !== 'customer') {
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

        return view('customer.dashboard', ['products' => $products, 'search' => $query]);
    }
}
