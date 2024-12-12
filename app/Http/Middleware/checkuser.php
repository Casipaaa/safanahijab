<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUser
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userRole = Auth::user()->role;

        // Simpan riwayat role terakhir di session
        $lastRole = session('last_role', null);

        if (Auth::check() && Auth::user()->role == 'customer') {
            return $next($request);
        }


        // Jika halaman admin
        if ($request->is('admin/*') || $request->is('admin')) {
            // Jika bukan admin, paksa keluar
            if ($userRole !== 'admin') {
                return redirect('/customer/dashboard')->with('error', 'Akses ditolak');
            }

            // Update session role
            session(['last_role' => 'admin']);
        }

        // Jika halaman dashboard/customer
        if ($request->is('dashboard')) {
            // Jika bukan customer, paksa keluar
            if ($userRole !== 'customer') {
                return redirect('/admin/home')->with('error', 'Akses ditolak');
            }

            // Update session role
            session(['last_role' => 'customer']);
        }

        // Tambahkan header untuk mencegah cache
        return $next($request)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}
