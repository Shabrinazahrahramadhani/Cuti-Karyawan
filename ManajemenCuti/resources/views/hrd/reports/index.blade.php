@extends('layouts.app')

@section('title', 'Laporan Cuti Karyawan')

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
                Laporan Cuti Karyawan
            </span>
            </h2>
            <p class="mt-2 text-xs text-slate-600">
                Rekap pengajuan cuti berdasarkan periode, divisi, jenis cuti, dan status.
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
    <form method="GET" action="{{ route('hrd.reports.index') }}" class="mb-6">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-4 grid grid-cols-1 md:grid-cols-4 gap-4">

            {{-- Periode --}}
            <div>
                <label class="block text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-600 mb-1">
                    Tanggal Dari
                </label>
                <input type="date" name="tanggal_dari"
                       value="{{ $tanggalDari }}"
                       class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2 bg-white text-slate-900
                              focus:outline-none focus:ring-2 focus:ring-sky-300">
            </div>

            <div>
                <label class="block text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-600 mb-1">
                    Tanggal Sampai
                </label>
                <input type="date" name="tanggal_sampai"
                       value="{{ $tanggalSampai }}"
                       class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2 bg-white text-slate-900
                              focus:outline-none focus:ring-2 focus:ring-sky-300">
            </div>

            {{-- Divisi --}}
            <div>
                <label class="block text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-600 mb-1">
                    Divisi
                </label>
                <select name="division_id"
                        class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2 bg-white text-slate-900
                               focus:outline-none focus:ring-2 focus:ring-sky-300">
                    <option value="">Semua</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}"
                            {{ (string)$divisionId === (string)$division->id ? 'selected' : '' }}>
                            {{ $division->nama_divisi }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status / jenis --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-600 mb-1">
                        Status
                    </label>
                    <select name="status"
                            class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2 bg-white text-slate-900
                                   focus:outline-none focus:ring-2 focus:ring-sky-300">
                        <option value="">Semua</option>
                        <option value="Pending"  {{ $status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ $status === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ $status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Cancelled" {{ $status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-600 mb-1">
                        Jenis
                    </label>
                    <select name="jenis_cuti"
                            class="w-full text-xs border border-slate-300 rounded-xl px-3 py-2 bg-white text-slate-900
                                   focus:outline-none focus:ring-2 focus:ring-sky-300">
                        <option value="">Semua</option>
                        <option value="tahunan" {{ $jenisCuti === 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                        <option value="sakit"   {{ $jenisCuti === 'sakit' ? 'selected' : '' }}>Sakit</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <a href="{{ route('hrd.reports.index') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-full
                      bg-slate-50 text-slate-800 text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                      border border-slate-200 hover:bg-slate-100 transition">
                Reset
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-full
                           bg-sky-200 text-sky-900 text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                           border border-sky-400 hover:bg-sky-300 transition">
                Terapkan Filter
            </button>
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
                            <span class="px-3 py-1 rounded-full text-[0.7rem] font-semibold tracking-[0.12em] uppercase
                                         @if($leave->status === 'Approved')
                                             bg-emerald-100 text-emerald-700
                                         @elseif($leave->status === 'Rejected')
                                             bg-rose-100 text-rose-700
                                         @elseif($leave->status === 'Cancelled')
                                             bg-slate-100 text-slate-600
                                         @else
                                             bg-amber-100 text-amber-700
                                         @endif">
                                {{ $leave->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-500">
                            Tidak ada data cuti sesuai filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
