@extends('layouts.app')

@section('title', 'Detail Cuti')

@section('content')
@php
    $user = auth()->user();
    $role = $user->role ?? null;

    // Tentukan URL kembali berdasarkan role
    try {
        $backUrl = match ($role) {
            'Admin'  => route('admin.cuti.index'),
            'HRD'    => route('approvals.index'),
            'Leader' => route('verifications.index'),
            default  => route('user.leave.history'),
        };
    } catch (\Throwable $e) {
        // Fallback kalau route tertentu belum ada
        $backUrl = route('user.leave.history');
    }

    $status      = $leave->status ?? 'Pending';
    $statusLower = strtolower($status);

    // role pemohon (bisa User atau Leader)
    $pemohonRole   = optional($leave->user)->role ?? null;
    $pemohonLeader = strtolower($pemohonRole) === 'leader';

    // leaderDone = hanya jika memang ada proses dari leader (bukan kalau pemohonnya leader)
    $leaderDone  = !$pemohonLeader && in_array($statusLower, ['approved by leader', 'approved', 'rejected', 'cancelled']);
    $hrdApproved = $statusLower === 'approved';
    $hrdRejected = $statusLower === 'rejected';
    $cancelled   = $statusLower === 'cancelled';

    $pengajuNama = optional(optional($leave->user)->profile)->nama_lengkap
                    ?? optional($leave->user)->name
                    ?? 'Tanpa Nama';

    $leaderNama  = optional($leave->leader)->name ?? '-';
    $hrdNama     = optional($leave->hrd)->name ?? '-';

    $filePath = $leave->surat_dokter_path ?? $leave->surat_dokter ?? null;

    $badgeClass = match ($statusLower) {
        'pending'             => 'bg-amber-100 text-amber-800 ring-amber-200',
        'approved by leader'  => 'bg-sky-100 text-sky-800 ring-sky-200',
        'approved'            => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        'rejected'            => 'bg-rose-100 text-rose-800 ring-rose-200',
        'cancelled'           => 'bg-slate-100 text-slate-700 ring-slate-200',
        default               => 'bg-slate-100 text-slate-700 ring-slate-200',
    };
@endphp

