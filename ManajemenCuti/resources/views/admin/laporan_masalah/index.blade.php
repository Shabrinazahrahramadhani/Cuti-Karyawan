@extends('layouts.app')

@section('content')

<style>
    @keyframes fadeInUpSoft {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .page-anim { animation: fadeInUpSoft .35s ease-out forwards; }
</style>

<div class="max-w-6xl mx-auto mt-10 px-4 page-anim">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl md:text-3xl font-semibold text-slate-900 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full
                             bg-rose-100 text-[0.6rem] font-bold text-slate-900 tracking-[0.18em] uppercase">
                </span>
                <span class="tracking-[0.16em] uppercase text-sm sm:text-base text-slate-800">
                    Laporan Masalah Cuti
                </span>
            </h2>
            <p class="mt-2 text-xs text-slate-500">
                Menampilkan cuti yang <span class="font-semibold">ditolak, dibatalkan, atau pending &gt; 7 hari</span>.
            </p>
        </div>
    </div>

    {{-- Filter Bar (disederhanakan) --}}
    <div class="mb-6 bg-white border border-slate-200 rounded-2xl shadow-[0_12px_32px_rgba(15,23,42,0.08)] p-5">
        <form method="GET" class="grid md:grid-cols-3 gap-4 items-end text-xs">

            {{-- Jenis Cuti --}}
            <div class="space-y-1">
                <label class="font-semibold tracking-[0.12em] uppercase text-slate-700 text-[0.75rem]">
                    Jenis Cuti
                </label>
                <select name="jenis_cuti"
                        class="w-full border border-slate-300 rounded-xl px-3 py-2 bg-white text-slate-900
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                    <option value="">Semua</option>
                    <option value="tahunan" {{ request('jenis_cuti') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                    <option value="sakit"   {{ request('jenis_cuti') == 'sakit'   ? 'selected' : '' }}>Sakit</option>
                </select>
            </div>

            {{-- Status --}}
            <div class="space-y-1">
                <label class="font-semibold tracking-[0.12em] uppercase text-slate-700 text-[0.75rem]">
                    Status
                </label>
                <select name="status"
                        class="w-full border border-slate-300 rounded-xl px-3 py-2 bg-white text-slate-900
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                    <option value="">Semua</option>
                    <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Pending (&gt; 7 hari)</option>
                    <option value="rejected"  {{ request('status') == 'rejected'  ? 'selected' : '' }}>Rejected</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            {{-- Pengajuan Dari --}}
            <div class="space-y-1">
                <label class="font-semibold tracking-[0.12em] uppercase text-slate-700 text-[0.75rem]">
                    Pengajuan Dari
                </label>
                <input type="date" name="tanggal_pengajuan_from"
                       value="{{ request('tanggal_pengajuan_from') }}"
                       class="w-full border border-slate-300 rounded-xl px-3 py-2 bg-white text-slate-900
                              focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
            </div>

            {{-- Tombol --}}
            <div class="md:col-span-3 flex justify-end gap-2 mt-2">
                <a href="{{ route('admin.laporan_masalah.index') }}"
                   class="px-4 py-2 rounded-full bg-slate-50 text-slate-800 text-xs font-semibold tracking-[0.16em] uppercase
                          border border-slate-200 hover:bg-slate-100 transition">
                    Reset
                </a>
                <button type="submit"
                        class="px-4 py-2 rounded-full bg-sky-200 text-sky-900 text-xs font-semibold tracking-[0.16em] uppercase
                               border border-sky-500 shadow-sm hover:bg-sky-300 transition">
                    Filter
                </button>
            </div>

        </form>
    </div>

    {{-- Tabel Laporan Masalah (tidak diubah) --}}
    <div class="overflow-x-auto bg-white shadow-[0_14px_40px_rgba(15,23,42,0.08)] rounded-2xl border border-slate-200">
        <table class="min-w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">No</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">Karyawan</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">Jenis Cuti</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">Tgl Pengajuan</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">Periode</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">Masalah</th>
                    <th class="px-4 py-3 text-center text-slate-600 font-semibold text-[0.7rem] tracking-[0.16em] uppercase">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($laporan as $cuti)
                    @php
                        $status = $cuti->status ?? 'pending';
                        if ($status === 'pending' && $cuti->created_at->lte(now()->subDays(7))) {
                            $labelMasalah = 'Pending lebih dari 7 hari';
                        } elseif ($status === 'rejected') {
                            $labelMasalah = 'Ditolak (Leader / HRD)';
                        } elseif ($status === 'cancelled') {
                            $labelMasalah = 'Dibatalkan oleh karyawan';
                        } else {
                            $labelMasalah = '-';
                        }

                        $badgeClass = match($status) {
                            'pending'   => 'bg-amber-50 border-amber-300 text-amber-900',
                            'rejected'  => 'bg-rose-50  border-rose-300  text-rose-900',
                            'cancelled' => 'bg-slate-50 border-slate-300 text-slate-900',
                            default     => 'bg-slate-50 border-slate-300 text-slate-900',
                        };
                    @endphp

                    <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                        <td class="px-4 py-3 text-sm text-slate-700 align-top">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-900 font-semibold align-top">
                            {{ $cuti->user->profile->nama_lengkap ?? $cuti->user->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-800 align-top">
                            {{ ucfirst($cuti->jenis_cuti ?? '-') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-800 align-top">
                            {{ $cuti->created_at?->format('d M Y') ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-800 align-top">
                            @if($cuti->tanggal_mulai && $cuti->tanggal_selesai)
                                {{ $cuti->tanggal_mulai->format('d M Y') }} - {{ $cuti->tanggal_selesai->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm align-top">
                            <span class="inline-flex px-3 py-1 rounded-full text-[0.7rem] font-semibold tracking-[0.14em] uppercase border {{ $badgeClass }}">
                                {{ strtoupper(str_replace('_', ' ', $status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-rose-800 font-semibold align-top">
                            {{ $labelMasalah }}
                        </td>
                        <td class="px-4 py-3 text-center align-top">
                            <a href="{{ route('admin.cuti.show', $cuti->id) }}"
                               class="inline-block bg-sky-100 text-sky-900 py-1.5 px-3 rounded-full text-[0.7rem] font-semibold
                                      tracking-[0.12em] uppercase border border-sky-400 hover:bg-sky-200 transition">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-sm text-slate-500">
                            Tidak ada cuti bermasalah untuk kriteria ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $laporan->links() }}
    </div>

</div>

@endsection
