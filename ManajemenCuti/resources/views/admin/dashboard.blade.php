@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="flex items-center gap-3 text-2xl md:text-3xl font-semibold text-slate-900">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl
                             bg-gradient-to-tr from-orange-400 via-pink-500 to-sky-500
                             text-[0.6rem] font-bold text-white tracking-[0.18em] uppercase">
                    ADMIN
                </span>
                <span class="tracking-[0.12em] uppercase text-sm sm:text-base">
                    Dashboard Sistem Cuti
                </span>
            </h2>
            <p class="mt-2 text-xs text-slate-600">
                Ringkasan statistik karyawan, divisi, dan pengajuan cuti bulan ini.
            </p>
        </div>
    </div>

    {{-- GRID STATISTIK UTAMA – STYLE KANTORAN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        {{-- Total Karyawan --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.16em] uppercase">
                        Total Karyawan
                    </p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100
                             text-[0.65rem] font-medium text-slate-700">
                    Karyawan
                </span>
            </div>

            <p class="text-4xl font-semibold text-slate-900 leading-tight">
                {{ $totalKaryawan }}
            </p>

            <div class="flex flex-col gap-1 text-[0.75rem] text-slate-700">
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
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.16em] uppercase">
                    Total Divisi
                </p>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50
                             text-[0.65rem] font-medium text-sky-700">
                    Struktur Organisasi
                </span>
            </div>

            <p class="text-4xl font-semibold text-slate-900 leading-tight">
                {{ $totalDivisi }}
            </p>

            <p class="text-[0.75rem] text-slate-700">
                Kelola struktur dan leader divisi dari menu <span class="font-medium">Manajemen Divisi</span>.
            </p>
        </div>

        {{-- Pengajuan Cuti Bulan Ini --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.16em] uppercase">
                    Pengajuan Cuti Bulan Ini
                </p>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-amber-50
                             text-[0.65rem] font-medium text-amber-700">
                    Periode Berjalan
                </span>
            </div>

            <p class="text-4xl font-semibold text-slate-900 leading-tight">
                {{ $cutiBulanIni }}
            </p>

            <p class="text-[0.75rem] text-slate-700">
                Pending approval:
                <span class="font-semibold text-amber-700">{{ $pendingBulanIni }}</span>
            </p>
        </div>

        {{-- Masa Kerja < 1 Tahun --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.16em] uppercase">
                    Masa Kerja &lt; 1 Tahun
                </p>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-violet-50
                             text-[0.65rem] font-medium text-violet-700">
                    Masa Adaptasi
                </span>
            </div>

            <p class="text-4xl font-semibold text-slate-900 leading-tight">
                {{ $masaKerjaKurangSetahun }}
            </p>

            <p class="text-[0.75rem] text-slate-700">
                Karyawan yang belum eligible cuti tahunan penuh sesuai kebijakan perusahaan.
            </p>
        </div>

    </div>

    {{-- LIST KARYAWAN TERBARU (tetap versi “rapi kantor”) --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold tracking-[0.14em] uppercase text-slate-800">
                Karyawan Terbaru
            </h3>
            <span class="text-[0.7rem] text-slate-500">
                Total: {{ $karyawanTerbaru->count() }}
            </span>
        </div>

        @if($karyawanTerbaru->isEmpty())
            <p class="px-5 py-4 text-sm text-slate-600">
                Belum ada data karyawan.
            </p>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($karyawanTerbaru as $user)
                    @php
                        $divisionName = optional(optional($user->profile)->division)->nama_divisi ?? '-';
                        $joinedAt     = \Carbon\Carbon::parse($user->created_at)->format('d M Y');
                        $fullName     = optional($user->profile)->nama_lengkap ?? $user->name ?? 'Tanpa Nama';
                        $initial      = strtoupper(mb_substr($fullName, 0, 1));
                        $isActive     = optional($user->profile)->status_aktif ?? true;
                    @endphp

                    <div class="px-5 py-3 hover:bg-slate-50 transition">
                        <div class="grid grid-cols-1 sm:grid-cols-[minmax(0,1.7fr)_minmax(0,1.2fr)_auto] gap-3 items-center">

                            {{-- AVATAR + NAMA + ROLE --}}
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-2xl bg-slate-900/90 text-white text-sm font-semibold
                                            flex items-center justify-center shadow-sm">
                                    {{ $initial }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $fullName }}
                                    </p>
                                    <p class="text-[0.75rem] text-slate-600">
                                        Role: <span class="font-medium">{{ $user->role }}</span>
                                    </p>
                                </div>
                            </div>

                            {{-- DIVISI + STATUS --}}
                      <div class="grid grid-cols-1 sm:grid-cols-[1.7fr_1.2fr_1fr] gap-3 items-center">            

                                    {{-- DIVISI --}}
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                                    bg-slate-100 text-[0.7rem] font-medium text-slate-700 w-fit">
                                            Divisi: {{ $divisionName }}
                                        </span>
                                    </div>

                                    {{-- STATUS --}}
                                    <div class="flex sm:justify-start">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[0.7rem] font-semibold
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
                                    <span class="inline-flex items-center px-3 py-1 rounded-full
                                                bg-slate-100 text-[0.7rem] font-medium text-slate-700">
                                        Bergabung: {{ $joinedAt }}
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
