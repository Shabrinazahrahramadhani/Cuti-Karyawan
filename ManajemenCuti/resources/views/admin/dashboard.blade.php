@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<style>
    @keyframes fadeInUpSoft {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim-fade-up      { animation: fadeInUpSoft .45s ease-out forwards; }
    .anim-fade-up-del1 { animation: fadeInUpSoft .55s ease-out forwards; animation-delay: .06s; }
    .anim-fade-up-del2 { animation: fadeInUpSoft .65s ease-out forwards; animation-delay: .12s; }
    .anim-fade-up-del3 { animation: fadeInUpSoft .75s ease-out forwards; animation-delay: .18s; }

    .card-kantor {
        border-radius: 1.4rem;
        box-shadow: 0 18px 40px rgba(15,23,42,0.18);
    }

    .card-kantor:hover {
        transform: translateY(-2px);
        box-shadow: 0 24px 55px rgba(15,23,42,0.28);
    }
</style>

<div class="max-w-6xl mx-auto mt-10 px-4 anim-fade-up">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="space-y-2">
            <h2 class="flex items-center gap-3 text-2xl md:text-3xl font-semibold text-slate-900">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl
                             bg-gradient-to-tr from-sky-500 via-indigo-500 to-violet-500
                             text-[0.6rem] font-bold text-white tracking-[0.24em] uppercase shadow-md">
                    ADMIN
                </span>
                <span class="tracking-[0.18em] uppercase text-sm sm:text-base text-slate-800">
                    Dashboard Sistem Cuti
                </span>
            </h2>
            <p class="text-xs text-slate-500">
                Ringkasan statistik karyawan, divisi, dan pengajuan cuti bulan ini.
            </p>
        </div>

        <div class="inline-flex items-center gap-2 px-3 py-2 rounded-2xl bg-slate-900 text-slate-100 text-[0.7rem]">
            <span class="inline-flex w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="tracking-[0.22em] uppercase">Admin </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        {{-- Total Karyawan --}}
        <div class="bg-white/95 border border-slate-200 card-kantor p-5 flex flex-col gap-3 transition anim-fade-up">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.18em] uppercase">
                        Total Karyawan
                    </p>
                    <p class="mt-1 text-[0.7rem] text-slate-500">
                        Rekap seluruh karyawan terdaftar.
                    </p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-900 text-slate-50
                             text-[0.65rem] font-semibold uppercase tracking-[0.18em]">
                    Karyawan
                </span>
            </div>

            <p class="text-4xl font-semibold text-slate-900 leading-tight">
                {{ $totalKaryawan }}
            </p>

            <div class="flex flex-col gap-1 text-[0.75rem] text-slate-700 mt-1">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Aktif:
                        <span class="font-semibold text-emerald-700">{{ $aktifKaryawan }}</span>
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    <span>Tidak Aktif:
                        <span class="font-semibold text-rose-700">{{ $nonAktifKaryawan }}</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Total Divisi --}}
        <div class="bg-white/95 border border-slate-200 card-kantor p-5 flex flex-col gap-3 transition anim-fade-up-del1">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.18em] uppercase">
                        Total Divisi
                    </p>
                    <p class="mt-1 text-[0.7rem] text-slate-500">
                        Struktur organisasi aktif di perusahaan.
                    </p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-sky-50
                             text-[0.65rem] font-semibold text-sky-700">
                    Struktur Organisasi
                </span>
            </div>

            <p class="text-4xl font-semibold text-slate-900 leading-tight">
                {{ $totalDivisi }}
            </p>

            <p class="text-[0.75rem] text-slate-700 mt-1">
                Kelola struktur &amp; leader dari menu
                <span class="font-medium text-slate-900">Manajemen Divisi</span>.
            </p>
        </div>

        {{-- Pengajuan Cuti Bulan Ini --}}
        <div class="bg-white/95 border border-slate-200 card-kantor p-5 flex flex-col gap-3 transition anim-fade-up-del2">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.18em] uppercase">
                        Pengajuan Cuti Bulan Ini
                    </p>
                    <p class="mt-1 text-[0.7rem] text-slate-500">
                        Total pengajuan yang masuk di periode berjalan.
                    </p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-amber-50
                             text-[0.65rem] font-semibold text-amber-700">
                    Periode Berjalan
                </span>
            </div>

            <p class="text-4xl font-semibold text-slate-900 leading-tight">
                {{ $cutiBulanIni }}
            </p>

            <p class="text-[0.75rem] text-slate-700 mt-1">
                Pending approval:
                <span class="font-semibold text-amber-700">{{ $pendingBulanIni }}</span>
            </p>
        </div>

        {{-- Masa Kerja < 1 Tahun --}}
        <div class="bg-white/95 border border-slate-200 card-kantor p-5 flex flex-col gap-3 transition anim-fade-up-del3">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.18em] uppercase">
                        Masa Kerja &lt; 1 Tahun
                    </p>
                    <p class="mt-1 text-[0.7rem] text-slate-500">
                        Karyawan dalam fase adaptasi awal.
                    </p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-violet-50
                             text-[0.65rem] font-semibold text-violet-700">
                    Masa Adaptasi
                </span>
            </div>

            <p class="text-4xl font-semibold text-slate-900 leading-tight">
                {{ $masaKerjaKurangSetahun }}
            </p>

            <p class="text-[0.75rem] text-slate-700 mt-1">
                Belum eligible cuti tahunan penuh sesuai kebijakan perusahaan.
            </p>
        </div>

    </div>

    {{-- KARYAWAN TERBARU --}}
    <div class="bg-white/95 border border-slate-200 rounded-3xl shadow-[0_16px_40px_rgba(15,23,42,0.12)] overflow-hidden anim-fade-up-del2">
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-900 text-white text-[0.7rem] font-bold">
                    +
                </span>
                <h3 class="text-sm font-semibold tracking-[0.14em] uppercase text-slate-800">
                    Karyawan Terbaru
                </h3>
            </div>
            <span class="text-[0.7rem] text-slate-500">
                Total: {{ $karyawanTerbaru->count() }}
            </span>
        </div>

        @if($karyawanTerbaru->isEmpty())
            <p class="px-5 py-5 text-sm text-slate-600">
                Belum ada data karyawan.
            </p>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($karyawanTerbaru as $user)
                    @php
                        $profile      = optional($user->profile);
                        $divisionName = optional($profile->division)->nama_divisi ?? '-';
                        $joinedAt     = \Carbon\Carbon::parse($user->created_at)->format('d M Y');
                        $fullName     = $profile->nama_lengkap ?? $user->name ?? 'Tanpa Nama';
                        $initial      = strtoupper(mb_substr($fullName, 0, 1));
                        $isActive     = $profile->status_aktif ?? true;
                        $fotoUrl      = $profile && $profile->foto ? asset('storage/'.$profile->foto) : null;
                    @endphp

                    <div class="px-5 py-3 hover:bg-slate-50/80 transition">
                        <div class="grid grid-cols-1 sm:grid-cols-[minmax(0,1.7fr)_minmax(0,1.6fr)_auto] gap-3 items-center">

                            {{-- AVATAR + NAMA + ROLE --}}
                            <div class="flex items-center gap-3">
                                @if($fotoUrl)
                                    <div class="w-10 h-10 rounded-2xl overflow-hidden bg-slate-200 flex items-center justify-center shadow-sm ring-1 ring-slate-200">
                                        <img src="{{ $fotoUrl }}"
                                             alt="Foto {{ $fullName }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-2xl bg-slate-900/95 text-white text-sm font-semibold
                                                flex items-center justify-center shadow-sm ring-1 ring-slate-700">
                                        {{ $initial }}
                                    </div>
                                @endif

                                <div>
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $fullName }}
                                    </p>
                                    <p class="text-[0.75rem] text-slate-600">
                                        Role: <span class="font-medium">{{ $user->role }}</span>
                                    </p>
                                </div>
                            </div>

                            {{-- DIVISI & STATUS --}}
                            <div class="grid grid-cols-1 sm:grid-cols-[1.5fr_1.2fr] gap-3 items-center">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full
                                                bg-slate-100 text-[0.7rem] font-medium text-slate-700 w-fit">
                                        Divisi: {{ $divisionName }}
                                    </span>
                                </div>

                                <div class="flex sm:justify-start">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[0.7rem] font-semibold
                                                {{ $isActive
                                                    ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                                    : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full
                                                    {{ $isActive ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        {{ $isActive ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            </div>

                            {{-- TANGGAL BERGABUNG --}}
                            <div class="sm:text-right mt-2 sm:mt-0">
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full
                                            bg-slate-100 text-[0.7rem] font-medium text-slate-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M8 7V4m8 3V4M4 9h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1z"/>
                                    </svg>
                                    <span>Bergabung: {{ $joinedAt }}</span>
                                </span>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
