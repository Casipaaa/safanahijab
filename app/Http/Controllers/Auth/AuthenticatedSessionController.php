<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();

            // Reset session history untuk mencegah navigasi tidak diinginkan
            $request->session()->forget('last_role');

            // Arahkan berdasarkan role
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/home')
                    ->with('last_role', 'admin');
            } elseif (Auth::user()->role === 'customer') {
                return redirect()->intended('/customer/dashboard')
                    ->with('last_role', 'customer');
            }

            // Jika role tidak dikenali
            return redirect('/login')->with('error', 'Akses tidak sah');
        }

        return back()->withErrors([
            'username' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('username');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Hapus riwayat role saat logout
        $request->session()->forget('last_role');

        return redirect('/login');
    }
}
