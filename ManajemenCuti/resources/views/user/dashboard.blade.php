@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="flex items-center gap-3 text-2xl md:text-3xl font-semibold text-slate-900">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl
                             bg-gradient-to-tr from-sky-500 via-blue-500 to-indigo-500
                             text-[0.6rem] font-bold text-white tracking-[0.18em] uppercase">
                    USER
                </span>

                <span class="tracking-[0.12em] uppercase text-sm sm:text-base">
                    Dashboard Karyawan
                </span>
            </h2>

            <p class="mt-2 text-xs text-slate-600">
                Halo, {{ $profile->nama_lengkap ?? $user->name }} ✨ — pantau kuota & pengajuan cutimu di sini.
            </p>
        </div>
    </div>

    {{-- GRID RINGKASAN UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        {{-- Sisa Kuota Tahunan --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex flex-col gap-3">
            <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.16em] uppercase">
                Sisa Kuota Tahunan
            </p>

            <p class="text-4xl font-semibold text-sky-600 leading-tight">
                {{ $sisaKuota }}
            </p>

            <p class="text-[0.75rem] text-slate-700">
                Dari total {{ $kuotaTotal }} hari kerja per tahun.
            </p>
        </div>

        {{-- Kuota Terpakai --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex flex-col gap-3">
            <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.16em] uppercase">
                Kuota Terpakai
            </p>

            <p class="text-4xl font-semibold text-rose-600 leading-tight">
                {{ $kuotaTerpakai }}
            </p>

            <p class="text-[0.75rem] text-slate-700">
                Total cuti tahunan yang telah disetujui.
            </p>
        </div>

        {{-- Total Pengajuan Cuti --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex flex-col gap-3">
            <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.16em] uppercase">
                Total Pengajuan Cuti
            </p>

            <p class="text-4xl font-semibold text-indigo-600 leading-tight">
                {{ $totalPengajuan }}
            </p>

            <p class="text-[0.75rem] text-slate-700">
                Termasuk pending, disetujui, ditolak, dan dibatalkan.
            </p>
        </div>

        {{-- Total Cuti Sakit --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex flex-col gap-3">
            <p class="text-[0.7rem] font-semibold text-slate-500 tracking-[0.16em] uppercase">
                Jumlah Cuti Sakit
            </p>

            <p class="text-4xl font-semibold text-purple-600 leading-tight">
                {{ $jumlahCutiSakit }}
            </p>

            <p class="text-[0.75rem] text-slate-700">
                Total pengajuan cuti sakit dengan surat dokter.
            </p>
        </div>

    </div>

    {{-- DIVISI & QUICK ACTION --}}
    <div class="grid md:grid-cols-2 gap-6">

        {{-- Divisi & Atasan --}}
        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-6">
            <h3 class="text-sm font-semibold tracking-[0.14em] uppercase text-slate-600 mb-2">
                Divisi & Atasan
            </h3>

            <p class="text-lg font-semibold text-slate-900">
                {{ $division->nama_divisi ?? 'Belum ada divisi' }}
            </p>

            <p class="mt-1 text-sm text-slate-700">
                Ketua Divisi:
                <span class="font-semibold text-sky-600">
                    {{ $leader->profile->nama_lengkap ?? $leader->name ?? '-' }}
                </span>
            </p>

            <p class="mt-3 text-xs text-slate-500">
                Atasan menentukan alur persetujuan cuti (Leader → HRD).
            </p>
        </div>

        {{-- Quick Action: Ajukan Cuti & Riwayat --}}
        <div class="grid sm:grid-cols-2 gap-4">

            {{-- Ajukan Cuti (conditional, aman kalau profile null) --}}
            @if($profile && $profile->status_aktif)
                <a href="{{ route('user.leave.create') }}"
                   class="block bg-gradient-to-r from-sky-500 to-sky-400
      
                   rounded-3xl border border-slate-200 shadow-sm
                          px-5 py-4 hover:-translate-y-1 transition">
                    <div class="text-[0.7rem] tracking-[0.16em] uppercase text-slate-50 mb-1">
                        Ajukan Cuti
                    </div>
                    <div class="text-lg font-semibold text-white">
                        Pengajuan Baru
                    </div>
                    <p class="mt-2 text-[0.75rem] text-white/80">
                        Pilih jenis cuti & kirim ke atasanmu.
                    </p>
                </a>
            @else
                <div class="block bg-slate-100 rounded-3xl border border-slate-200
                            shadow-sm px-5 py-4 cursor-not-allowed">
                    <div class="text-[0.7rem] tracking-[0.16em] uppercase text-slate-400 mb-1">
                        Ajukan Cuti
                    </div>
                    <div class="text-lg font-semibold text-slate-500">
                        Tidak Dapat Mengajukan
                    </div>
                    <p class="mt-2 text-[0.75rem] text-rose-600 font-medium">
                        Akun non-aktif atau profil belum lengkap, hubungi HRD.
                    </p>
                </div>
            @endif

            {{-- Riwayat Cuti --}}
            <a href="{{ route('user.leave.history') }}"
               class="block bg-white rounded-3xl border border-slate-200
                      shadow-sm px-5 py-4 hover:-translate-y-1 transition">
                <div class="text-[0.7rem] tracking-[0.16em] uppercase text-slate-500 mb-1">
                    Riwayat Cuti
                </div>
                <div class="text-lg font-semibold text-slate-900">
                    Lihat Pengajuan
                </div>
                <p class="mt-2 text-[0.75rem] text-slate-700">
                    Cek status cutimu secara lengkap.
                </p>
            </a>

        </div>

    </div>
</div>
@endsection
