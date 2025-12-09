@extends('layouts.app')

@section('title', 'Ajukan Cuti')

@section('content')
<div class="max-w-6xl mx-auto px-4 lg:px-0 py-8 space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="space-y-3">
            <div class="inline-flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl
                             bg-gradient-to-tr from-blue-600 via-indigo-500 to-emerald-500
                             text-[0.6rem] font-bold text-white tracking-[0.22em] uppercase shadow-md">
                    @if(auth()->user()->role === 'leader')
                        Leader
                    @else
                        User
                    @endif
                </span>

                <div class="flex flex-col">
                    <span class="text-[0.7rem] tracking-[0.25em] text-slate-500 uppercase">
                        Form Pengajuan Cuti
                    </span>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-slate-900 tracking-[0.18em] uppercase">
                        Ajukan Cuti
                    </h1>
                </div>
            </div>

            <p class="text-xs sm:text-sm text-slate-600 max-w-2xl">
                Lengkapi data di bawah ini untuk mengajukan cuti. Sistem akan otomatis menghitung jumlah
                hari kerja (Senin–Jumat) dan menerapkan aturan sesuai jenis cuti yang dipilih.
            </p>
        </div>

        {{-- KARTU INFO PROFILE & KUOTA --}}
        <div class="w-full lg:w-auto">
            <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                        shadow-[0_20px_50px_rgba(15,23,42,0.18)] px-5 py-4 flex flex-col gap-3 min-w-[260px]">

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-sky-500
                                text-white flex items-center justify-center text-lg font-bold shadow-lg">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="leading-tight">
                        <p class="text-sm font-semibold text-slate-900">
                            {{ $profile->nama_lengkap ?? $user->name ?? '-' }}
                        </p>
                        <p class="text-[0.7rem] text-slate-500">
                            @if(auth()->user()->role === 'leader')
                                Ketua Divisi
                            @else
                                Karyawan
                            @endif
                        </p>
                       @php
                            $divisionName = optional(optional($profile)->division)->nama_divisi;
                        @endphp

                        @if($divisionName)
                            <p class="text-[0.7rem] text-slate-500 mt-0.5">
                                Divisi: <span class="font-semibold text-slate-700">{{ $divisionName }}</span>
                            </p>
                        @else
                            <p class="text-[0.7rem] text-slate-500 mt-0.5">
                                Divisi: <span class="font-semibold text-slate-700">-</span>
                            </p>
                        @endif
                    </div>
                </div>

                <div class="h-px bg-gradient-to-r from-slate-100 via-slate-200 to-slate-100 my-1"></div>

                <div class="grid grid-cols-2 gap-3 text-[0.72rem]">
                    <div class="space-y-1">
                        <p class="uppercase tracking-[0.18em] text-slate-500 font-semibold">
                            Kuota Tahunan
                        </p>
                        <p class="text-2xl font-bold text-emerald-600">
                            {{ $sisaKuota }} <span class="text-xs font-semibold text-slate-500">hari</span>
                        </p>
                        <p class="text-[0.68rem] text-slate-500">
                            Tidak termasuk Sabtu &amp; Minggu.
                        </p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase tracking-[0.18em] text-slate-500 font-semibold">
                            Jenis Cuti
                        </p>
                        <p class="text-xs text-slate-600">
                            • <span class="font-semibold">Cuti Tahunan</span> (H-3, pakai kuota)<br>
                            • <span class="font-semibold">Cuti Sakit</span> (H-0 s/d +3 hari, wajib surat dokter)
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERT VALIDASI / SUCCESS --}}
    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs sm:text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs sm:text-sm text-rose-800">
            <p class="font-semibold mb-1">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM PENGAJUAN CUTI --}}
    @php
        $isLeader = auth()->user()->role === 'leader'
    @endphp

    <form
        method="POST"
        action="{{ $isLeader ? route('leader.leave.store') : route('leave.store') }}"
        enctype="multipart/form-data"
        class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,0.9fr)] gap-6 items-start"
    >
        @csrf

        {{-- KOLOM KIRI: DATA CUTI --}}
        <div class="space-y-4">
            <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                        shadow-[0_20px_50px_rgba(15,23,42,0.12)] px-6 py-5 space-y-4">

                <h2 class="text-sm font-semibold text-slate-900 tracking-[0.18em] uppercase">
                    Informasi Cuti
                </h2>

                {{-- JENIS CUTI --}}
                <div class="space-y-1.5">
                    <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                        Jenis Cuti <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="jenis_cuti"
                                class="w-full text-sm rounded-2xl border border-slate-300 bg-slate-50/80
                                       pl-3 pr-9 py-2.5 text-slate-800
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih jenis cuti</option>
                            <option value="Tahunan" {{ old('jenis_cuti') === 'Tahunan' ? 'selected' : '' }}>
                                Cuti Tahunan
                            </option>
                            <option value="Sakit" {{ old('jenis_cuti') === 'Sakit' ? 'selected' : '' }}>
                                Cuti Sakit
                            </option>
                        </select>
                        <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.106l3.71-3.875a.75.75 0 0 1 1.08 1.04l-4.25 4.44a.75.75 0 0 1-1.08 0l-4.25-4.44a.75.75 0 0 1 .02-1.06Z"
                                      clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>
                    <p class="text-[0.68rem] text-slate-500">
                        • Tahunan: minimal H-3.<br>
                        • Sakit: H-0 sampai maksimal 3 hari setelah mulai sakit (wajib surat dokter).
                    </p>
                </div>

                {{-- TANGGAL --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                            Tanggal Mulai <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                               class="w-full text-sm rounded-2xl border border-slate-300 bg-slate-50/80
                                      px-3 py-2.5 text-slate-800
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                            Tanggal Selesai <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                               class="w-full text-sm rounded-2xl border border-slate-300 bg-slate-50/80
                                      px-3 py-2.5 text-slate-800
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                {{-- ALASAN --}}
                <div class="space-y-1.5">
                    <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                        Alasan Cuti <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="alasan" rows="3"
                              class="w-full text-sm rounded-2xl border border-slate-300 bg-slate-50/80
                                     px-3 py-2.5 text-slate-800 resize-y
                                     focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Jelaskan secara singkat, misalnya: menghadiri acara keluarga, istirahat karena sakit, dll.">{{ old('alasan') }}</textarea>
                </div>

                {{-- ALAMAT SELAMA CUTI --}}
                <div class="space-y-1.5">
                    <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                        Alamat Selama Cuti
                    </label>
                    <textarea name="alamat_selama_cuti" rows="2"
                              class="w-full text-sm rounded-2xl border border-slate-300 bg-slate-50/80
                                     px-3 py-2.5 text-slate-800 resize-y
                                     focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Contoh: Jl. Melati No. 10, Lubuklinggau">{{ old('alamat_cuti') }}</textarea>
                </div>

                {{-- NOMOR DARURAT --}}
                <div class="space-y-1.5">
                    <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                        Nomor Darurat
                    </label>
                    <input type="text" name="nomor_darurat" value="{{ old('nomor_darurat') }}"
                           class="w-full text-sm rounded-2xl border border-slate-300 bg-slate-50/80
                                  px-3 py-2.5 text-slate-800
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: 08xxxxxxxxxx (keluarga terdekat)">
                    <p class="text-[0.68rem] text-slate-500">
                        Nomor yang bisa dihubungi jika terjadi keadaan darurat saat kamu sedang cuti.
                    </p>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: DOKUMEN & AKSI --}}
        <div class="space-y-4">

            {{-- DOKUMEN SURAT DOKTER --}}
            <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                        shadow-[0_16px_40px_rgba(15,23,42,0.12)] px-6 py-5 space-y-3">
                <h2 class="text-sm font-semibold text-slate-900 tracking-[0.18em] uppercase">
                    Dokumen Pendukung
                </h2>

                <div class="space-y-1.5">
                    <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                        Surat Keterangan Dokter
                    </label>
                    <input type="file" name="surat_dokter"
                           class="block w-full text-xs text-slate-700
                                  file:mr-3 file:py-1.5 file:px-3 file:rounded-full
                                  file:border-0 file:text-xs file:font-semibold
                                  file:bg-slate-900 file:text-white
                                  hover:file:bg-slate-800">
                    <p class="text-[0.68rem] text-slate-500 mt-1">
                        Wajib diupload untuk <span class="font-semibold">Cuti Sakit</span>.
                        Format: PDF/JPG/PNG, maksimal 2MB.
                    </p>
                </div>
            </div>

            {{-- CATATAN ATURAN & TOMBOL --}}
            <div class="rounded-3xl bg-slate-900 text-slate-50 px-6 py-5 space-y-4
                        shadow-[0_18px_45px_rgba(15,23,42,0.65)]">

                <div class="space-y-2">
                    <p class="text-[0.68rem] font-semibold tracking-[0.22em] uppercase text-slate-400">
                        Ringkasan Aturan
                    </p>
                    <ul class="text-xs space-y-1.5 text-slate-100">
                        <li>• Cuti tahunan: pengajuan minimal <span class="font-semibold">H-3</span> sebelum tanggal mulai.</li>
                        <li>• Cuti sakit: pengajuan H-0 sampai maksimal <span class="font-semibold">+3 hari</span> setelah tanggal mulai sakit.</li>
                        <li>• Perhitungan hari cuti hanya pada <span class="font-semibold">Senin–Jumat</span>.</li>
                        <li>• Sistem akan menolak pengajuan yang <span class="font-semibold">overlap</span> dengan cuti lain yang masih aktif.</li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
                    <p class="text-[0.7rem] text-slate-300 max-w-xs">
                        Pastikan semua data sudah benar sebelum dikirim.
                        Setelah dikirim, pengajuan akan diproses
                        @if($isLeader)
                            langsung oleh <span class="font-semibold">HRD</span>.
                        @else
                            oleh <span class="font-semibold">Ketua Divisi</span> terlebih dahulu.
                        @endif
                    </p>

                    <div class="flex flex-row gap-2">
                        <button type="reset"
                                class="inline-flex items-center justify-center px-4 py-2 rounded-full
                                       border border-slate-500 text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                                       text-slate-100 hover:bg-slate-800 transition">
                            Reset
                        </button>

                        <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-2 rounded-full
                                       bg-emerald-500 text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                                       text-slate-950 shadow-[0_14px_35px_rgba(16,185,129,0.8)]
                                       hover:bg-emerald-400 hover:-translate-y-[1px] active:translate-y-0 transition">
                            Kirim Pengajuan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection
