<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // app/Http/Controllers/Auth/PasswordResetLinkController.php

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        try {
            // Gunakan Firebase untuk mengirim link reset password
            app('firebase.auth')->sendPasswordResetLink($request->email);
        } catch (\Exception $e) {
            // Jangan beri tahu jika email tidak ada untuk keamanan
            // Cukup kembalikan seolah-olah berhasil
        }

        // Selalu kembalikan dengan pesan sukses
        $status = 'Kami telah mengirimkan link reset password ke email Anda!';
        return back()->with('status', __($status));
    }
}
