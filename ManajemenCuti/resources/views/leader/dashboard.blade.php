@extends('layouts.app')

@section('title', 'Dashboard Ketua Divisi')

@section('content')
<div class="max-w-6xl mx-auto px-4 lg:px-0 py-8 space-y-8">

    {{-- ========= HEADER ========= --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="space-y-3">
            <div class="inline-flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl
                             bg-gradient-to-tr from-emerald-500 via-sky-500 to-indigo-500
                             text-[0.6rem] font-bold text-white tracking-[0.22em] uppercase shadow-md">
                    Leader
                </span>

                <div class="flex flex-col">
                    <span class="text-[0.7rem] tracking-[0.25em] text-slate-500 uppercase">
                        Dashboard Ketua Divisi
                    </span>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-slate-900 tracking-[0.18em] uppercase">
                        Overview Tim &amp; Cuti
                    </h1>
                </div>
            </div>
            <p class="text-xs sm:text-sm text-slate-600 max-w-2xl">
                Halo, {{ auth()->user()->name }} ✨ — pantau pengajuan cuti, status verifikasi,
                dan kondisi tim kamu dalam satu tampilan yang rapi.
            </p>
        </div>

        @if($division)
            <div class="w-full lg:w-auto">
                <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                            shadow-[0_20px_50px_rgba(15,23,42,0.18)] px-5 py-4 min-w-[260px]">
                    <p class="text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-slate-500">
                        Divisi
                    </p>
                    <p class="text-sm sm:text-base font-semibold text-slate-900 mt-1">
                        {{ $division->nama_divisi }}
                    </p>
                    @if($division->deskripsi)
                        <p class="text-[0.7rem] text-slate-500 mt-1">
                            {{ $division->deskripsi }}
                        </p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- ========= KALAU BELUM JADI KETUA DIVISI ========= --}}
    @if(!$division)
        <div class="rounded-3xl border border-amber-200 bg-amber-50 px-5 py-4 text-xs sm:text-sm text-amber-900 shadow-sm">
            <p class="font-semibold">
                Kamu belum terdaftar sebagai Ketua pada divisi manapun.
            </p>
            <p class="mt-1">
                Silakan hubungi Admin untuk mengatur kamu sebagai Ketua Divisi pada salah satu divisi.
            </p>
        </div>
    @else

        {{-- ========= KARTU STATISTIK ========= --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Pengajuan Masuk Bulan Ini --}}
            <div class="rounded-3xl bg-gradient-to-br from-emerald-50 via-emerald-100 to-emerald-50
                        border border-emerald-200 px-5 py-4 shadow-[0_16px_40px_rgba(16,185,129,0.25)]">
                <p class="text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-emerald-700">
                    Pengajuan Masuk Bulan Ini
                </p>
                <p class="mt-3 text-4xl font-bold text-emerald-700">
                    {{ $pengajuanMasuk ?? 0 }}
                </p>
                <p class="mt-1 text-[0.7rem] text-emerald-800">
                    Total pengajuan cuti dari anggota divisi di bulan berjalan.
                </p>
            </div>

            {{-- Menunggu Verifikasi --}}
            <a href="{{ route('verifications.index') }}"
               class="block rounded-3xl bg-gradient-to-br from-amber-50 via-yellow-100 to-amber-50
                      border border-amber-200 px-5 py-4 shadow-[0_16px_40px_rgba(234,179,8,0.25)]
                      hover:shadow-[0_20px_50px_rgba(202,138,4,0.35)] hover:-translate-y-[1px] transition">
                <p class="text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-amber-700">
                    Menunggu Verifikasi
                </p>
                <p class="mt-3 text-4xl font-bold text-amber-700">
                    {{ $pendingVerifikasi ?? 0 }}
                </p>
                <p class="mt-1 text-[0.7rem] text-amber-800">
                    Klik kartu ini untuk melihat pengajuan yang perlu kamu proses.
                </p>
            </a>

            {{-- Sedang Cuti Minggu Ini --}}
            <div class="rounded-3xl bg-gradient-to-br from-sky-50 via-blue-100 to-sky-50
                        border border-sky-200 px-5 py-4 shadow-[0_16px_40px_rgba(59,130,246,0.25)]">
                <p class="text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-sky-700">
                    Sedang Cuti Minggu Ini
                </p>
                <p class="mt-3 text-4xl font-bold text-sky-700">
                    {{ $sedangCuti ?? 0 }}
                </p>
                <p class="mt-1 text-[0.7rem] text-sky-800">
                    Jumlah anggota divisi yang sedang cuti pada minggu ini.
                </p>
            </div>
        </div>

        {{-- ========= ANGGOTA SEDANG CUTI MINGGU INI ========= --}}
        <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                    shadow-[0_20px_50px_rgba(15,23,42,0.15)] px-5 sm:px-7 py-5 space-y-4 mt-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <p class="text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-slate-500">
                        Monitoring Cuti
                    </p>
                    <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-slate-900">
                        Anggota yang Sedang Cuti Minggu Ini
                    </h2>
                </div>
                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full
                             text-[0.7rem] font-semibold
                             bg-slate-900 text-slate-50 tracking-[0.18em] uppercase">
                    Total: {{ isset($sedangCutiList) ? $sedangCutiList->count() : 0 }} Karyawan
                </span>
            </div>

            @if(isset($sedangCutiList) && $sedangCutiList->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Nama
                                </th>
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Jenis Cuti
                                </th>
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Periode
                                </th>
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Total Hari
                                </th>
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($sedangCutiList as $leave)
                                @php
                                    $status = $leave->status;
                                    $badgeClass = 'bg-slate-100 text-slate-700';
                                    if ($status === 'Approved by Leader') {
                                        $badgeClass = 'bg-blue-100 text-blue-700';
                                    } elseif ($status === 'Approved') {
                                        $badgeClass = 'bg-emerald-100 text-emerald-700';
                                    }
                                @endphp
                                <tr>
                                    <td class="px-4 py-2 text-slate-800">
                                        {{ $leave->user->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-slate-700 text-xs">
                                        {{ $leave->jenis_cuti }}
                                    </td>
                                    <td class="px-4 py-2 text-slate-700 text-xs">
                                        {{ optional($leave->tanggal_mulai)->format('d M Y') }}
                                        &mdash;
                                        {{ optional($leave->tanggal_selesai)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-2 text-slate-700 text-xs">
                                        {{ $leave->total_hari }} hari
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-[0.65rem] font-semibold {{ $badgeClass }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-xs sm:text-sm text-slate-500">
                    Tidak ada anggota yang sedang cuti pada minggu ini.
                </p>
            @endif
        </div>

        {{-- ========= DAFTAR ANGGOTA DIVISI ========= --}}
        <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                    shadow-[0_20px_50px_rgba(15,23,42,0.15)] px-5 sm:px-7 py-5 space-y-4 mt-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <p class="text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-slate-500">
                        Struktur Tim
                    </p>
                    <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-slate-900">
                        Anggota Divisi
                    </h2>
                </div>
                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full
                             text-[0.7rem] font-semibold
                             bg-slate-100 text-slate-700 tracking-[0.18em] uppercase border border-slate-200">
                    Total: {{ $anggota->count() }} Karyawan
                </span>
            </div>

            @if($anggota->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Nama
                                </th>
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Status
                                </th>
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Kuota Cuti
                                </th>
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Nomor Telepon
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($anggota as $profile)
                                <tr>
                                    <td class="px-4 py-2 text-slate-800">
                                        {{ $profile->nama_lengkap ?? $profile->user->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($profile->status_aktif)
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[0.65rem] font-semibold bg-emerald-100 text-emerald-700">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[0.65rem] font-semibold bg-rose-100 text-rose-700">
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-slate-700 text-xs">
                                        {{ $profile->kuota_cuti }} hari
                                    </td>
                                    <td class="px-4 py-2 text-slate-700 text-xs">
                                        {{ $profile->nomor_telepon ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-xs sm:text-sm text-slate-500">
                    Belum ada anggota yang terdaftar dalam divisi ini.
                </p>
            @endif
        </div>

    @endif {{-- end if division --}}

</div>
@endsection
