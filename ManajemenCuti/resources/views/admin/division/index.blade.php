@extends('layouts.app')

@section('title', 'Manajemen Divisi')

@section('content')
<div class="max-w-6xl mx-auto px-4 lg:px-0 py-8 space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full
                             bg-gradient-to-tr from-sky-500 via-blue-500 to-indigo-500
                             text-[0.55rem] font-bold text-white tracking-[0.22em] uppercase shadow-md">
                    Admin
                </span>

                <div class="flex flex-col">
                    <span class="text-[0.7rem] tracking-[0.25em] text-slate-500 uppercase">
                        Manajemen Cuti &amp; Approval
                    </span>
                    <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-slate-900 tracking-[0.18em] uppercase">
                        Manajemen Divisi
                    </h2>
                </div>
            </div>
            <p class="text-xs sm:text-sm text-slate-500">
                Kelola struktur divisi, leader, dan anggota.
            </p>
        </div>

        <a href="{{ route('admin.division.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full
                  bg-sky-50 text-sky-700 text-[0.7rem] font-semibold border border-sky-300
                  tracking-[0.18em] uppercase shadow-sm
                  hover:bg-sky-100 hover:border-sky-400 hover:-translate-y-[1px] transition">
            <span class="text-base leading-none">+</span>
            <span>Tambah Divisi</span>
        </a>
    </div>

    <form method="GET" class="space-y-4">
        <div class="bg-white/95 backdrop-blur border border-slate-200 rounded-3xl
                    shadow-[0_18px_40px_rgba(15,23,42,0.12)] px-5 sm:px-7 py-5
                    grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="space-y-1">
                <label class="block text-[0.68rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                    Nama Divisi
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m21 21-4.35-4.35M11 18a7 7 0 1 0 0-14 7 7 0 0 0 0 14Z" />
                        </svg>
                    </span>
                    <input type="text" name="nama" value="{{ $namaFilter }}"
                           class="w-full text-xs rounded-full border border-slate-300 bg-slate-50/80
                                  pl-8 pr-3 py-2 text-slate-800
                                  focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                           placeholder="Cari nama divisi...">
                </div>
            </div>

            <div class="space-y-1">
                <label class="block text-[0.68rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                    Leader
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.5 20.25a7.5 7.5 0 0 1 15 0" />
                        </svg>
                    </span>
                    <select name="leader_id"
                            class="w-full text-xs rounded-full border border-slate-300 bg-slate-50/80
                                   pl-8 pr-6 py-2 text-slate-800 appearance-none
                                   focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="">Semua</option>
                        @foreach($leaders as $leader)
                            <option value="{{ $leader->id }}" {{ ($leaderFilter ?? '') == $leader->id ? 'selected' : '' }}>
                                {{ $leader->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <svg class="w-3 h-3 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.106l3.71-3.875a.75.75 0 0 1 1.08 1.04l-4.25 4.44a.75.75 0 0 1-1.08 0l-4.25-4.44a.75.75 0 0 1 .02-1.06Z"
                                  clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
            </div>

            <div class="space-y-1">
                <label class="block text-[0.68rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                    Jumlah Anggota
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6.75 7.5A2.25 2.25 0 1 0 4.5 5.25 2.25 2.25 0 0 0 6.75 7.5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17.25 7.5A2.25 2.25 0 1 0 15 5.25 2.25 2.25 0 0 0 17.25 7.5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 18.75a3.75 3.75 0 0 1 7.5 0v.75H3.75A.75.75 0 0 1 3 18.75Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M13.5 19.5v-.75a3.75 3.75 0 0 1 7.5 0v.75H13.5Z" />
                        </svg>
                    </span>
                    <select name="members"
                            class="w-full text-xs rounded-full border border-slate-300 bg-slate-50/80
                                   pl-8 pr-6 py-2 text-slate-800 appearance-none
                                   focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="">Semua</option>
                        <option value="has"   {{ ($memberFilter ?? '') === 'has' ? 'selected' : '' }}>Ada Anggota</option>
                        <option value="empty" {{ ($memberFilter ?? '') === 'empty' ? 'selected' : '' }}>Belum Ada Anggota</option>
                    </select>
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <svg class="w-3 h-3 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.106l3.71-3.875a.75.75 0 0 1 1.08 1.04l-4.25 4.44a.75.75 0 0 1-1.08 0l-4.25-4.44a.75.75 0 0 1 .02-1.06Z"
                                  clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
            </div>

            <div class="space-y-1">
                <label class="block text-[0.68rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                    Sortir
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7h12M8 12h7M8 17h4M4 7h.01M4 12h.01M4 17h.01" />
                        </svg>
                    </span>
                    <select name="sort"
                            class="w-full text-xs rounded-full border border-slate-300 bg-slate-50/80
                                   pl-8 pr-6 py-2 text-slate-800 appearance-none
                                   focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="nama_asc"     {{ $sort === 'nama_asc' ? 'selected' : '' }}>Nama (A–Z)</option>
                        <option value="nama_desc"    {{ $sort === 'nama_desc' ? 'selected' : '' }}>Nama (Z–A)</option>
                        <option value="members_asc"  {{ $sort === 'members_asc' ? 'selected' : '' }}>Anggota (Sedikit → Banyak)</option>
                        <option value="members_desc" {{ $sort === 'members_desc' ? 'selected' : '' }}>Anggota (Banyak → Sedikit)</option>
                        <option value="created_asc"  {{ $sort === 'created_asc' ? 'selected' : '' }}>Tanggal Dibentuk (Lama → Baru)</option>
                        <option value="created_desc" {{ $sort === 'created_desc' ? 'selected' : '' }}>Tanggal Dibentuk (Baru → Lama)</option>
                    </select>
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <svg class="w-3 h-3 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.106l3.71-3.875a.75.75 0 0 1 1.08 1.04l-4.25 4.44a.75.75 0 0 1-1.08 0l-4.25-4.44a.75.75 0 0 1 .02-1.06Z"
                                  clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full
                           bg-sky-50 text-sky-700 text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                           border border-sky-300 shadow-sm
                           hover:bg-sky-100 hover:border-sky-400 hover:-translate-y-[1px] transition">
                Terapkan Filter
            </button>

            <a href="{{ route('admin.division.index') }}"
               class="inline-flex items-center justify-center px-5 py-2.5 rounded-full
                      bg-white text-slate-600 text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                      border border-slate-300 shadow-sm hover:bg-slate-50 hover:border-slate-400 transition">
                Reset
            </a>
        </div>
    </form>

    <div class="space-y-4">
        @forelse($divisions as $division)
            <article
                class="rounded-3xl bg-white border border-slate-200 overflow-hidden
                       shadow-[0_18px_40px_rgba(15,23,42,0.12)]">

                <div class="h-1.5 bg-gradient-to-r from-sky-500 via-blue-500 to-indigo-500"></div>

                <div class="px-5 sm:px-7 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <h3 class="font-semibold text-slate-900 tracking-[0.18em] uppercase text-xs sm:text-sm">
                                {{ $division->nama_divisi ?? 'Tanpa Nama' }}
                            </h3>

                            @if($division->members_count > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                             bg-emerald-50 border border-emerald-200 text-[0.65rem] text-emerald-700 font-semibold">
                                    {{ $division->members_count }} anggota
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                             bg-slate-50 border border-slate-200 text-[0.65rem] text-slate-500 font-medium">
                                    Belum ada anggota
                                </span>
                            @endif
                        </div>

                        @if(!empty($division->deskripsi))
                            <p class="text-xs text-slate-600">
                                {{ $division->deskripsi }}
                            </p>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1 text-xs text-slate-600 mt-1">
                            <p>
                                <span class="text-slate-500">Leader:</span>
                                <span class="font-semibold">
                                    {{ optional($division->ketuaDivisi)->name ?? '-' }}
                                </span>
                            </p>

                            <p>
                                <span class="text-slate-500">Dibentuk:</span>
                                <span class="font-semibold">
                                    {{ optional($division->created_at)->format('d M Y') ?? '-' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:items-end gap-2">

                        <a href="{{ route('admin.division.edit', $division->id) }}"
                           class="inline-flex items-center justify-center px-4 py-1.5 rounded-full
                                  bg-amber-50 text-amber-700 text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                                  border border-amber-200 shadow-sm
                                  hover:bg-amber-100 hover:border-amber-300 hover:-translate-y-[1px] transition">
                            Edit Divisi
                        </a>

                        <a href="{{ route('admin.division.members', $division->id ?? $division->id) ?? '#' }}"
                           class="inline-flex items-center justify-center px-4 py-1.5 rounded-full
                                  bg-emerald-50 text-emerald-700 text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                                  border border-emerald-200 shadow-sm
                                  hover:bg-emerald-100 hover:border-emerald-300 hover:-translate-y-[1px] transition">
                            Kelola Anggota
                        </a>

                        <form action="{{ route('admin.division.destroy', $division->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus divisi ini? Semua anggota akan kehilangan atribut divisi.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-4 py-1.5 rounded-full
                                           bg-rose-50 text-rose-700 text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                                           border border-rose-200 shadow-sm
                                           hover:bg-rose-100 hover:border-rose-300 hover:-translate-y-[1px] transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <p class="text-sm text-slate-500 text-center py-10">
                Belum ada data divisi yang terdaftar.
            </p>
        @endforelse
    </div>

</div>
@endsection
