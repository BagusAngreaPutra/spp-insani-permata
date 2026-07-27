<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin; // ganti modelnya ke Admin
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register'); // pastikan sudah ubah blade-nya juga
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_admin' => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'max:255', 'unique:admin,username'],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Simpan ke tabel admin
        $admin = Admin::create([
            'nama_admin' => $request->nama_admin,
            'username'   => $request->username,
            'password'   => Hash::make($request->password),
        ]);

        event(new Registered($admin));

        // login langsung pakai guard web
        Auth::guard('web')->login($admin);

        return redirect(RouteServiceProvider::HOME);
    }
}
