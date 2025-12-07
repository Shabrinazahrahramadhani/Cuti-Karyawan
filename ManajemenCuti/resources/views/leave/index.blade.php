@extends('layouts.app')

@section('title', 'Cuti Saya - Ketua Divisi')

@section('content')
<div class="max-w-6xl mx-auto px-4 lg:px-0 py-8 space-y-8">

    {{-- ============== HEADER ============== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full
                             bg-gradient-to-tr from-emerald-500 via-green-500 to-sky-500
                             text-[0.55rem] font-bold text-white tracking-[0.22em] uppercase shadow-md">
                    Leader
                </span>

                <div class="flex flex-col">
                    <span class="text-[0.7rem] tracking-[0.25em] text-slate-500 uppercase">
                        Pengajuan Cuti Pribadi
                    </span>
                    <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-slate-900 tracking-[0.18em] uppercase">
                        Cuti Saya
                    </h2>
                </div>
            </div>
            <p class="text-xs sm:text-sm text-slate-500">
                Pantau dan kelola semua pengajuan cuti yang kamu ajukan sebagai Ketua Divisi.
            </p>
        </div>

        <a href="{{ route('leave-requests.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full
                  bg-gradient-to-r from-emerald-600 to-sky-600 text-white text-[0.7rem] font-semibold
                  tracking-[0.18em] uppercase shadow-[0_12px_30px_rgba(22,163,74,0.45)]
                  hover:from-emerald-500 hover:to-sky-500 hover:translate-y-[-1px] transition">
            <span class="text-base leading-none">+</span>
            <span>Ajukan Cuti</span>
        </a>
    </div>

    {{-- ============== FILTER ============== --}}
    <form method="GET" class="space-y-4">
        <div class="bg-white/95 backdrop-blur border border-slate-200 rounded-3xl
                    shadow-[0_18px_40px_rgba(15,23,42,0.12)] px-5 sm:px-7 py-5
                    grid grid-cols-1 md:grid-cols-4 gap-4">

            {{-- Jenis Cuti --}}
            <div class="space-y-1">
                <label class="block text-[0.68rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                    Jenis Cuti
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 6v12m-4-4 4 4 4-4" />
                        </svg>
                    </span>
                    <select name="jenis_cuti"
                            class="w-full text-xs rounded-full border border-slate-300 bg-slate-50/80
                                   pl-8 pr-6 py-2 text-slate-800 appearance-none
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="all" {{ ($jenisFilter ?? 'all') === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="Tahunan" {{ ($jenisFilter ?? '') === 'Tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                        <option value="Sakit"   {{ ($jenisFilter ?? '') === 'Sakit'   ? 'selected' : '' }}>Cuti Sakit</option>
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

            {{-- Status --}}
            <div class="space-y-1">
                <label class="block text-[0.68rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                    Status
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <select name="status"
                            class="w-full text-xs rounded-full border border-slate-300 bg-slate-50/80
                                   pl-8 pr-6 py-2 text-slate-800 appearance-none
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="all"           {{ ($statusFilter ?? 'all') === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="Pending"       {{ ($statusFilter ?? '') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved by Leader" {{ ($statusFilter ?? '') === 'Approved by Leader' ? 'selected' : '' }}>Approved by Leader</option>
                        <option value="Approved"      {{ ($statusFilter ?? '') === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected by Leader" {{ ($statusFilter ?? '') === 'Rejected by Leader' ? 'selected' : '' }}>Rejected by Leader</option>
                        <option value="Rejected by HRD"    {{ ($statusFilter ?? '') === 'Rejected by HRD' ? 'selected' : '' }}>Rejected by HRD</option>
                        <option value="Cancelled"     {{ ($statusFilter ?? '') === 'Cancelled' ? 'selected' : '' }}>Dibatalkan</option>
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

            {{-- Tanggal Mulai dari --}}
            <div class="space-y-1">
                <label class="block text-[0.68rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                    Dari Tanggal
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7h8M7 11h10M7 15h6M5 4h14v16H5z" />
                        </svg>
                    </span>
                    <input type="date" name="start_date" value="{{ $startDate ?? '' }}"
                           class="w-full text-xs rounded-full border border-slate-300 bg-slate-50/80
                                  pl-8 pr-3 py-2 text-slate-800
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            {{-- Sampai Tanggal --}}
            <div class="space-y-1">
                <label class="block text-[0.68rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                    Sampai
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7h8M7 11h10M7 15h6M5 4h14v16H5z" />
                        </svg>
                    </span>
                    <input type="date" name="end_date" value="{{ $endDate ?? '' }}"
                           class="w-full text-xs rounded-full border border-slate-300 bg-slate-50/80
                                  pl-8 pr-3 py-2 text-slate-800
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full
                           bg-emerald-600 text-white text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                           shadow-[0_10px_25px_rgba(22,163,74,0.45)]
                           hover:bg-emerald-500 hover:translate-y-[-1px] transition">
                Terapkan Filter
            </button>

            <a href="{{ route('leave-requests.index') }}"
               class="inline-flex items-center justify-center px-5 py-2.5 rounded-full
                      bg-white text-slate-700 text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                      border border-slate-300 hover:bg-slate-50 hover:border-slate-400 transition">
                Reset
            </a>
        </div>
    </form>

    {{-- ============== LIST CUTI SAYA ============== --}}
    <div class="space-y-4">
        @forelse($requests as $req)
            @php
                $status = $req->status;
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

            <article
                class="rounded-3xl bg-white border border-slate-200 overflow-hidden
                       shadow-[0_18px_40px_rgba(15,23,42,0.12)]">

                {{-- top accent bar --}}
                <div class="h-1.5 bg-gradient-to-r from-emerald-500 via-green-500 to-sky-500"></div>

                <div class="px-5 sm:px-7 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    {{-- Info cuti --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <h3 class="font-semibold text-slate-900 tracking-[0.18em] uppercase text-xs sm:text-sm">
                                {{ $req->jenis_cuti ?? 'Cuti' }}
                            </h3>

                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                         text-[0.65rem] font-semibold {{ $badgeClass }}">
                                {{ $status }}
                            </span>
                        </div>

                        <p class="text-xs text-slate-600">
                            Diajukan pada:
                            <span class="font-semibold text-slate-800">
                                {{ optional($req->tanggal_pengajuan)->format('d M Y') }}
                            </span>
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1 text-xs text-slate-600 mt-1">
                            <p>
                                <span class="text-slate-500">Periode:</span>
                                <span class="font-semibold">
                                    {{ optional($req->tanggal_mulai)->format('d M Y') }}
                                    &mdash;
                                    {{ optional($req->tanggal_selesai)->format('d M Y') }}
                                    ({{ $req->total_hari }} hari)
                                </span>
                            </p>

                            <p>
                                <span class="text-slate-500">Nomor Darurat:</span>
                                <span class="font-semibold">
                                    {{ $req->nomor_darurat ?? '-' }}
                                </span>
                            </p>
                        </div>

                        @if($req->alasan)
                            <p class="text-xs text-slate-600 mt-1">
                                <span class="text-slate-500">Alasan:</span>
                                <span class="font-medium text-slate-800">
                                    {{ $req->alasan }}
                                </span>
                            </p>
                        @endif

                        @if($req->alamat_selama_cuti)
                            <p class="text-xs text-slate-600 mt-1">
                                <span class="text-slate-500">Alamat selama cuti:</span>
                                <span class="font-medium text-slate-800">
                                    {{ $req->alamat_selama_cuti }}
                                </span>
                            </p>
                        @endif

                        @if($req->surat_dokter)
                            <p class="text-xs text-emerald-700 mt-1">
                                <a href="{{ asset('storage/' . $req->surat_dokter) }}"
                                   target="_blank"
                                   class="underline">
                                    Lihat surat keterangan dokter
                                </a>
                            </p>
                        @endif
                    </div>

                    {{-- Aksi --}}
                    <div class="flex flex-col sm:items-end gap-2">

                        <a href="{{ route('leave-requests.show', $req->id) }}"
                           class="inline-flex items-center justify-center px-4 py-1.5 rounded-full
                                  bg-sky-600 text-white text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                                  shadow-[0_8px_20px_rgba(37,99,235,0.45)]
                                  hover:bg-sky-500 hover:translate-y-[-1px] transition">
                            Detail
                        </a>

                        @if($status === 'Pending')
                            <form action="{{ route('leave-requests.cancel', $req->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin membatalkan pengajuan cuti ini?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="inline-flex items-center justify-center px-4 py-1.5 rounded-full
                                               bg-rose-600 text-white text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                                               shadow-[0_8px_20px_rgba(225,29,72,0.5)]
                                               hover:bg-rose-500 hover:translate-y-[-1px] transition">
                                    Batalkan
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
            </article>
        @empty
            <p class="text-sm text-slate-500 text-center py-10">
                Belum ada pengajuan cuti yang kamu buat.
            </p>
        @endforelse
    </div>

    {{-- PAGINATION (kalau pakai paginate) --}}
    @if(method_exists($requests, 'links'))
        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    @endif

</div>
@endsection
