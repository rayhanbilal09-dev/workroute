<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'], // 2MB max
        ]);

        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            // Validate the file is actually uploaded properly
            $file = $request->file('avatar');

            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store with a unique filename to prevent caching issues
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('avatars', $filename, 'public');
            $user->avatar = $path;
        }

        $user->save();

        // Refresh the auth user instance so the new avatar shows immediately
        Auth::setUser($user->fresh());

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui! Foto profil Anda kini telah diperbarui.');
    }

    public function destroyAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
            $user->save();

            // Refresh the auth user instance
            Auth::setUser($user->fresh());

            return redirect()->route('profile.edit')->with('success', 'Foto profil berhasil dihapus.');
        }

        return redirect()->route('profile.edit')->withErrors(['avatar' => 'Tidak ada foto profil untuk dihapus.']);
    }
}
