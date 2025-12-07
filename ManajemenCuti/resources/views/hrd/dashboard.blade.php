@extends('layouts.app')

@section('title', 'Dashboard HRD')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="flex items-center gap-3 text-2xl md:text-3xl font-semibold text-slate-900">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl
                             bg-gradient-to-tr from-orange-400 via-pink-500 to-sky-500
                             text-[0.6rem] font-bold text-white tracking-[0.18em] uppercase">
                    HRD
                </span>
                <span class="tracking-[0.12em] uppercase text-sm sm:text-base">
                    Dashboard HRD
                </span>
            </h2>

            <p class="mt-2 text-xs text-slate-600">
                Ringkasan pengajuan cuti, status approval, dan aktivitas karyawan bulan ini.
            </p>
        </div>

        <a href="{{ route('hrd.reports.index') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-full
                  bg-sky-100 text-sky-800 text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                  border border-sky-300 hover:bg-sky-200 transition">
            Lihat Laporan Cuti
        </a>
    </div>

    {{-- GRID STATISTIK UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-5 mb-8">

        {{-- Total pengajuan cuti bulan ini --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex flex-col gap-3">
            <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.16em] uppercase">
                Pengajuan Cuti Bulan Ini
            </p>
            <p class="text-4xl font-semibold text-slate-900 leading-tight">
                {{ $totalCutiBulanIni }}
            </p>
            <p class="text-[0.75rem] text-slate-700">
                Total semua jenis cuti yang diajukan pada bulan berjalan.
            </p>
        </div>

        {{-- Pending final approval --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex flex-col gap-3">
            <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.16em] uppercase">
                Menunggu Final Approval
            </p>
            <p class="text-4xl font-semibold text-amber-600 leading-tight">
                {{ $pendingFinal->count() }}
            </p>
            <p class="text-[0.75rem] text-slate-700">
                Pengajuan yang sudah diverifikasi atasan / ketua divisi dan menunggu keputusan HRD.
            </p>
            <a href="{{ route('approvals.index') }}"
               class="inline-flex items-center justify-center mt-2 px-3 py-1.5 rounded-full
                      bg-amber-50 text-amber-800 text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                      border border-amber-200 hover:bg-amber-100 transition">
                Kelola Approval
            </a>
        </div>

        {{-- Sedang cuti bulan ini --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex flex-col gap-3">
            <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.16em] uppercase">
                Karyawan Sedang Cuti (Bulan Ini)
            </p>
            <p class="text-4xl font-semibold text-emerald-600 leading-tight">
                {{ $sedangCutiBulanIni->count() }}
            </p>
            <p class="text-[0.75rem] text-slate-700">
                Termasuk semua divisi &amp; jenis cuti yang statusnya disetujui.
            </p>
        </div>

        {{-- Total divisi --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex flex-col gap-3">
            <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.16em] uppercase">
                Total Divisi
            </p>
            <p class="text-4xl font-semibold text-indigo-600 leading-tight">
                {{ $divisions->count() }}
            </p>
            <p class="text-[0.75rem] text-slate-700">
                Struktur organisasi dan ketua divisi dapat dipantau dari menu Data Divisi.
            </p>
        </div>

    </div>

    <div class="grid md:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)] gap-6">

        {{-- Daftar pending final approval (ringkas) --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-sm font-semibold tracking-[0.14em] uppercase text-slate-800">
                    Menunggu Final Approval HRD
                </h3>
                <a href="{{ route('approvals.index') }}"
                   class="text-[0.7rem] font-semibold tracking-[0.12em] uppercase text-sky-700 hover:text-sky-900">
                    Lihat Semua →
                </a>
            </div>

            @if($pendingFinal->isEmpty())
                <p class="px-5 py-4 text-xs text-slate-500">
                    Tidak ada pengajuan yang menunggu final approval saat ini.
                </p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($pendingFinal->take(5) as $req)
                        @php
                            $nama   = optional(optional($req->user)->profile)->nama_lengkap
                                      ?? optional($req->user)->name
                                      ?? '-';
                            $divisi = optional(optional(optional($req->user)->profile)->division)->nama_divisi
                                      ?? '-';
                        @endphp
                        <div class="px-5 py-3 text-sm">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-900">
                                        {{ $nama }}
                                    </p>
                                    <p class="text-[0.7rem] text-slate-500">
                                        Divisi: {{ $divisi }} • Jenis: {{ ucfirst($req->jenis_cuti) }}
                                    </p>
                                    <p class="mt-1 text-[0.7rem] text-slate-600">
                                        Periode:
                                        <span class="font-medium">
                                            {{ \Carbon\Carbon::parse($req->tanggal_mulai)->format('d M Y') }}
                                            –
                                            {{ \Carbon\Carbon::parse($req->tanggal_selesai)->format('d M Y') }}
                                        </span>
                                    </p>
                                </div>
                                <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-[0.7rem] font-semibold tracking-[0.12em] uppercase">
                                    {{ $req->status }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Ringkasan divisi --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-sm font-semibold tracking-[0.14em] uppercase text-slate-800">
                    Ringkasan Divisi
                </h3>
                <a href="{{ route('hrd.divisions.index') }}"
                   class="text-[0.7rem] font-semibold tracking-[0.12em] uppercase text-sky-700 hover:text-sky-900">
                    Lihat Semua →
                </a>
            </div>

            @if($divisions->isEmpty())
                <p class="px-5 py-4 text-xs text-slate-500">
                    Belum ada data divisi.
                </p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($divisions->take(5) as $division)
                        @php
                            $leader      = $division->ketuaDivisi ?? null;
                            $leaderName  = optional(optional($leader)->profile)->nama_lengkap
                                           ?? optional($leader)->name
                                           ?? '-';
                        @endphp
                        <div class="px-5 py-3">
                            <p class="text-sm font-semibold text-slate-900">
                                {{ $division->nama_divisi }}
                            </p>
                            <p class="text-[0.7rem] text-slate-600">
                                Ketua Divisi:
                                <span class="font-medium text-sky-700">
                                    {{ $leaderName }}
                                </span>
                            </p>
                            <p class="mt-1 text-[0.7rem] text-slate-500">
                                Anggota:
                                <span class="font-medium">
                                    {{ $division->members_count }} orang
                                </span>
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
