@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')

<style>
    @keyframes fadeInUpSoft {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim-page { animation: fadeInUpSoft .35s ease-out forwards; }
</style>

<div class="max-w-6xl mx-auto mt-10 px-4 anim-page">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl md:text-3xl font-semibold text-slate-900 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full
                             bg-sky-100 text-[0.6rem] font-bold text-slate-900 tracking-[0.18em] uppercase">
                    ADMIN
                </span>
                <span class="tracking-[0.14em] uppercase text-sm sm:text-base text-slate-800">
                    Manajemen Pengguna
                </span>
            </h2>
            <p class="mt-2 text-xs text-slate-500">
                Kelola akun karyawan, role, divisi, dan status aktif/nonaktif dalam sistem cuti.
            </p>
        </div>

        <a href="{{ route('admin.manajemen_user.create') }}"
           class="inline-flex items-center justify-center gap-2 bg-sky-200 text-sky-900 font-semibold py-2.5 px-5 rounded-full
                  text-xs tracking-[0.16em] uppercase border border-sky-500 shadow-sm
                  hover:bg-sky-300 transition">
            <span class="text-lg leading-none">+</span>
            <span>Tambah User</span>
        </a>
    </div>

    {{-- Filter & Sorting --}}
    <form method="GET" class="mb-8 mt-6">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-[0_12px_30px_rgba(15,23,42,0.08)] p-4 grid grid-cols-1 md:grid-cols-4 gap-4">

            {{-- Role --}}
            <div>
                <label class="block text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-600 mb-1">
                    Role
                </label>
                <select name="role"
                        class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300 text-slate-900 bg-white">
                    <option value="">Semua</option>
                    <option value="Admin"  {{ ($roleFilter ?? '') === 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="User"   {{ ($roleFilter ?? '') === 'User' ? 'selected' : '' }}>Karyawan</option>
                    <option value="Leader" {{ ($roleFilter ?? '') === 'Leader' ? 'selected' : '' }}>Leader</option>
                    <option value="HRD"    {{ ($roleFilter ?? '') === 'HRD' ? 'selected' : '' }}>HRD</option>
                </select>
            </div>

            {{-- Divisi --}}
            <div>
                <label class="block text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-600 mb-1">
                    Divisi
                </label>
                <select name="division_id"
                        class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300 text-slate-900 bg-white">
                    <option value="">Semua</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}"
                            {{ ($divisionFilter ?? '') == $division->id ? 'selected' : '' }}>
                            {{ $division->nama_divisi }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-600 mb-1">
                    Status
                </label>
                <select name="status"
                        class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300 text-slate-900 bg-white">
                    <option value="">Semua</option>
                    <option value="active"   {{ ($statusFilter ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ ($statusFilter ?? '') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            {{-- Masa Kerja --}}
            <div>
                <label class="block text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-600 mb-1">
                    Masa Kerja
                </label>
                <select name="masa_kerja"
                        class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300 text-slate-900 bg-white">
                    <option value="">Semua</option>
                    <option value="lt1"  {{ ($masaKerjaFilter ?? '') === 'lt1' ? 'selected' : '' }}>&lt; 1 Tahun</option>
                    <option value="gte1" {{ ($masaKerjaFilter ?? '') === 'gte1' ? 'selected' : '' }}>&ge; 1 Tahun</option>
                </select>
            </div>
        </div>

        {{-- Sorting + Tombol --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mt-6">
            <div class="inline-flex items-center gap-2 bg-slate-50 px-3 py-2 rounded-xl border border-slate-200">
                <span class="text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-700">
                    Sortir
                </span>
                <select name="sort"
                        class="text-xs border border-slate-300 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-sky-300 text-slate-900 bg-white">
                    <option value="name"     {{ ($sort ?? '') === 'name' ? 'selected' : '' }}>Nama</option>
                    <option value="joined"   {{ ($sort ?? '') === 'joined' ? 'selected' : '' }}>Tanggal Bergabung</option>
                    <option value="division" {{ ($sort ?? '') === 'division' ? 'selected' : '' }}>Divisi</option>
                </select>
                <select name="direction"
                        class="text-xs border border-slate-300 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-sky-300 text-slate-900 bg-white">
                    <option value="asc"  {{ ($direction ?? '') === 'asc' ? 'selected' : '' }}>Naik</option>
                    <option value="desc" {{ ($direction ?? '') === 'desc' ? 'selected' : '' }}>Turun</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.manajemen_user.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2 rounded-full
                          bg-slate-50 text-slate-800 text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                          border border-slate-200 hover:bg-slate-100 transition">
                    Reset
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 bg-sky-200 text-sky-900 font-semibold py-2 px-4 rounded-full
                               text-xs tracking-[0.16em] uppercase border border-sky-500 shadow-sm
                               hover:bg-sky-300 transition">
                    Terapkan Filter
                </button>
            </div>
        </div>
    </form>

    {{-- TABLE USERS --}}
    <div class="mt-4 overflow-x-auto bg-white shadow-[0_14px_40px_rgba(15,23,42,0.08)] rounded-2xl border border-slate-200">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">No</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">Role</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">Divisi</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">Masa Kerja</th>
                    <th class="px-4 py-3 text-center text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                    @php
                        $divisionName = optional(optional($user->profile)->division)->nama_divisi ?? '-';
                        $joined       = \Carbon\Carbon::parse($user->created_at);
                        $years        = $joined->diffInYears(now());
                        $masaKerjaStr = $years < 1 ? '< 1 tahun' : $years.' tahun';
                        $statusAktif  = $user->profile->status_aktif ?? true;
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 text-xs text-slate-500">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-4 py-3 text-slate-900 font-semibold">
                            {{ $user->profile->nama_lengkap ?? $user->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-slate-700">
                            {{ $user->email }}
                        </td>
                        <td class="px-4 py-3 text-slate-700">
                            {{ $user->role }}
                        </td>
                        <td class="px-4 py-3 text-slate-700">
                            {{ $divisionName }}
                        </td>

                        {{-- STATUS BADGE --}}
                        <td class="px-4 py-3">
                            @if($statusAktif)
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700
                                             text-[0.7rem] font-semibold tracking-[0.12em] uppercase">
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-700
                                             text-[0.7rem] font-semibold tracking-[0.12em] uppercase">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>

                        {{-- MASA KERJA --}}
                        <td class="px-4 py-3 text-slate-700">
                            {{ $masaKerjaStr }}
                        </td>

                        {{-- ACTIONS --}}
<td class="px-4 py-3 text-center">
    <div class="inline-flex flex-wrap gap-2 justify-center">

        @if(in_array($user->role, ['Admin', 'HRD']))
            {{-- System User Tidak Bisa Diedit/Hapus --}}
            <span class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700
                         text-[0.7rem] font-semibold tracking-[0.12em] uppercase">
                System User
            </span>
        @else

            {{-- Edit --}}
            <a href="{{ route('admin.manajemen_user.edit', $user->id) }}"
               class="inline-flex items-center justify-center bg-amber-100 text-amber-900 py-1.5 px-3 rounded-lg text-[0.7rem] font-semibold
                      tracking-[0.12em] uppercase border border-amber-300 hover:bg-amber-200 transition">
                Edit
            </a>

            {{-- Toggle Aktif / Nonaktif --}}
            @if($statusAktif)
                <form action="{{ route('admin.manajemen_user.toggle_active', $user->id) }}"
                      method="POST"
                      onsubmit="return confirm('Nonaktifkan user ini?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="inline-flex items-center justify-center bg-slate-100 text-slate-900 py-1.5 px-3 rounded-lg text-[0.7rem]
                                   font-semibold tracking-[0.12em] uppercase border border-slate-300 hover:bg-slate-200 transition">
                        Nonaktifkan
                    </button>
                </form>
            @else
                <form action="{{ route('admin.manajemen_user.toggle_active', $user->id) }}"
                      method="POST"
                      onsubmit="return confirm('Aktifkan kembali user ini?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="inline-flex items-center justify-center bg-emerald-100 text-emerald-900 py-1.5 px-3 rounded-lg text-[0.7rem]
                                   font-semibold tracking-[0.12em] uppercase border border-emerald-300 hover:bg-emerald-200 transition">
                        Aktifkan
                    </button>
                </form>
            @endif

            {{-- Delete --}}
            <form action="{{ route('admin.manajemen_user.destroy', $user->id) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center justify-center bg-rose-100 text-rose-900 py-1.5 px-3 rounded-lg text-[0.7rem] font-semibold
                               tracking-[0.12em] uppercase border border-rose-300 hover:bg-rose-200 transition">
                    Delete
                </button>
            </form>

        @endif

    </div>
</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-sm text-slate-500">
                            Belum ada data pengguna.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
