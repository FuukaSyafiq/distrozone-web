<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function create(): View
    {
        $totalOrder = Transaksi::where('id_customer', auth()->id())->count();
        return view('profile.index', [
            'user' => auth()->user(),
            'totalOrder' => $totalOrder

        ]);
    }

    public function editFotoProfile(Request $request)
    {
        // Validasi file
        $request->validate([
            'foto_user' => 'required|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
        ], [
            'foto_user.required' => 'Foto profile harus diupload',
            'foto_user.image' => 'File harus berupa gambar',
            'foto_user.mimes' => 'Format gambar harus JPG, PNG, atau JPEG',
            'foto_user.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $user = Auth::user();

        // Hapus foto lama jika ada
        if ($user->foto_user) {
            Storage::disk('s3')->delete($user->foto_user);
        }

        // Upload foto baru ke S3
        $file = $request->file('foto_user');
        $fileName = 'profile/' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('', $fileName, 's3');

        // Update database
        $user->foto_user = $path;
        $user->save();

        return redirect()->back()->with('success', 'Foto profile berhasil diperbarui!');
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
