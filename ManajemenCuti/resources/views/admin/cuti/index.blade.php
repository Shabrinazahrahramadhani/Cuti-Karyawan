@extends('layouts.app')

@section('content')

<style>
    @keyframes fadeInUpSoft {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.96); }
        to   { opacity: 1; transform: scale(1); }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    .anim-fade-up { animation: fadeInUpSoft .45s ease-out forwards; }
    .anim-fade-up-delay-1 { animation: fadeInUpSoft .55s ease-out forwards; animation-delay:.08s }
    .anim-fade-up-delay-2 { animation: fadeInUpSoft .65s ease-out forwards; animation-delay:.14s }
    .anim-scale-in { animation: fadeInScale .45s ease-out forwards; }
    .anim-fade { animation: fadeIn .35s ease-out forwards; }

    tbody tr {
        opacity:0;
        transform: translateY(10px);
        animation: fadeInUpSoft .45s ease-out forwards;
    }
    tbody tr:nth-child(1){animation-delay:.05s;}
    tbody tr:nth-child(2){animation-delay:.10s;}
    tbody tr:nth-child(3){animation-delay:.15s;}
    tbody tr:nth-child(4){animation-delay:.20s;}
    tbody tr:nth-child(5){animation-delay:.25s;}
    tbody tr:nth-child(6){animation-delay:.30s;}
    tbody tr:nth-child(7){animation-delay:.35s;}
    tbody tr:nth-child(8){animation-delay:.40s;}
</style>

