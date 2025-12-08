@extends('layouts.app')

@section('title', 'Detail Divisi & Anggota')

@section('content')

<style>
    @keyframes fadeInUpSoft {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim-page { animation: fadeInUpSoft .4s ease-out forwards; }
</style>

<div class="max-w-6xl mx-auto mt-10 px-4 anim-page">

    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm">
            <span class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold">
                ✓
            </span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 flex items-center gap-3 px-4 py-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-sm">
            <span class="w-7 h-7 rounded-full bg-rose-500 text-white flex items-center justify-center text-xs font-bold">
                !
            </span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full
                             bg-sky-100 text-[0.7rem] font-bold text-slate-900 tracking-[0.18em] uppercase">
                    DIV
                </span>
                <span class="tracking-[0.08em]">{{ $division->nama_divisi }}</span>
            </h2>
            <p class="mt-1 text-xs text-slate-500">
                Leader: {{ $division->ketuaDivisi?->profile?->nama_lengkap ?? $division->ketuaDivisi?->name ?? '-' }}
            </p>
        </div>

        <a href="{{ route('admin.division.index') }}"
           class="px-4 py-2 rounded-full bg-white text-slate-700 text-xs font-semibold tracking-[0.16em] uppercase
                  border border-slate-200 hover:bg-slate-50 transition">
            Kembali
        </a>
    </div>

    <div class="grid md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-[0_10px_26px_rgba(15,23,42,0.06)] border border-slate-200 p-4">
            <div class="text-xs text-slate-500 tracking-[0.16em] uppercase mb-1">
                Leader
            </div>
            <div class="text-sm font-semibold text-slate-900">
                {{ $division->ketuaDivisi?->profile?->nama_lengkap ?? $division->ketuaDivisi?->name ?? '-' }}
            </div>
            <p class="text-[0.7rem] text-slate-400 mt-1">
                ID: {{ $division->ketua_divisi_id ?? '-' }}
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_10px_26px_rgba(15,23,42,0.06)] border border-slate-200 p-4">
            <div class="text-xs text-slate-500 tracking-[0.16em] uppercase mb-1">
                Jumlah Anggota
            </div>
            <div class="text-3xl font-bold text-slate-900">
                {{ $members->count() }}
            </div>
            <p class="text-[0.7rem] text-slate-400 mt-1">
                Termasuk semua status aktif/nonaktif.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_10px_26px_rgba(15,23,42,0.06)] border border-slate-200 p-4">
            <div class="text-xs text-slate-500 tracking-[0.16em] uppercase mb-1">
                Dibentuk
            </div>
            <div class="text-sm font-semibold text-slate-900">
                {{ $division->created_at?->format('d M Y') ?? '-' }}
            </div>
            <p class="text-[0.7rem] text-slate-400 mt-1">
                {{ $division->deskripsi ?: 'Tidak ada deskripsi.' }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-[0_10px_26px_rgba(15,23,42,0.06)] border border-slate-200 p-4 mb-6">
        <h3 class="text-sm font-semibold text-slate-900 mb-3 tracking-[0.14em] uppercase">
            Tambah Anggota Divisi
        </h3>
        <form action="{{ route('admin.division.members.add', $division->id) }}" method="POST"
              class="flex flex-col md:flex-row gap-3 items-start md:items-center">
            @csrf
            <select name="user_profile_id"
                    class="flex-1 border border-slate-300 rounded-xl px-3 py-2 text-xs bg-white
                           focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                <option value="">Pilih karyawan (User) tanpa divisi</option>
                @foreach($candidateProfiles as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->nama_lengkap }} ({{ $p->user->email }})
                    </option>
                @endforeach
            </select>
            <button type="submit"
                    class="px-4 py-2 rounded-xl bg-sky-600 text-white text-xs font-semibold tracking-[0.16em] uppercase
                           shadow-[0_10px_24px_rgba(37,99,235,0.45)] hover:bg-sky-700 transition">
                Tambah
            </button>
        </form>
        @if($candidateProfiles->isEmpty())
            <p class="mt-2 text-[0.7rem] text-slate-400">
                Tidak ada karyawan yang belum memiliki divisi.
            </p>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-[0_12px_32px_rgba(15,23,42,0.08)] border border-slate-200 p-4">
        <h3 class="text-sm font-semibold text-slate-900 mb-3 tracking-[0.14em] uppercase">
            Anggota Divisi
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">No</th>
                        <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">Nama</th>
                        <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">Email</th>
                        <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">Status</th>
                        <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">Masa Kerja</th>
                        <th class="px-4 py-3 text-center text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($members as $member)
                        @php
                            $user  = $member->user;
                            $label = $member->masa_kerja_label;
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 align-top text-xs text-slate-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-4 py-3 align-top text-slate-900">
                                {{ $member->nama_lengkap ?? $user->name }}
                            </td>
                            <td class="px-4 py-3 align-top text-slate-700">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-3 align-top">
                                @if($member->status_aktif)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[0.7rem] font-semibold">
                                        ● Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[0.7rem] font-semibold">
                                        ● Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top text-xs text-slate-600">
                                {{ $label ?? '-' }}
                            </td>
                            <td class="px-4 py-3 align-top text-center">
                                <form action="{{ route('admin.division.members.remove', [$division->id, $member->id]) }}"
                                      method="POST"
                                      onsubmit="return confirm('Keluarkan anggota ini dari divisi?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-rose-500 text-white text-[0.7rem] font-semibold tracking-[0.12em] uppercase hover:bg-rose-600 transition">
                                        Keluarkan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-500">
                                Belum ada anggota di divisi ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
