@extends('layouts.app')

@section('title', 'Detail Cuti')

@section('content')

{{-- ANIMASI HALUS UNTUK CARD --}}
<style>
    @keyframes fadeInUpSoft {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInScaleSoft {
        from { opacity: 0; transform: scale(0.97); }
        to   { opacity: 1; transform: scale(1); }
    }
    .anim-fade-up { animation: fadeInUpSoft .4s ease-out forwards; }
    .anim-card    { animation: fadeInScaleSoft .45s ease-out forwards; }
</style>

<div class="max-w-5xl mx-auto mt-10 px-4 anim-fade-up">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="flex items-center gap-3 text-xl sm:text-2xl md:text-3xl font-semibold text-slate-900">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full
                             bg-sky-100 text-[0.6rem] font-bold text-slate-900 tracking-[0.18em] uppercase">
                    ADMIN
                </span>
                <span class="tracking-[0.14em] uppercase text-sm sm:text-base text-slate-800">
                    Detail Pengajuan Cuti
                </span>
            </h2>
            <p class="mt-2 text-xs text-slate-500">
                Rincian lengkap pengajuan cuti, termasuk timeline approval dan catatan HRD/Leader.
            </p>
        </div>

        <a href="{{ route('admin.cuti.index') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-full
                  bg-white text-slate-800 text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                  border border-slate-300 shadow-sm
                  hover:bg-slate-50 hover:border-sky-400 hover:text-sky-700 transition">
            ← Kembali
        </a>
    </div>

    @php
        $status      = $leave->status;
        $isApproved  = $status === 'Approved';
        $isRejected  = $status === 'Rejected';
        $isPending   = $status === 'Pending';
        $byLeader    = $status === 'Approved by Leader';

        $user        = $leave->user;
        $profile     = optional($user)->profile;
        $division    = optional($profile)->division;
    @endphp

    {{-- INFO UTAMA --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-[0_10px_30px_rgba(15,23,42,0.08)] p-6 mb-6 anim-card">
        <div class="flex flex-col sm:flex-row justify-between gap-4">
            <div>
                <p class="text-[0.7rem] text-slate-500 mb-1 uppercase tracking-[0.16em]">Karyawan</p>
                <p class="text-base font-semibold text-slate-900">
                    {{ $profile->nama_lengkap ?? $user->name ?? 'Tanpa Nama' }}
                </p>
                <p class="text-xs text-slate-600 mt-1">
                    Email: {{ $user->email ?? '-' }}
                </p>
                <p class="text-xs text-slate-600">
                    Divisi:
                    <span class="font-semibold text-slate-800">{{ $division->nama_divisi ?? '-' }}</span>
                </p>
            </div>

            <div class="text-left sm:text-right">
                <p class="text-[0.7rem] text-slate-500 mb-1 uppercase tracking-[0.16em]">Status</p>
                @php
                    $badgeClass = match(strtolower($status)) {
                        'pending'               => 'bg-amber-50 text-amber-700 border-amber-200',
                        'approved by leader'    => 'bg-sky-50 text-sky-700 border-sky-200',
                        'approved'              => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'rejected'              => 'bg-rose-50 text-rose-700 border-rose-200',
                        'cancelled'             => 'bg-slate-100 text-slate-700 border-slate-200',
                        default                 => 'bg-slate-100 text-slate-700 border-slate-200',
                    };
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[0.7rem] font-semibold
                             tracking-[0.12em] uppercase border {{ $badgeClass }}">
                    {{ $status }}
                </span>
                <p class="mt-2 text-[0.75rem] text-slate-600">
                    Jenis Cuti:
                    <span class="font-semibold text-slate-800">{{ $leave->jenis_cuti }}</span>
                </p>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-slate-700">
            <div>
                <p class="font-semibold text-[0.75rem] uppercase tracking-[0.12em] text-slate-500">
                    Tanggal Pengajuan
                </p>
                <p class="mt-1 text-slate-800">
                    {{ \Carbon\Carbon::parse($leave->tanggal_pengajuan)->format('d M Y') }}
                </p>
            </div>
            <div>
                <p class="font-semibold text-[0.75rem] uppercase tracking-[0.12em] text-slate-500">
                    Periode Cuti
                </p>
                <p class="mt-1 text-slate-800">
                    {{ \Carbon\Carbon::parse($leave->tanggal_mulai)->format('d M Y') }}
                    –
                    {{ \Carbon\Carbon::parse($leave->tanggal_selesai)->format('d M Y') }}
                    ({{ $leave->total_hari }} hari kerja)
                </p>
            </div>
            <div>
                <p class="font-semibold text-[0.75rem] uppercase tracking-[0.12em] text-slate-500">
                    Kontak Selama Cuti
                </p>
                <p class="mt-1 text-slate-800">
                    <span class="block">
                        Alamat: {{ $leave->alamat_selama_cuti ?? '-' }}
                    </span>
                    <span class="block">
                        Nomor: {{ $leave->nomor_darurat ?? '-' }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    {{-- TIMELINE APPROVAL --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-[0_10px_30px_rgba(15,23,42,0.08)] p-6 mb-6 anim-card">
        <h3 class="text-sm font-semibold tracking-[0.14em] uppercase text-slate-800 mb-4">
            Timeline Approval
        </h3>

        <div class="space-y-5">

            {{-- Step 1: Pengajuan --}}
            <div class="flex gap-3">
                <div class="flex flex-col items-center">
                    <div class="w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white shadow-sm"></div>
                    <div class="flex-1 w-px bg-slate-200 mt-1"></div>
                </div>
                <div class="flex-1">
                    <p class="text-[0.8rem] font-semibold text-slate-900">
                        Pengajuan Cuti Dibuat
                    </p>
                    <p class="text-[0.75rem] text-slate-600">
                        Oleh: {{ $profile->nama_lengkap ?? $user->name ?? 'Tanpa Nama' }}
                    </p>
                    <p class="text-[0.7rem] text-slate-500 mt-1">
                        {{ \Carbon\Carbon::parse($leave->created_at)->format('d M Y · H:i') }}
                    </p>
                    <p class="text-[0.75rem] text-slate-700 mt-2">
                        <span class="font-semibold">Alasan:</span> {{ $leave->alasan }}
                    </p>
                </div>
            </div>

            {{-- Step 2: Leader --}}
            <div class="flex gap-3">
                <div class="flex flex-col items-center">
                    @php
                        $step2Done = !empty($leave->approved_leader_at) || in_array($status, ['Approved by Leader', 'Approved', 'Rejected']);
                    @endphp
                    <div class="w-3.5 h-3.5 rounded-full border-2 border-white shadow-sm
                                {{ $step2Done ? 'bg-sky-500' : 'bg-slate-300' }}"></div>
                    <div class="flex-1 w-px bg-slate-200 mt-1"></div>
                </div>
                <div class="flex-1">
                    <p class="text-[0.8rem] font-semibold text-slate-900">
                        Verifikasi Ketua Divisi
                    </p>
                    <p class="text-[0.75rem] text-slate-600">
                        Atasan: {{ optional($leave->leader)->name ?? '-' }}
                    </p>

                    @if($step2Done)
                        <p class="text-[0.75rem] text-slate-700 mt-1">
                            Status:
                            <span class="font-semibold text-slate-900">
                                {{ $status === 'Approved by Leader' || $status === 'Approved' ? 'Disetujui Leader' : 'Diproses' }}
                            </span>
                        </p>
                        <p class="text-[0.7rem] text-slate-500 mt-1">
                            {{ \Carbon\Carbon::parse($leave->approved_leader_at)->format('d M Y · H:i') }}
                        </p>
                    @else
                        <p class="text-[0.75rem] text-slate-500 mt-1 italic">
                            Menunggu verifikasi Ketua Divisi.
                        </p>
                    @endif
                </div>
            </div>

            {{-- Step 3: HRD --}}
            <div class="flex gap-3">
                <div class="flex flex-col items-center">
                    @php
                        $step3Done = !empty($leave->approved_hrd_at) || in_array($status, ['Approved', 'Rejected']);
                    @endphp
                    <div class="w-3.5 h-3.5 rounded-full border-2 border-white shadow-sm
                        @if($step3Done)
                            {{ $isApproved ? 'bg-emerald-500' : ($isRejected ? 'bg-rose-500' : 'bg-sky-500') }}
                        @else
                            bg-slate-300
                        @endif
                    "></div>
                </div>
                <div class="flex-1">
                    <p class="text-[0.8rem] font-semibold text-slate-900">
                        Persetujuan Final HRD
                    </p>
                    <p class="text-[0.75rem] text-slate-600">
                        HRD: {{ optional($leave->hrd)->name ?? '-' }}
                    </p>

                    @if($step3Done)
                        <p class="text-[0.75rem] text-slate-700 mt-1">
                            Status:
                            <span class="font-semibold text-slate-900">
                                {{ $isApproved ? 'Disetujui' : ($isRejected ? 'Ditolak' : $status) }}
                            </span>
                        </p>
                        <p class="text-[0.7rem] text-slate-500 mt-1">
                            {{ \Carbon\Carbon::parse($leave->approved_hrd_at)->format('d M Y · H:i') }}
                        </p>

                        @if(!empty($leave->catatan_penolakan))
                            <p class="text-[0.75rem] text-rose-700 mt-2">
                                <span class="font-semibold">Catatan Penolakan HRD:</span>
                                {{ $leave->catatan_penolakan }}
                            </p>
                        @endif
                    @else
                        <p class="text-[0.75rem] text-slate-500 mt-1 italic">
                            Menunggu persetujuan final HRD.
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- LAMPIRAN SURAT DOKTER --}}
    @if($leave->jenis_cuti === 'Sakit')
        <div class="bg-sky-50 border border-sky-100 rounded-2xl shadow-[0_8px_22px_rgba(15,23,42,0.06)] p-5 mb-6 anim-card">
            <h3 class="text-sm font-semibold tracking-[0.14em] uppercase text-slate-800 mb-2">
                Lampiran Surat Dokter
            </h3>

            @php
                $filePath = $leave->surat_dokter_path ?? $leave->surat_dokter ?? null;
            @endphp

            @if($filePath)
                <a href="{{ asset('storage/'.$filePath) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 text-xs font-semibold text-sky-700 hover:text-sky-800 underline">
                    Lihat Surat Dokter
                </a>
                <p class="mt-1 text-[0.75rem] text-slate-600">
                    File akan dibuka di tab baru (PDF/JPG/PNG).
                </p>
            @else
                <p class="text-[0.8rem] text-slate-600">
                    Tidak ada lampiran surat dokter yang tersimpan.
                </p>
            @endif
        </div>
    @endif

    {{-- ALASAN PEMBATALAN (JIKA CANCELLED) --}}
    @if(strtolower($leave->status) === 'cancelled' && !empty($leave->alasan_pembatalan))
        <div class="bg-rose-50 border border-rose-100 rounded-2xl shadow-[0_8px_22px_rgba(15,23,42,0.06)] p-5 anim-card">
            <h3 class="text-sm font-semibold tracking-[0.14em] uppercase text-rose-800 mb-2">
                Pengajuan Dibatalkan
            </h3>
            <p class="text-[0.8rem] text-slate-700">
                <span class="font-semibold">Alasan Pembatalan:</span>
                {{ $leave->alasan_pembatalan }}
            </p>
        </div>
    @endif

</div>
@endsection
