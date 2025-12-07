<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $namaFilter   = $request->input('nama');
        $leaderFilter = $request->input('leader_id');
        $memberFilter = $request->input('members');
        $sort         = $request->input('sort', 'nama_asc');

        $query = Division::with(['ketuaDivisi.profile', 'members.user.profile'])
            ->withCount('members');

        if ($namaFilter) {
            $query->where('nama_divisi', 'like', '%' . $namaFilter . '%');
        }

        if ($leaderFilter) {
            $query->where('ketua_divisi_id', $leaderFilter);
        }

        if ($memberFilter === 'has') {
            $query->having('members_count', '>', 0);
        } elseif ($memberFilter === 'empty') {
            $query->having('members_count', '=', 0);
        }

        switch ($sort) {
            case 'nama_desc':
                $query->orderBy('nama_divisi', 'desc');
                break;
            case 'members_asc':
                $query->orderBy('members_count', 'asc');
                break;
            case 'members_desc':
                $query->orderBy('members_count', 'desc');
                break;
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'created_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('nama_divisi', 'asc');
        }

        $divisions = $query->get();

        $leaders = User::where('role', 'Leader')->orderBy('name')->get();

        return view('admin.division.index', [
            'divisions'    => $divisions,
            'leaders'      => $leaders,
            'namaFilter'   => $namaFilter,
            'leaderFilter' => $leaderFilter,
            'memberFilter' => $memberFilter,
            'sort'         => $sort,
        ]);
    }

    public function create()
    {
        $leaders = User::where('role', 'Leader')
            ->whereDoesntHave('divisiPimpinan')
            ->orderBy('name')
            ->get();

        return view('admin.division.create', [
            'leaders' => $leaders,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_divisi'     => 'required|string|max:255|unique:divisions,nama_divisi',
            'deskripsi'       => 'nullable|string',
            'ketua_divisi_id' => 'nullable|exists:users,id',
        ]);

        if (!empty($validated['ketua_divisi_id'])) {
            $leader = User::where('id', $validated['ketua_divisi_id'])
                ->where('role', 'Leader')
                ->firstOrFail();

            if ($leader->divisiPimpinan) {
                return back()
                    ->withErrors(['ketua_divisi_id' => 'Leader ini sudah memimpin divisi lain.'])
                    ->withInput();
            }
        }

        Division::create([
            'nama_divisi'     => $validated['nama_divisi'],
            'deskripsi'       => $validated['deskripsi'] ?? null,
            'ketua_divisi_id' => $validated['ketua_divisi_id'] ?? null,
        ]);

        return redirect()
            ->route('admin.division.index')
            ->with('success', 'Divisi berhasil dibuat.');
    }

    public function edit(Division $division)
    {
        $leaders = User::where('role', 'Leader')
            ->where(function ($query) use ($division) {
                $query->whereDoesntHave('divisiPimpinan')
                      ->orWhereHas('divisiPimpinan', function ($q) use ($division) {
                          $q->where('id', $division->id);
                      });
            })
            ->orderBy('name')
            ->get();

        return view('admin.division.edit', [
            'division' => $division,
            'leaders'  => $leaders,
        ]);
    }

    public function update(Request $request, Division $division)
    {
        $validated = $request->validate([
            'nama_divisi'     => 'required|string|max:255|unique:divisions,nama_divisi,' . $division->id,
            'deskripsi'       => 'nullable|string',
            'ketua_divisi_id' => 'nullable|exists:users,id',
        ]);

        if (!empty($validated['ketua_divisi_id'])) {
            $leader = User::where('id', $validated['ketua_divisi_id'])
                ->where('role', 'Leader')
                ->firstOrFail();

            if ($leader->divisiPimpinan && $leader->divisiPimpinan->id !== $division->id) {
                return back()
                    ->withErrors(['ketua_divisi_id' => 'Leader ini sudah memimpin divisi lain.'])
                    ->withInput();
            }
        }

        $division->update([
            'nama_divisi'     => $validated['nama_divisi'],
            'deskripsi'       => $validated['deskripsi'] ?? null,
            'ketua_divisi_id' => $validated['ketua_divisi_id'] ?? null,
        ]);

        return redirect()
            ->route('admin.division.index')
            ->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        UserProfile::where('divisi_id', $division->id)
            ->update(['divisi_id' => null]);

        $division->delete();

        return redirect()
            ->route('admin.division.index')
            ->with('success', 'Divisi berhasil dihapus.');
    }

    public function members(Division $division)
    {
        $division->load([
            'ketuaDivisi.profile',
            'members.user.profile',
        ]);

        $availableUsers = User::where('role', 'User')
            ->whereHas('profile', function ($q) {
                $q->whereNull('divisi_id');
            })
            ->orderBy('name')
            ->get();

        return view('admin.division.members', [
            'division'           => $division,
            'availableEmployees' => $availableUsers, 
        ]);
    }


    public function addMember(Request $request, Division $division)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($data['user_id']);

        if ($user->role !== 'User') {
            return back()
                ->withErrors(['user_id' => 'Hanya karyawan (role User) yang dapat dijadikan anggota divisi.'])
                ->withInput();
        }

        if (!$user->profile) {
            $user->profile()->create([
                'nama_lengkap'  => $user->name,
                'kuota_cuti'    => 12,
                'status_aktif'  => true,
                'divisi_id'     => $division->id,
            ]);
        } else {
            $profile = $user->profile;
            $profile->divisi_id = $division->id;

            if ($profile->nama_lengkap === null) {
                $profile->nama_lengkap = $user->name;
            }
            if ($profile->kuota_cuti === null) {
                $profile->kuota_cuti = 12;
            }

            $profile->save();
        }

        return back()->with('success', 'Anggota berhasil ditambahkan ke divisi.');
    }

    public function removeMember(Division $division, User $user)
    {
        if ($user->profile && $user->profile->divisi_id == $division->id) {
            $user->profile->divisi_id = null;
            $user->profile->save();
        }

        return back()->with('success', 'Anggota berhasil dikeluarkan dari divisi.');
    }
}
