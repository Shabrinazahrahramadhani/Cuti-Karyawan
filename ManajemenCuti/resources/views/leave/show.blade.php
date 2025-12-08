@extends('layouts.app')

@section('title', 'Detail Cuti')

@section('content')
@php
    $user = auth()->user();
    $role = $user->role ?? null;

    try {
        $backUrl = match ($role) {
            'Admin'  => route('admin.cuti.index'),
            'HRD'    => route('approvals.index'),
            'Leader' => route('verifications.index'),
            default  => route('leave.history'),
        };
    } catch (\Throwable $e) {
        // Fallback kalau route tertentu belum ada
        $backUrl = route('leave.history');
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
@endphp

<div class="max-w-3xl mx-auto mt-10 px-4">
    <div class="relative overflow-hidden bg-[#fefce8] rounded-3xl border-2 border-[#0f172a]
                shadow-[8px_8px_0_#0f172a] backdrop-blur p-6 sm:p-8 space-y-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-start gap-4">
            <div>
                <h2 class="flex items-center gap-3 text-xl sm:text-2xl font-semibold text-slate-900">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl
                                 bg-gradient-to-tr from-orange-400 via-pink-500 to-sky-500
                                 text-[0.6rem] font-bold text-white tracking-[0.18em] uppercase">
                        CUTI
                    </span>
                    <span class="tracking-[0.12em] uppercase text-sm sm:text-base">
                        Detail Pengajuan Cuti
                    </span>
                </h2>
                <p class="mt-2 text-xs text-slate-600">
                    Rincian lengkap pengajuan cuti beserta timeline persetujuan.
                </p>
            </div>

            <a href="{{ $backUrl }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-2xl
                      bg-white text-slate-900 text-[0.7rem] font-semibold tracking-[0.14em] uppercase
                      border-2 border-[#0f172a] shadow-[4px_4px_0_#0f172a]
                      hover:-translate-y-0.5 hover:-translate-x-0.5 hover:shadow-[6px_6px_0_#0f172a]
                      transition">
                ← Kembali
            </a>
        </div>

        {{-- INFO RINGKAS --}}
        <div class="grid sm:grid-cols-2 gap-4 text-sm">
            <div class="space-y-2">
                <div>
                    <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-700">
                        Jenis Cuti
                    </p>
                    <p class="text-base font-semibold text-slate-900">
                        {{ $leave->jenis_cuti }}
                    </p>
                </div>

                <div class="mt-3">
                    <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-700">
                        Periode Cuti
                    </p>
                    <p class="text-sm text-slate-900">
                        {{ \Carbon\Carbon::parse($leave->tanggal_mulai)->format('d M Y') }}
                        -
                        {{ \Carbon\Carbon::parse($leave->tanggal_selesai)->format('d M Y') }}
                        ({{ $leave->total_hari }} hari kerja)
                    </p>
                </div>

                <div class="mt-3">
                    <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-700">
                        Status
                    </p>
                    @php
                        $badgeClass = match ($statusLower) {
                            'pending'             => 'bg-yellow-300 text-slate-900',
                            'approved by leader'  => 'bg-sky-300 text-slate-900',
                            'approved'            => 'bg-emerald-300 text-slate-900',
                            'rejected'            => 'bg-red-300 text-slate-900',
                            'cancelled'           => 'bg-gray-300 text-slate-900',
                            default               => 'bg-white text-slate-900',
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                 border-2 border-[#0f172a] shadow-[3px_3px_0_#0f172a] {{ $badgeClass }}">
                        {{ $status }}
                    </span>
                </div>

                <div class="mt-3">
                    <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-700">
                        Diajukan oleh
                    </p>
                    <p class="text-sm text-slate-900">
                        {{ $pengajuNama }}
                    </p>
                </div>
            </div>

            <div class="space-y-3">
                <div>
                    <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-700">
                        Alasan Cuti
                    </p>
                    <p class="text-sm text-slate-800 bg-white/70 border border-slate-200 rounded-2xl px-3 py-2">
                        {{ $leave->alasan }}
                    </p>
                </div>

                @if($leave->jenis_cuti === 'Sakit')
                    <div>
                        <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-700">
                            Surat Dokter
                        </p>
                        @if($filePath)
                            <a href="{{ asset('storage/'.$filePath) }}"
                               target="_blank"
                               class="inline-flex items-center text-xs text-blue-600 underline">
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
                    <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-700">
                        Alamat Selama Cuti
                    </p>
                    <p class="text-xs text-slate-800">
                        {{ $leave->alamat_selama_cuti ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-[0.75rem] font-semibold tracking-[0.12em] uppercase text-slate-700">
                        Kontak Darurat
                    </p>
                    <p class="text-xs text-slate-800">
                        {{ $leave->nomor_darurat ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- TIMELINE --}}
        <div class="mt-4">
            <h3 class="text-sm font-semibold tracking-[0.14em] uppercase text-slate-700 mb-3">
                Timeline Persetujuan
            </h3>

            <div class="space-y-5">

                {{-- STEP 1: Pengajuan --}}
                <div class="flex gap-3">
                    <div class="mt-1 flex flex-col items-center">
                        <div class="w-3 h-3 rounded-full bg-blue-500 border border-[#0f172a]"></div>
                        <div class="w-px flex-1 bg-slate-300 mt-1"></div>
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
                                bg-gray-400
                            @else
                                {{ $leaderDone ? 'bg-emerald-500' : 'bg-gray-400' }}
                            @endif
                            border border-[#0f172a]"></div>
                        <div class="w-px flex-1 bg-slate-300 mt-1"></div>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900 text-sm">
                            Verifikasi Ketua Divisi
                        </p>

                        @if($pemohonLeader)
                            {{-- kalau pengaju = Leader, tidak ada verifikasi dari Leader --}}
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
                                <p class="text-xs text-red-700 mt-1">
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
                            @elseif($hrdRejected) bg-red-500
                            @elseif($cancelled) bg-gray-400
                            @else bg-gray-400 @endif border border-[#0f172a]"></div>
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
                            <p class="text-xs text-red-700 mt-1">
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
            <div class="mt-4 bg-[#fee2e2] border-2 border-[#0f172a] rounded-3xl shadow-[6px_6px_0_#0f172a] p-4">
                <p class="text-sm font-semibold text-red-800 tracking-[0.12em] uppercase mb-1">
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
@endsection