<div class="max-w-6xl mx-auto mt-10 px-4 anim-fade">

    @if(session('success'))
        <div class="mb-6 inline-flex items-center gap-3 px-5 py-3 bg-emerald-50 border border-emerald-500 
                    text-emerald-700 rounded-2xl shadow-sm anim-scale-in">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl bg-emerald-500 text-slate-100 text-xs font-bold">
                ✓
            </span>
            <span class="text-sm font-semibold">

            </span>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 anim-fade-up">
        <div class="space-y-2">
            <h2 class="text-xl md:text-2xl font-semibold text-slate-900 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full
                             bg-sky-100 text-[0.6rem] font-bold text-slate-900 tracking-[0.18em] uppercase">
                    CUTI
                </span>
                <span class="tracking-[0.2em] uppercase text-sm sm:text-base text-slate-800">
                    Manajemen Cuti
                </span>
            </h2>
            <p class="text-xs text-slate-600">
                Kelola dan pantau seluruh pengajuan cuti karyawan.
            </p>
        </div>
    </div>

    {{-- FILTER BAR --}}
    <div class="mb-6 bg-white border border-slate-200 rounded-3xl 
                shadow-[0_8px_22px_rgba(15,23,42,0.08)] p-5 anim-fade-up-delay-1">

        <form method="GET" class="grid lg:grid-cols-3 gap-4 items-end text-xs">

            {{-- Jenis Cuti --}}
            <div>
                <label class="block mb-1 font-semibold uppercase text-slate-700 text-[0.7rem]">
                    Jenis Cuti
                </label>
                <select name="jenis_cuti"
                        class="w-full border border-slate-300 rounded-full px-3 py-2 bg-slate-50 text-slate-800">
                    <option value="">Semua</option>
                    <option value="tahunan" {{ request('jenis_cuti') == 'tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                    <option value="sakit"   {{ request('jenis_cuti') == 'sakit'   ? 'selected' : '' }}>Cuti Sakit</option>
                </select>
            </div>

            {{-- Status --}}
            <div>
                <label class="block mb-1 font-semibold uppercase text-slate-700 text-[0.7rem]">
                    Status
                </label>
                <select name="status"
                        class="w-full border border-slate-300 rounded-full px-3 py-2 bg-slate-50 text-slate-800">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                    <option value="approved by leader" {{ request('status')=='approved_leader'?'selected':'' }}>Approved Leader</option>
                    <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved HRD</option>
                    <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
                    <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
                </select>
            </div>

            {{-- Pengajuan Dari --}}
            <div>
                <label class="block mb-1 font-semibold uppercase text-slate-700 text-[0.7rem]">
                    Pengajuan Dari
                </label>
                <input type="date" name="tanggal_pengajuan_from" value="{{ request('tanggal_pengajuan_from') }}"
                       class="w-full border border-slate-300 rounded-full px-3 py-2 bg-slate-50 text-slate-800">
            </div>

            {{-- Tombol --}}
            <div class="lg:col-span-3 flex justify-end gap-2 mt-2">
                <a href="{{ route('admin.cuti.index') }}"
                   class="px-4 py-2 rounded-full bg-white text-slate-700 text-xs font-semibold uppercase
                          border border-slate-300 shadow-sm hover:bg-slate-50 hover:border-slate-400 transition">
                    Reset
                </a>
                <button type="submit"
                        class="px-5 py-2 rounded-full bg-sky-50 text-sky-700 text-xs font-semibold uppercase
                               border border-sky-300 shadow-sm
                               hover:bg-sky-100 hover:border-sky-400 transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- RINGKASAN --}}
    @isset($summary)
        <div class="mt-4 grid gap-3 md:grid-cols-5 anim-fade-up-delay-2">

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm px-4 py-3 text-slate-800">
                <div class="text-[0.7rem] uppercase text-slate-600">Total</div>
                <div class="mt-1 text-2xl font-bold">{{ $summary['total'] ?? 0 }}</div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-xl shadow-sm px-4 py-3 text-slate-800">
                <div class="text-[0.7rem] uppercase text-amber-700">Pending</div>
                <div class="mt-1 text-2xl font-bold">{{ $summary['pending'] ?? 0 }}</div>
            </div>

            <div class="bg-sky-50 border border-sky-200 rounded-xl shadow-sm px-4 py-3 text-slate-800">
                <div class="text-[0.7rem] uppercase text-sky-700">Approved Leader</div>
                <div class="mt-1 text-2xl font-bold">{{ $summary['approved_leader'] ?? 0 }}</div>
            </div>

            <div class="bg-emerald-50 border border-emerald-200 rounded-xl shadow-sm px-4 py-3 text-slate-800">
                <div class="text-[0.7rem] uppercase text-emerald-700">Approved HRD</div>
                <div class="mt-1 text-2xl font-bold">{{ $summary['approved'] ?? 0 }}</div>
            </div>

            <div class="bg-rose-50 border border-rose-200 rounded-xl shadow-sm px-4 py-3 text-slate-800">
                <div class="text-[0.7rem] uppercase text-rose-700">Rejected / Cancelled</div>
                <div class="mt-1 text-2xl font-bold">{{ ($summary['rejected']??0)+($summary['cancelled']??0) }}</div>
            </div>

        </div>
    @endisset

    {{-- TABLE --}}
    <div class="mt-6 overflow-x-auto bg-white shadow-[0_8px_22px_rgba(15,23,42,0.1)]
                rounded-3xl border border-slate-200 anim-fade-up-delay-2">

        <table class="min-w-full">
            
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase text-slate-600">No</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase text-slate-600">Karyawan</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase text-slate-600">Jenis Cuti</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase text-slate-600">Tgl Pengajuan</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase text-slate-600">Periode</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase text-slate-600">Hari</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase text-slate-600">Status</th>
                    <th class="px-4 py-3 text-center font-semibold text-xs uppercase text-slate-600">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($leaveRequests as $cuti)
                    <tr class="border-t border-slate-100 hover:bg-slate-50 transition">

                        <td class="px-4 py-3 text-sm text-slate-900">{{ $loop->iteration }}</td>

                        <td class="px-4 py-3 text-sm text-slate-900 font-semibold">
                            {{ $cuti->user->profile->nama_lengkap ?? $cuti->user->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-sm text-slate-800">
                            {{ ucfirst($cuti->jenis_cuti ?? '-') }}
                        </td>

                        <td class="px-4 py-3 text-sm text-slate-800">
                            {{ optional($cuti->created_at)->format('d M Y') ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-sm text-slate-800">
                            @if($cuti->tanggal_mulai && $cuti->tanggal_selesai)
                                {{ $cuti->tanggal_mulai->format('d M Y') }} - {{ $cuti->tanggal_selesai->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-4 py-3 text-sm text-slate-800">
                            {{ $cuti->total_hari ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-sm">
                            @php
                                $status = $cuti->status ?? 'pending';
                                $badge = match($status) {
                                    'pending'         => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'approved_leader' => 'bg-sky-100 text-sky-800 border-sky-200',
                                    'approved'        => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'rejected'        => 'bg-rose-100 text-rose-800 border-rose-200',
                                    'cancelled'       => 'bg-slate-100 text-slate-700 border-slate-200',
                                    default           => 'bg-slate-100 text-slate-700 border-slate-200',
                                };
                            @endphp

                            <span class="inline-flex px-3 py-1 rounded-full text-[0.7rem]
                                         font-semibold uppercase border {{ $badge }}">
                                {{ str_replace('_',' ',ucfirst($status)) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('admin.cuti.show',$cuti->id) }}"
                               class="inline-block py-1.5 px-3 rounded-full
                                      bg-sky-50 text-sky-700 text-[0.7rem] font-semibold uppercase
                                      border border-sky-300 shadow-sm
                                      hover:bg-sky-100 hover:border-sky-400 transition">
                                Detail
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-sm text-slate-600">
                            Belum ada pengajuan cuti.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

@endsection
