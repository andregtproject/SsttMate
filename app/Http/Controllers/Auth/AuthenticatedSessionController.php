<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi input dari form
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        try {
            // 2. Lakukan otentikasi dengan Firebase
            $firebaseAuth = app('firebase.auth');
            $signInResult = $firebaseAuth->signInWithEmailAndPassword($request->email, $request->password);
            
            $user = $signInResult->data();
            $uid = $user['localId'];

            // 3. Simpan UID pengguna ke dalam sesi
            $request->session()->put('firebase_uid', $uid);
            
            // 4. Paksa simpan data sesi sebelum mengarahkan
            $request->session()->save();

            // 5. Arahkan ke dashboard jika berhasil
            return redirect()->intended(route('dashboard', absolute: false));

        } catch (InvalidPassword | UserNotFound $e) {
            // Tangani jika email atau password salah
            return back()->withErrors(['email' => trans('auth.failed')])->withInput($request->only('email'));
        } catch (\Throwable $e) {
            // Tangani error lainnya
            return back()->withErrors(['error' => 'Gagal login: ' . $e->getMessage()])->withInput($request->only('email'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
