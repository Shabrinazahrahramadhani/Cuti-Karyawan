@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto px-4 lg:px-0 py-8 space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl
                             bg-gradient-to-tr from-sky-600 via-indigo-500 to-emerald-500
                             text-[0.6rem] font-bold text-white tracking-[0.22em] uppercase shadow-md">
                    Profil
                </span>

                <div class="flex flex-col">
                    <span class="text-[0.7rem] tracking-[0.25em] text-slate-500 uppercase">
                        Akun & Data Pribadi
                    </span>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-slate-900 tracking-[0.18em] uppercase">
                        Profil Saya
                    </h1>
                </div>
            </div>
            <p class="text-xs sm:text-sm text-slate-600 max-w-md">
                Lihat informasi akun, data profil, dan kuota cuti kamu di halaman ini.
            </p>
        </div>

        <a href="{{ route('profile.edit') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-full
                  bg-slate-900 text-white text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                  shadow-[0_12px_30px_rgba(15,23,42,0.75)]
                  hover:bg-slate-800 hover:-translate-y-[1px] transition">
            Edit Profil
        </a>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs sm:text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- KONTEN UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] gap-6 items-start">

        {{-- KIRI: DATA AKUN --}}
        <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                    shadow-[0_18px_45px_rgba(15,23,42,0.15)] px-6 py-5 space-y-4">
            <h2 class="text-sm font-semibold text-slate-900 tracking-[0.18em] uppercase">
                Data Akun
            </h2>

            <div class="flex items-center gap-4">
                <div class="relative">
                    @if($profile && $profile->foto)
                        <img src="{{ asset('storage/' . $profile->foto) }}"
                             alt="Foto Profil"
                             class="w-16 h-16 rounded-2xl object-cover border border-slate-200 shadow-md">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-2xl font-semibold shadow-md">
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-900">
                        {{ $profile->nama_lengkap ?? $user->name ?? '-' }}
                    </p>
                    <p class="text-xs text-slate-500">
                        {{ $user->email }}
                    </p>
                    <p class="text-[0.7rem] text-slate-500">
                        Role:
                        <span class="font-semibold text-slate-800">
                            {{ ucfirst($user->role ?? 'user') }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="h-px bg-gradient-to-r from-slate-100 via-slate-200 to-slate-100 my-3"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm text-slate-700">
                <div class="space-y-1">
                    <p class="text-[0.7rem] uppercase tracking-[0.16em] text-slate-500 font-semibold">
                        Nomor Telepon
                    </p>
                    <p>{{ $profile->nomor_telepon ?? '-' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[0.7rem] uppercase tracking-[0.16em] text-slate-500 font-semibold">
                        Alamat
                    </p>
                    <p>{{ $profile->alamat ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- KANAN: INFO CUTI --}}
        <div class="space-y-4">

            <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                        shadow-[0_16px_40px_rgba(15,23,42,0.15)] px-6 py-5 space-y-4">
                <h2 class="text-sm font-semibold text-slate-900 tracking-[0.18em] uppercase">
                    Informasi Cuti Tahunan
                </h2>

                <div class="grid grid-cols-2 gap-4 text-xs sm:text-sm">
                    <div class="space-y-1">
                        <p class="text-[0.7rem] uppercase tracking-[0.16em] text-slate-500 font-semibold">
                            Kuota Tahunan
                        </p>
                        <p class="text-2xl font-bold text-emerald-600">
                            {{ $profile->kuota_cuti ?? 0 }}
                            <span class="text-xs text-slate-500 font-semibold">hari</span>
                        </p>
                        <p class="text-[0.68rem] text-slate-500">
                            Default: 12 hari kerja per tahun (tidak termasuk Sabtu &amp; Minggu).
                        </p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-[0.7rem] uppercase tracking-[0.16em] text-slate-500 font-semibold">
                            Status Akun
                        </p>
                        @if($profile && $profile->status_aktif)
                            <span class="inline-flex px-3 py-1 rounded-full text-[0.68rem] font-semibold bg-emerald-100 text-emerald-700">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 rounded-full text-[0.68rem] font-semibold bg-rose-100 text-rose-700">
                                Tidak Aktif
                            </span>
                        @endif
                        @if(optional($profile->division)->nama_divisi)
                            <p class="text-[0.68rem] text-slate-500 mt-1">
                                Divisi:
                                <span class="font-semibold text-slate-700">
                                    {{ $profile->division->nama_divisi }}
                                </span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
