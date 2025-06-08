<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan form profil pengguna.
     */
    public function edit(Request $request): View
    {
        // Ambil UID pengguna dari sesi yang disimpan saat login
        $uid = $request->session()->get('firebase_uid');
        $firebaseUser = null;

        if ($uid) {
            // Ambil data pengguna (nama, email) dari Realtime Database
            $database = app('firebase.database');
            $firebaseUser = $database->getReference('users/' . $uid)->getValue();
        }

        // Kirim data pengguna ke view
        return view('profile.edit', [
            // Kita ubah array menjadi objek agar di view tetap bisa pakai $user->name
            'user' => (object) $firebaseUser,
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Ambil UID dari sesi
        $uid = $request->session()->get('firebase_uid');

        if ($uid) {
            // Siapkan data yang akan diupdate
            $validatedData = $request->validated();
            $updates = [
                'name' => $validatedData['name'],
            ];

            // Update data di Realtime Database
            $database = app('firebase.database');
            $database->getReference('users/' . $uid)->update($updates);

            // Catatan: Mengubah email di Firebase Auth adalah proses terpisah dan lebih kompleks.
            // Untuk saat ini, kita hanya update nama.
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validasi password saat ini tetap sama
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $uid = $request->session()->get('firebase_uid');

        if ($uid) {
            try {
                // 1. Hapus pengguna dari Firebase Authentication
                app('firebase.auth')->deleteUser($uid);

                // 2. Hapus data pengguna dari Realtime Database
                app('firebase.database')->getReference('users/' . $uid)->remove();

            } catch (\Exception $e) {
                // Jika terjadi error, kembalikan ke halaman profil dengan pesan
                return Redirect::route('profile.edit')->withErrors(['deletion_error' => 'Gagal menghapus akun.']);
            }
        }

        // Hapus sesi Laravel
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}