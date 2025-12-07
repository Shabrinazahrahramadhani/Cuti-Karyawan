@extends('layouts.app')

@section('title', 'Detail Pengajuan Cuti')

@section('content')
<div class="max-w-5xl mx-auto px-4 lg:px-0 py-8 space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl
                             bg-gradient-to-tr from-blue-600 via-sky-500 to-emerald-500
                             text-[0.6rem] font-bold text-white tracking-[0.22em] uppercase shadow-md">
                    Leader
                </span>

                <div class="flex flex-col">
                    <span class="text-[0.7rem] tracking-[0.25em] text-slate-500 uppercase">
                        Detail Pengajuan Cuti
                    </span>
                    <h1 class="text-xl sm:text-2xl font-semibold text-slate-900 tracking-[0.18em] uppercase">
                        Detail Cuti Karyawan
                    </h1>
                </div>
            </div>
            <p class="text-xs sm:text-sm text-slate-600">
                Tinjau informasi lengkap pengajuan cuti sebelum kamu menyetujui atau menolak pengajuan ini.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('verifications.index') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-full
                      border border-slate-300 bg-white text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                      text-slate-700 hover:bg-slate-50 transition">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- INFO DIVISI (PAKE RELASI USER → PROFILE → DIVISION) --}}
    @php
        $division = optional(optional($leaveRequest->user)->profile)->division;
    @endphp

    @if($division)
        <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200 px-5 py-4 shadow-sm">
            <p class="text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-slate-500">
                Divisi
            </p>
            <p class="text-sm sm:text-base font-semibold text-slate-900 mt-1">
                {{ $division->nama_divisi }}
            </p>
        </div>
    @endif

    {{-- 2 KOLOM: INFORMASI & DOKUMEN --}}
    <div class="grid grid-cols-1 md:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)] gap-6 items-start">

        {{-- KIRI: Informasi Pengajuan --}}
        <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                    shadow-[0_18px_45px_rgba(15,23,42,0.15)] px-6 py-5 text-slate-800 space-y-3">
            <h2 class="text-sm font-semibold text-slate-900 tracking-[0.18em] uppercase mb-1">
                Informasi Pengajuan
            </h2>

            {{-- Nama --}}
            <div>
                <span class="text-xs text-slate-500">Nama Karyawan</span>
                <p class="text-sm sm:text-base font-semibold text-slate-900 mt-1">
                    {{ $leaveRequest->user->name ?? '-' }}
                </p>
            </div>

            {{-- Jenis Cuti --}}
            <div>
                <span class="text-xs text-slate-500">Jenis Cuti</span>
                <p class="text-sm sm:text-base text-slate-800 mt-1">
                    {{ $leaveRequest->jenis_cuti }}
                </p>
            </div>

            {{-- Periode --}}
            <div>
                <span class="text-xs text-slate-500">Periode Cuti</span>
                <p class="text-sm sm:text-base text-slate-800 mt-1">
                    {{ optional($leaveRequest->tanggal_mulai)->format('d M Y') }}
                    &mdash;
                    {{ optional($leaveRequest->tanggal_selesai)->format('d M Y') }}
                    <span class="text-slate-500 text-xs">
                        ({{ $leaveRequest->total_hari }} hari kerja)
                    </span>
                </p>
            </div>

            {{-- Tanggal Pengajuan --}}
            <div>
                <span class="text-xs text-slate-500">Tanggal Pengajuan</span>
                <p class="text-sm sm:text-base text-slate-800 mt-1">
                    {{ optional($leaveRequest->tanggal_pengajuan)->format('d M Y H:i') }}
                </p>
            </div>

            {{-- Status --}}
            <div>
                <span class="text-xs text-slate-500">Status Saat Ini</span>
                <p class="mt-1">
                    @php
                        $status = $leaveRequest->status;
                        $badgeClass = 'bg-slate-100 text-slate-700';
                        if ($status === 'Pending') {
                            $badgeClass = 'bg-yellow-100 text-yellow-700';
                        } elseif ($status === 'Approved by Leader') {
                            $badgeClass = 'bg-blue-100 text-blue-700';
                        } elseif ($status === 'Approved') {
                            $badgeClass = 'bg-emerald-100 text-emerald-700';
                        } elseif (str_contains($status, 'Rejected') || $status === 'Cancelled') {
                            $badgeClass = 'bg-rose-100 text-rose-700';
                        }
                    @endphp
                    <span class="inline-flex px-3 py-1 rounded-full text-[0.7rem] font-semibold {{ $badgeClass }}">
                        {{ $status }}
                    </span>
                </p>
            </div>

            {{-- Alasan --}}
            @if($leaveRequest->alasan)
                <div>
                    <span class="text-xs text-slate-500">Alasan Cuti</span>
                    <p class="text-sm text-slate-800 mt-1">
                        {{ $leaveRequest->alasan }}
                    </p>
                </div>
            @endif

            {{-- Alamat --}}
            @if($leaveRequest->alamat_selama_cuti)
                <div>
                    <span class="text-xs text-slate-500">Alamat selama cuti</span>
                    <p class="text-sm text-slate-800 mt-1">
                        {{ $leaveRequest->alamat_selama_cuti }}
                    </p>
                </div>
            @endif

            {{-- Kontak Darurat --}}
            @if($leaveRequest->nomor_darurat)
                <div>
                    <span class="text-xs text-slate-500">Kontak darurat</span>
                    <p class="text-sm text-slate-800 mt-1">
                        {{ $leaveRequest->nomor_darurat }}
                    </p>
                </div>
            @endif
        </div>

        {{-- KANAN: Dokumen & Aksi --}}
        <div class="space-y-4">

            <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                        shadow-[0_16px_40px_rgba(15,23,42,0.12)] px-6 py-5 text-slate-800 space-y-3">
                <h2 class="text-sm font-semibold text-slate-900 tracking-[0.18em] uppercase mb-1">
                    Dokumen & Tindakan
                </h2>

                {{-- Surat Dokter --}}
                @if($leaveRequest->surat_dokter)
                    <div>
                        <span class="text-xs text-slate-500">Surat Keterangan Dokter</span>
                        <p class="mt-1">
                            <a href="{{ asset('storage/' . $leaveRequest->surat_dokter) }}"
                               target="_blank"
                               class="text-xs sm:text-sm text-sky-700 hover:text-sky-600 underline">
                                Lihat Surat Dokter
                            </a>
                        </p>
                    </div>
                @endif

                {{-- Aksi Approve / Reject --}}
                <div class="border-t border-slate-200 pt-4 space-y-3">
                    <form action="{{ route('verifications.approve', $leaveRequest) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full px-4 py-2 rounded-full
                                       bg-emerald-500 text-slate-950 text-[0.75rem] font-semibold
                                       tracking-[0.18em] uppercase
                                       shadow-[0_12px_30px_rgba(16,185,129,0.7)]
                                       hover:bg-emerald-400 hover:-translate-y-[1px] transition">
                            Approve Pengajuan
                        </button>
                    </form>

                    <details class="group">
                        <summary
                            class="px-4 py-2 rounded-full border border-rose-400
                                   bg-rose-50 text-rose-700 text-[0.75rem] font-semibold
                                   tracking-[0.18em] uppercase cursor-pointer
                                   hover:bg-rose-100 transition list-none flex items-center justify-between">
                            <span>Reject dengan Alasan</span>
                            <span class="text-[0.8rem] group-open:rotate-180 transition-transform">⌄</span>
                        </summary>

                        <div class="mt-2 p-3 border border-rose-200 rounded-2xl bg-rose-50">
                            <form action="{{ route('verifications.reject', $leaveRequest) }}" method="POST" class="space-y-2">
                                @csrf
                                <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-rose-800">
                                    Alasan Penolakan
                                </label>
                                <textarea name="catatan"
                                          rows="3"
                                          class="w-full text-xs rounded-2xl border border-rose-300 bg-white
                                                 px-3 py-2 text-slate-800 resize-y
                                                 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-rose-400"
                                          required
                                          minlength="10"
                                          placeholder="Tuliskan alasan penolakan yang jelas (minimal 10 karakter)...">{{ old('catatan') }}</textarea>

                                <button type="submit"
                                        class="w-full mt-1 px-4 py-2 rounded-full
                                               bg-rose-600 text-white text-[0.75rem] font-semibold
                                               tracking-[0.18em] uppercase
                                               shadow-[0_10px_26px_rgba(225,29,72,0.7)]
                                               hover:bg-rose-500 hover:-translate-y-[1px] transition">
                                    Kirim Penolakan
                                </button>
                            </form>
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </div>

    {{-- TIMELINE APPROVAL --}}
    <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                shadow-[0_18px_45px_rgba(15,23,42,0.15)] px-6 py-5 text-slate-800 space-y-4">
        <h2 class="text-sm font-semibold text-slate-900 tracking-[0.18em] uppercase">
            Timeline Approval
        </h2>

        <ol class="border-l border-slate-200 pl-4 space-y-4 text-xs sm:text-sm">

            {{-- Step 1: Pengajuan --}}
            <li>
                <div class="flex gap-2 items-start">
                    <div class="w-3 h-3 rounded-full bg-blue-500 mt-1.5"></div>
                    <div>
                        <p class="font-semibold text-slate-900">
                            Pengajuan dibuat oleh {{ $leaveRequest->user->name ?? 'Karyawan' }}
                        </p>
                        <p class="text-[0.7rem] text-slate-500">
                            {{ optional($leaveRequest->tanggal_pengajuan)->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </li>

            {{-- Step 2: Approval Leader --}}
            <li>
                <div class="flex gap-2 items-start">
                    <div class="w-3 h-3 rounded-full bg-emerald-500 mt-1.5"></div>
                    <div>
                        <p class="font-semibold text-slate-900">
                            Proses oleh Leader
                        </p>
                        @if($leaveRequest->approved_leader_at)
                            <p class="text-[0.7rem] text-slate-500">
                                Diproses oleh {{ optional($leaveRequest->leader)->name ?? 'Leader' }}
                                pada {{ optional($leaveRequest->approved_leader_at)->format('d M Y H:i') }}.
                            </p>
                            @if($leaveRequest->catatan_leader)
                                <p class="text-[0.7rem] text-slate-600 mt-1">
                                    Catatan Leader: {{ $leaveRequest->catatan_leader }}
                                </p>
                            @endif
                        @else
                            <p class="text-[0.7rem] text-slate-500">
                                Belum diproses oleh Leader.
                            </p>
                        @endif
                    </div>
                </div>
            </li>

            {{-- Step 3: Approval HRD --}}
            <li>
                <div class="flex gap-2 items-start">
                    <div class="w-3 h-3 rounded-full bg-slate-700 mt-1.5"></div>
                    <div>
                        <p class="font-semibold text-slate-900">
                            Proses oleh HRD
                        </p>
                        @if($leaveRequest->approved_hrd_at)
                            <p class="text-[0.7rem] text-slate-500">
                                Diproses oleh {{ optional($leaveRequest->hrd)->name ?? 'HRD' }}
                                pada {{ optional($leaveRequest->approved_hrd_at)->format('d M Y H:i') }}.
                            </p>
                            @if($leaveRequest->catatan_hrd)
                                <p class="text-[0.7rem] text-slate-600 mt-1">
                                    Catatan HRD: {{ $leaveRequest->catatan_hrd }}
                                </p>
                            @endif
                        @else
                            <p class="text-[0.7rem] text-slate-500">
                                Belum diproses oleh HRD.
                            </p>
                        @endif
                    </div>
                </div>
            </li>
        </ol>
    </div>

</div>
@endsection
