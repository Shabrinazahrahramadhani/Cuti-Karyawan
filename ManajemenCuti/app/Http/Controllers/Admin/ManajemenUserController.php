<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ManajemenUserController extends Controller
{
    public function index(Request $request)
    {
        $roleFilter      = $request->input('role');
        $divisionFilter  = $request->input('division_id');
        $statusFilter    = $request->input('status');
        $masaKerjaFilter = $request->input('masa_kerja');
        $sort            = $request->input('sort', 'name');
        $direction       = $request->input('direction', 'asc');

        $query = User::with(['profile.division']);

        if ($roleFilter) {
            $query->where('role', $roleFilter);
        }

        if ($divisionFilter) {
            $query->whereHas('profile', function ($q) use ($divisionFilter) {
                $q->where('divisi_id', $divisionFilter);
            });
        }

        if ($statusFilter === 'active') {
            $query->whereHas('profile', function ($q) {
                $q->where('status_aktif', true);
            });
        }

        if ($statusFilter === 'inactive') {
            $query->whereHas('profile', function ($q) {
                $q->where('status_aktif', false);
            });
        }

        if ($masaKerjaFilter) {
            $cutoff = Carbon::now()->subYear();

            if ($masaKerjaFilter === 'lt1') {
                $query->where('created_at', '>=', $cutoff);
            }

            if ($masaKerjaFilter === 'gte1') {
                $query->where('created_at', '<', $cutoff);
            }
        }

        $users = $query->get();

        if ($sort === 'name') {
            $users = $users->sortBy(
                fn($u) => $u->profile->nama_lengkap ?? $u->name ?? '',
                SORT_NATURAL,
                $direction === 'desc'
            );
        }

        if ($sort === 'joined') {
            $users = $users->sortBy('created_at', SORT_REGULAR, $direction === 'desc');
        }

        if ($sort === 'division') {
            $users = $users->sortBy(
                fn($u) => optional(optional($u->profile)->division)->nama_divisi ?? '',
                SORT_NATURAL,
                $direction === 'desc'
            );
        }

        return view('admin.manajemen_user.index', [
            'users'           => $users,
            'divisions'       => Division::orderBy('nama_divisi')->get(),
            'roleFilter'      => $roleFilter,
            'divisionFilter'  => $divisionFilter,
            'statusFilter'    => $statusFilter,
            'masaKerjaFilter' => $masaKerjaFilter,
            'sort'            => $sort,
            'direction'       => $direction,
        ]);
    }

    public function create()
    {
        return view('admin.manajemen_user.create', [
            'divisions' => Division::orderBy('nama_divisi')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255|unique:users,name',
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'nama_lengkap'  => 'required|string|max:255',
            'role'          => 'required|in:Admin,User,Leader,HRD',
            'divisi_id'     => 'nullable|exists:divisions,id',
            'alamat'        => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role'     => $validated['role'],
        ]);

        $user->profile()->create([
            'nama_lengkap'  => $validated['nama_lengkap'],
            'status_aktif'  => $request->has('status_aktif'),
            'kuota_cuti'    => 12,
            'divisi_id'     => $validated['role'] === 'User' ? $validated['divisi_id'] : null,
            'alamat'        => $validated['alamat'] ?? null,
            'nomor_telepon' => $validated['nomor_telepon'] ?? null,
        ]);

        return redirect()
            ->route('admin.manajemen_user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $manajemen_user)
    {
        return view('admin.manajemen_user.edit', [
            'user'      => $manajemen_user,
            'divisions' => Division::orderBy('nama_divisi')->get(),
        ]);
    }

    public function show(User $manajemen_user)
    {
        return redirect()->route('admin.manajemen_user.edit', $manajemen_user->id);
    }

    public function update(Request $request, User $manajemen_user)
    {
        if (in_array($manajemen_user->role, ['Admin', 'HRD'])) {
            return back()->with('error', 'System user tidak boleh diedit.');
        }

        $validated = $request->validate([
            'name'          => 'required|string|max:255|unique:users,name,' . $manajemen_user->id,
            'email'         => 'required|email|max:255|unique:users,email,' . $manajemen_user->id,
            'nama_lengkap'  => 'required|string|max:255',
            'role'          => 'required|in:Admin,User,Leader,HRD',
            'divisi_id'     => 'nullable|exists:divisions,id',
            'alamat'        => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:20',
        ]);

        $manajemen_user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role'  => $validated['role'],
        ]);

        $manajemen_user->profile()->updateOrCreate(
            ['user_id' => $manajemen_user->id],
            [
                'nama_lengkap'  => $validated['nama_lengkap'],
                'status_aktif'  => $request->has('status_aktif'),
                'kuota_cuti'    => optional($manajemen_user->profile)->kuota_cuti ?? 12,
                'divisi_id'     => $validated['role'] === 'User' ? $validated['divisi_id'] : null,
                'alamat'        => $validated['alamat'] ?? null,
                'nomor_telepon' => $validated['nomor_telepon'] ?? null,
            ]
        );

        return redirect()
            ->route('admin.manajemen_user.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $manajemen_user)
    {
        if (!in_array($manajemen_user->role, ['User', 'Leader'])) {
            return back()->with('error', 'System user tidak boleh dihapus.');
        }

        $manajemen_user->profile?->delete();
        $manajemen_user->delete();

        return redirect()
            ->route('admin.manajemen_user.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function toggleActive(User $manajemen_user)
    {
        if (in_array($manajemen_user->role, ['Admin', 'HRD'])) {
            return back()->with('error', 'System user tidak boleh diubah.');
        }

        $profile = $manajemen_user->profile;

        if (!$profile) {
            return back()->with('error', 'Profil user tidak ditemukan.');
        }

        $profile->status_aktif = !$profile->status_aktif;
        $profile->save();

        return back()->with(
            'success',
            $profile->status_aktif ? 'User berhasil diaktifkan.' : 'User berhasil dinonaktifkan.'
        );
    }
}
