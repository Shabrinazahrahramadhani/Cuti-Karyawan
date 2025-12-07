<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
  
    public function show()
    {
        $user    = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();

        return view('user.profile.show', [
            'user'    => $user,
            'profile' => $profile,
        ]);
    }

    public function edit()
    {
        $user    = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();

        return view('user.profile.edit', [
            'user'    => $user,
            'profile' => $profile,
        ]);
    }


    public function update(Request $request)
    {
        $user    = Auth::user();
        $profile = UserProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['nama_lengkap' => $user->name, 'kuota_cuti' => 12, 'status_aktif' => true]
        );

        $validated = $request->validate([
            'nama_lengkap'   => 'nullable|string|max:255',
            'nomor_telepon'  => 'nullable|string|max:50',
            'alamat'         => 'nullable|string|max:255',
            'foto'           => 'nullable|image|max:2048',
            'password'       => 'nullable|min:8|confirmed',
        ]);

        if (!empty($validated['nama_lengkap'])) {
            $profile->nama_lengkap = $validated['nama_lengkap'];
        }
        if (!empty($validated['nomor_telepon'])) {
            $profile->nomor_telepon = $validated['nomor_telepon'];
        }
        if (!empty($validated['alamat'])) {
            $profile->alamat = $validated['alamat'];
        }

       if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('profile', 'public');
            $profile->foto = $foto;
        }


        $profile->save();

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $user->save();
        }

        return redirect()
            ->back()
            ->with('success', 'Profil berhasil diperbarui.');
    }
    
}
