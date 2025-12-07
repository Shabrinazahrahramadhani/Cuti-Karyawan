@extends('layouts.app')

@section('title', 'Riwayat Keputusan Cuti HRD')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="flex items-center gap-3 text-xl sm:text-2xl md:text-3xl font-semibold text-slate-900">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl
                             bg-gradient-to-tr from-orange-400 via-pink-500 to-sky-500
                             text-[0.6rem] font-bold text-white tracking-[0.18em] uppercase">
                    HRD
                </span>
                <span class="tracking-[0.12em] uppercase text-sm sm:text-base">
                    Riwayat Keputusan Cuti
                </span>
            </h2>
            <p class="mt-2 text-xs text-slate-600">
                Daftar seluruh pengajuan cuti yang sudah diproses HRD (approve / reject).
            </p>
        </div>

        <a href="{{ route('hrd.dashboard') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-full
                  bg-white text-slate-900 text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                  border border-slate-200 hover:bg-slate-50 transition">
            ← Kembali ke Dashboard
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('history.index') }}" class="mb-6">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-600 mb-1">
                    Status
                </label>
                <select name="status"
                        class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2 bg-white text-slate-900
                               focus:outline-none focus:ring-2 focus:ring-sky-300">
                    <option value="">Semua</option>
                    <option value="Approved" {{ $status === 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ $status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div>
                <label class="block text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-600 mb-1">
                    Jenis Cuti
                </label>
                <select name="jenis_cuti"
                        class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2 bg-white text-slate-900
                               focus:outline-none focus:ring-2 focus:ring-sky-300">
                    <option value="">Semua</option>
                    <option value="tahunan" {{ $jenisCuti === 'tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                    <option value="sakit"   {{ $jenisCuti === 'sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-full
                               bg-sky-200 text-sky-900 text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                               border border-sky-400 hover:bg-sky-300 transition">
                    Terapkan Filter
                </button>
            </div>
        </div>
    </form>

    {{-- Tabel --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-600">No</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-600">Nama</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-600">Divisi</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-600">Jenis</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-600">Periode</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-600">Status</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-600">Catatan HRD</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($leaves as $leave)
                    @php
                        $nama   = optional($leave->user->profile)->nama_lengkap ?? $leave->user->name ?? '-';
                        $divisi = optional(optional($leave->user->profile)->division)->nama_divisi ?? '-';
                        $mulai  = \Carbon\Carbon::parse($leave->tanggal_mulai)->format('d M Y');
                        $sel    = \Carbon\Carbon::parse($leave->tanggal_selesai)->format('d M Y');
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-slate-900">{{ $nama }}</td>
                        <td class="px-4 py-3 text-sm text-slate-700">{{ $divisi }}</td>
                        <td class="px-4 py-3 text-sm text-slate-700 capitalize">{{ $leave->jenis_cuti }}</td>
                        <td class="px-4 py-3 text-sm text-slate-700">
                            {{ $mulai }} – {{ $sel }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($leave->status === 'Approved')
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[0.7rem] font-semibold tracking-[0.12em] uppercase">
                                    Approved
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-rose-100 text-rose-700 text-[0.7rem] font-semibold tracking-[0.12em] uppercase">
                                    Rejected
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-700">
                            {{ $leave->catatan_hrd ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-sm text-slate-500">
                            Belum ada riwayat keputusan cuti.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
