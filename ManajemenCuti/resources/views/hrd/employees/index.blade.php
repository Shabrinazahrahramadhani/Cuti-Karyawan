@extends('layouts.app')

@section('title', 'Data Karyawan')

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
                    Data Karyawan
                </span>
            </h2>
            <p class="mt-2 text-xs text-slate-600">
                Daftar seluruh karyawan yang terdaftar di sistem cuti beserta divisi dan status aktifnya.
            </p>
        </div>

        <a href="{{ route('hrd.dashboard') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-full
                  bg-white text-slate-900 text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                  border border-slate-200 hover:bg-slate-50 transition">
            ← Kembali ke Dashboard
        </a>
    </div>

    {{-- Tabel karyawan, tanpa aksi edit / delete untuk Admin & HRD (view-only) --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-600">No</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-600">Nama</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-600">Email</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-600">Role</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-600">Divisi</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-600">Status</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-600">Masa Kerja</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($employees as $user)
                    @php
                        $fullName = optional($user->profile)->nama_lengkap ?? $user->name ?? '-';
                        $divisionName = optional(optional($user->profile)->division)->nama_divisi ?? '-';
                        $isActive = optional($user->profile)->status_aktif ?? true;
                        $joined = \Carbon\Carbon::parse($user->created_at);
                        $years = $joined->diffInYears(now());
                        $masaKerjaStr = $years < 1 ? '< 1 tahun' : $years.' tahun';
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-slate-900">{{ $fullName }}</td>
                        <td class="px-4 py-3 text-sm text-slate-700">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-sm text-slate-700">{{ $user->role }}</td>
                        <td class="px-4 py-3 text-sm text-slate-700">{{ $divisionName }}</td>
                        <td class="px-4 py-3">
                            @if($isActive)
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700
                                             text-[0.7rem] font-semibold tracking-[0.12em] uppercase">
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-700
                                             text-[0.7rem] font-semibold tracking-[0.12em] uppercase">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-700">
                            {{ $masaKerjaStr }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-sm text-slate-500">
                            Belum ada data karyawan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