<div class="max-w-4xl mx-auto mt-10 px-4 space-y-6">

    {{-- HEADER HALAMAN --}}
    <div class="flex justify-between items-start gap-4">
        <div>
            <h2 class="flex items-center gap-3 text-2xl md:text-3xl font-semibold text-slate-900">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl
                             bg-gradient-to-tr from-sky-500 via-blue-500 to-indigo-500
                             text-[0.6rem] font-bold text-white tracking-[0.18em] uppercase">
                    CUTI
                </span>
                <span class="tracking-[0.12em] uppercase text-sm sm:text-base">
                    Detail Pengajuan Cuti
                </span>
            </h2>
            <p class="mt-2 text-xs text-slate-600">
                Rincian lengkap pengajuan cuti, termasuk alamat selama cuti dan timeline persetujuan.
            </p>
        </div>

        <a href="{{ $backUrl }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-full
                  bg-white text-slate-900 text-[0.7rem] font-semibold tracking-[0.14em] uppercase
                  border border-slate-300 shadow-sm
                  hover:bg-slate-50 hover:-translate-y-0.5 transition">
            ← Kembali
        </a>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="rounded-3xl bg-white border border-slate-200 shadow-[0_18px_40px_rgba(15,23,42,0.08)] overflow-hidden">

        {{-- Top accent bar --}}
        <div class="h-1.5 bg-gradient-to-r from-sky-500 via-blue-500 to-indigo-500"></div>

        <div class="px-5 sm:px-7 py-6 space-y-6">

            {{-- INFO RINGKAS --}}
            <div class="grid sm:grid-cols-2 gap-6 text-sm">

                {{-- Kolom kiri --}}
                <div class="space-y-3">
                    <div>
                        <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-500">
                            Jenis Cuti
                        </p>
                        <p class="text-base font-semibold text-slate-900">
                            {{ $leave->jenis_cuti }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-500">
                            Periode Cuti
                        </p>
                        <p class="text-sm text-slate-900">
                            {{ \Carbon\Carbon::parse($leave->tanggal_mulai)->format('d M Y') }}
                            –
                            {{ \Carbon\Carbon::parse($leave->tanggal_selesai)->format('d M Y') }}
                            ({{ $leave->total_hari }} hari kerja)
                        </p>
                    </div>

                    <div>
                        <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-500">
                            Status
                        </p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[0.7rem] font-semibold
                                     tracking-[0.12em] uppercase ring-1 {{ $badgeClass }}">
                            {{ $status }}
                        </span>
                    </div>

                    <div>
                        <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-500">
                            Diajukan oleh
                        </p>
                        <p class="text-sm text-slate-900">
                            {{ $pengajuNama }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-500">
                            Tanggal Pengajuan
                        </p>
                        <p class="text-sm text-slate-900">
                            {{ \Carbon\Carbon::parse($leave->tanggal_pengajuan)->format('d M Y') }}
                        </p>
                    </div>
                </div>

                {{-- Kolom kanan --}}
                <div class="space-y-3">
                    <div>
                        <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-500">
                            Alasan Cuti
                        </p>
                        <p class="text-sm text-slate-800 bg-slate-50 border border-slate-200 rounded-2xl px-3 py-2">
                            {{ $leave->alasan }}
                        </p>
                    </div>

                    @if($leave->jenis_cuti === 'Sakit')
                        <div>
                            <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-500">
                                Surat Dokter
                            </p>
                            @if($filePath)
                                <a href="{{ asset('storage/'.$filePath) }}"
                                   target="_blank"
                                   class="inline-flex items-center text-xs text-sky-600 underline">
                                    Lihat Surat Dokter
                                </a>
                            @else
                                <p class="text-xs text-slate-600">
                                    Tidak ada lampiran surat dokter.
                                </p>
                            @endif
                        </div>
                    @endif

                    <div>
                        <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-500">
                            Alamat Selama Cuti
                        </p>
                        <p class="text-xs text-slate-800">
                            {{ $leave->alamat_selama_cuti ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-500">
                            Kontak Darurat
                        </p>
                        <p class="text-xs text-slate-800">
                            {{ $leave->nomor_darurat ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- GARIS PEMISAH --}}
            <div class="border-t border-slate-200 pt-4">
                <h3 class="text-sm font-semibold tracking-[0.14em] uppercase text-slate-600 mb-3">
                    Timeline Persetujuan
                </h3>

                <div class="space-y-5">

                    {{-- STEP 1: Pengajuan --}}
                    <div class="flex gap-3">
                        <div class="mt-1 flex flex-col items-center">
                            <div class="w-3 h-3 rounded-full bg-sky-500"></div>
                            <div class="w-px flex-1 bg-slate-200 mt-1"></div>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900 text-sm">Pengajuan Cuti Dibuat</p>
                            <p class="text-xs text-slate-600">
                                Diajukan oleh <span class="font-semibold">{{ $pengajuNama }}</span>
                                pada {{ \Carbon\Carbon::parse($leave->tanggal_pengajuan)->format('d M Y') }}.
                            </p>
                            <p class="text-[0.7rem] text-slate-500 mt-1">
                                Dibuat: {{ \Carbon\Carbon::parse($leave->created_at)->format('d M Y · H:i') }}
                            </p>
                        </div>
                    </div>

                    {{-- STEP 2: Verifikasi Ketua Divisi --}}
                    <div class="flex gap-3">
                        <div class="mt-1 flex flex-col items-center">
                            <div class="w-3 h-3 rounded-full
                                @if($pemohonLeader)
                                    bg-slate-300
                                @else
                                    {{ $leaderDone ? 'bg-emerald-500' : 'bg-slate-300' }}
                                @endif"></div>
                            <div class="w-px flex-1 bg-slate-200 mt-1"></div>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900 text-sm">
                                Verifikasi Ketua Divisi
                            </p>

                            @if($pemohonLeader)
                                <p class="text-xs text-slate-600 mt-1">
                                    Pengajuan ini dibuat oleh <span class="font-semibold">Ketua Divisi</span> dan
                                    langsung diproses oleh <span class="font-semibold">HRD</span> tanpa tahapan verifikasi atasan.
                                </p>
                            @else
                                <p class="text-xs text-slate-600">
                                    Atasan langsung: <span class="font-semibold">{{ $leaderNama }}</span>
                                </p>

                                @if($statusLower === 'pending')
                                    <p class="text-xs text-slate-600 mt-1">
                                        Menunggu verifikasi Ketua Divisi.
                                    </p>
                                @elseif($statusLower === 'approved by leader')
                                    <p class="text-xs text-emerald-700 mt-1">
                                        Pengajuan telah <span class="font-semibold">disetujui oleh Ketua Divisi</span>
                                        dan sedang menunggu persetujuan HRD.
                                    </p>
                                @elseif($statusLower === 'approved')
                                    <p class="text-xs text-emerald-700 mt-1">
                                        Pengajuan telah melewati tahapan verifikasi
                                        <span class="font-semibold">Ketua Divisi</span>.
                                    </p>
                                @elseif($statusLower === 'rejected')
                                    <p class="text-xs text-rose-700 mt-1">
                                        Pengajuan telah <span class="font-semibold">ditolak</span>
                                        (oleh Leader atau HRD).
                                    </p>
                                @elseif($statusLower === 'cancelled')
                                    <p class="text-xs text-slate-700 mt-1">
                                        Pengajuan dibatalkan sebelum proses verifikasi selesai.
                                    </p>
                                @endif

                                @if(!empty($leave->approved_leader_at))
                                    <p class="text-[0.7rem] text-slate-500 mt-1">
                                        Diproses pada: {{ \Carbon\Carbon::parse($leave->approved_leader_at)->format('d M Y · H:i') }}
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- STEP 3: Persetujuan Final HRD --}}
                    <div class="flex gap-3">
                        <div class="mt-1 flex flex-col items-center">
                            <div class="w-3 h-3 rounded-full
                                @if($hrdApproved) bg-emerald-500
                                @elseif($hrdRejected) bg-rose-500
                                @elseif($cancelled) bg-slate-300
                                @else bg-slate-300 @endif"></div>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900 text-sm">
                                Persetujuan Final HRD
                            </p>

                            <p class="text-xs text-slate-600">
                                HRD: <span class="font-semibold">{{ $hrdNama }}</span>
                            </p>

                            @if($hrdApproved)
                                <p class="text-xs text-emerald-700 mt-1">
                                    Pengajuan cuti telah <span class="font-semibold">disetujui oleh HRD</span>.
                                </p>
                            @elseif($hrdRejected)
                                <p class="text-xs text-rose-700 mt-1">
                                    Pengajuan cuti telah <span class="font-semibold">ditolak oleh HRD</span>.
                                </p>
                                @if($leave->catatan_penolakan)
                                    <p class="mt-1 text-xs text-slate-700">
                                        <span class="font-semibold">Catatan Penolakan HRD:</span><br>
                                        {{ $leave->catatan_penolakan }}
                                    </p>
                                @endif
                            @elseif($cancelled)
                                <p class="text-xs text-slate-600 mt-1">
                                    Pengajuan dibatalkan, sehingga tidak diproses oleh HRD.
                                </p>
                            @else
                                <p class="text-xs text-slate-600 mt-1">
                                    Menunggu persetujuan HRD
                                    @if(!$pemohonLeader)
                                        setelah disetujui oleh Ketua Divisi.
                                    @else
                                        sebagai persetujuan final.
                                    @endif
                                </p>
                            @endif

                            @if(!empty($leave->approved_hrd_at))
                                <p class="text-[0.7rem] text-slate-500 mt-1">
                                    Diproses pada: {{ \Carbon\Carbon::parse($leave->approved_hrd_at)->format('d M Y · H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            {{-- ALASAN PEMBATALAN (JIKA CANCELLED) --}}
            @if($cancelled && !empty($leave->alasan_pembatalan))
                <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3">
                    <p class="text-sm font-semibold text-rose-700 mb-1">
                        Pengajuan Dibatalkan
                    </p>
                    <p class="text-xs text-slate-800">
                        <span class="font-semibold">Alasan Pembatalan:</span>
                        {{ $leave->alasan_pembatalan }}
                    </p>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
