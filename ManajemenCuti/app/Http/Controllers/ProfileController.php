<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user    = Auth::user();
        $profile = $user->profile;

        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user    = Auth::user();
        $profile = $user->profile;

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'nomor_telepon' => 'nullable|string|max:30',
            'alamat'        => 'nullable|string|max:255',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        if (!$profile) {
            $profile = $user->profile()->create([]);
        }

        $profile->nomor_telepon = $request->nomor_telepon;
        $profile->alamat        = $request->alamat;
        $profile->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
