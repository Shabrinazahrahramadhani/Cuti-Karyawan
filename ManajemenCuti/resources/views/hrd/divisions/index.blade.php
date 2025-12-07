@extends('layouts.app')

@section('title', 'Data Divisi')

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
                    Data Divisi
                </span>
            </h2>
            <p class="mt-2 text-xs text-slate-600">
                Distribusi divisi, ketua divisi, dan jumlah anggota di seluruh organisasi.
            </p>
        </div>

        <a href="{{ route('hrd.dashboard') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-full
                  bg-white text-slate-900 text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                  border border-slate-200 hover:bg-slate-50 transition">
            ← Kembali ke Dashboard
        </a>
    </div>

    {{-- List Divisi --}}
    <div class="space-y-4">
        @forelse($divisions as $division)
            @php
                $leader      = $division->ketuaDivisi ?? null;
                $leaderName  = optional(optional($leader)->profile)->nama_lengkap
                               ?? optional($leader)->name
                               ?? '-';
                $membersCount = $division->members_count ?? $division->members->count();
            @endphp

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-4">
                <div class="flex flex-col sm:flex-row justify-between gap-3 sm:items-center">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">
                            {{ $division->nama_divisi }}
                        </p>
                        <p class="mt-1 text-[0.75rem] text-slate-600">
                            Ketua Divisi:
                            <span class="font-medium text-sky-700">
                                {{ $leaderName }}
                            </span>
                        </p>
                    </div>
                    <div class="sm:text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full
                                     bg-slate-100 text-slate-800 text-[0.7rem] font-semibold tracking-[0.12em] uppercase">
                            Anggota: {{ $membersCount }}
                        </span>
                    </div>
                </div>

                @if(!empty($division->deskripsi))
                    <p class="mt-3 text-xs text-slate-600">
                        {{ $division->deskripsi }}
                    </p>
                @endif

                {{-- List anggota ringkas --}}
                @if($membersCount > 0)
                    <div class="mt-3 border-t border-slate-100 pt-3">
                        <p class="text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-600 mb-1">
                            Anggota (maks. 5 nama ditampilkan)
                        </p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($division->members->take(5) as $member)
                                @php
                                    $mName = optional($member->profile)->nama_lengkap
                                             ?? $member->name
                                             ?? '-';
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full bg-slate-50
                                             border border-slate-200 text-[0.7rem] text-slate-800">
                                    {{ $mName }}
                                </span>
                            @endforeach

                            @if($membersCount > 5)
                                <span class="inline-flex px-2.5 py-1 rounded-full bg-slate-100
                                             text-[0.7rem] text-slate-600">
                                    +{{ $membersCount - 5 }} lainnya
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-xs text-slate-500">
                Belum ada data divisi.
            </p>
        @endforelse
    </div>

</div>
@endsection
