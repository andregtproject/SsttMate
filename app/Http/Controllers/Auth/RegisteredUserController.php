<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Kreait\Firebase\Exception\Auth\EmailExists;

class RegisteredUserController extends Controller
{
    // HAPUS __construct DARI SINI

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            // Panggil service Firebase secara langsung di sini
            $firebaseAuth = app('firebase.auth');
            $database = app('firebase.database');

            $createdUser = $firebaseAuth->createUserWithEmailAndPassword($request->email, $request->password);
            
            $database->getReference('users/' . $createdUser->uid)
                ->set([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);

            return redirect()->route('login')->with('status', 'Registrasi berhasil! Silakan login.');

        } catch (EmailExists $e) {
            return back()->withErrors(['email' => 'Alamat email ini sudah terdaftar.'])->withInput();
        } catch (\Throwable $e) { // Tangkap semua jenis error
            return back()->withErrors(['error' => 'Gagal membuat pengguna: ' . $e->getMessage()])->withInput();
        }
    }
}