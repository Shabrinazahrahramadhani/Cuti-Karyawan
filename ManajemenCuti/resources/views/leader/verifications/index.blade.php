@extends('layouts.app')

@section('title', 'Verifikasi Pengajuan Cuti')

@section('content')
<div class="max-w-6xl mx-auto px-4 lg:px-0 py-8 space-y-8">

    {{-- HEADER --}}
    <div class="space-y-3">
        <div class="inline-flex items-center gap-3">
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl
                         bg-gradient-to-tr from-blue-600 via-sky-500 to-emerald-500
                         text-[0.6rem] font-bold text-white tracking-[0.22em] uppercase shadow-md">
                Leader
            </span>

            <div class="flex flex-col">
                <span class="text-[0.7rem] tracking-[0.25em] text-slate-500 uppercase">
                    Verifikasi Pengajuan Cuti
                </span>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-slate-900 tracking-[0.18em] uppercase">
                    Verifikasi Cuti Tim
                </h1>
            </div>
        </div>
        <p class="text-xs sm:text-sm text-slate-600">
            Tinjau dan proses pengajuan cuti anggota divisi kamu. Approve atau reject dengan catatan yang jelas.
        </p>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs sm:text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if(!$division)
        <div class="rounded-3xl border border-amber-200 bg-amber-50 px-5 py-4 text-xs sm:text-sm text-amber-900 shadow-sm">
            <p class="font-semibold">
                Kamu belum terdaftar sebagai Ketua pada divisi manapun.
            </p>
            <p class="mt-1">
                Silakan hubungi Admin untuk mengatur kamu sebagai Ketua Divisi.
            </p>
        </div>
    @else
        @if($requests->isEmpty())
            <div class="rounded-3xl border border-slate-200 bg-white px-5 py-5 text-xs sm:text-sm text-slate-500 shadow-sm">
                Belum ada pengajuan cuti yang menunggu verifikasi.
            </div>
        @else
            <div class="space-y-4">

                @foreach($requests as $req)
                    @php
                        $isSakit   = $req->jenis_cuti === 'Sakit';
                        $profile   = $req->user->profile ?? null;
                        $fotoUrl   = $profile && $profile->foto ? asset('storage/'.$profile->foto) : null;
                        $initial   = strtoupper(mb_substr($req->user->name, 0, 1));
                    @endphp

                    <article
                        class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                               shadow-[0_20px_50px_rgba(15,23,42,0.18)] overflow-hidden">

                        {{-- Accent bar --}}
                        <div class="h-1.5 bg-gradient-to-r from-blue-500 via-sky-500 to-emerald-500"></div>

                        <div class="px-5 sm:px-7 py-4 flex flex-col md:flex-row md:items-start md:justify-between gap-4 text-slate-800">

                            {{-- Info Pengaju (termasuk FOTO) --}}
                            <div class="flex-1 space-y-2">
                                <div class="flex items-center gap-3 mb-1">
                                    @if($fotoUrl)
                                        <div class="w-10 h-10 rounded-2xl overflow-hidden shadow-lg border border-slate-200">
                                            <img src="{{ $fotoUrl }}"
                                                 alt="Foto {{ $req->user->name }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-700
                                                    text-white flex items-center justify-center text-base font-semibold shadow-lg">
                                            {{ $initial }}
                                        </div>
                                    @endif

                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $req->user->name }}
                                        </p>
                                        <p class="text-[0.7rem] text-slate-500">
                                            Diajukan pada:
                                            <span class="font-medium text-slate-700">
                                                {{ optional($req->tanggal_pengajuan)->format('d M Y') }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <p class="text-xs sm:text-sm text-slate-700">
                                    <span class="font-semibold">Jenis Cuti:</span>
                                    {{ $req->jenis_cuti }}
                                </p>
                                <p class="text-xs sm:text-sm text-slate-700">
                                    <span class="font-semibold">Periode:</span>
                                    {{ optional($req->tanggal_mulai)->format('d M Y') }}
                                    &mdash;
                                    {{ optional($req->tanggal_selesai)->format('d M Y') }}
                                    <span class="text-slate-500">
                                        ({{ $req->total_hari }} hari kerja)
                                    </span>
                                </p>

                                @if($req->alasan)
                                    <p class="text-xs sm:text-sm text-slate-700 mt-1">
                                        <span class="font-semibold">Alasan:</span>
                                        {{ $req->alasan }}
                                    </p>
                                @endif

                                @if($req->alamat_selama_cuti)
                                    <p class="text-xs sm:text-sm text-slate-700 mt-1">
                                        <span class="font-semibold">Alamat selama cuti:</span>
                                        {{ $req->alamat_selama_cuti }}
                                    </p>
                                @endif

                                @if($req->nomor_darurat)
                                    <p class="text-xs sm:text-sm text-slate-700 mt-1">
                                        <span class="font-semibold">Kontak darurat:</span>
                                        {{ $req->nomor_darurat }}
                                    </p>
                                @endif

                                @if($req->surat_dokter)
                                    <p class="text-xs sm:text-emerald-700 mt-2">
                                        <a href="{{ asset('storage/' . $req->surat_dokter) }}"
                                           target="_blank"
                                           class="underline">
                                            Lihat surat keterangan dokter
                                        </a>
                                    </p>
                                @endif

                                <a href="{{ route('verifications.show', $req) }}
                                   " class="inline-flex items-center mt-2 text-[0.72rem] text-sky-700 hover:text-sky-600 hover:underline">
                                    Lihat detail lengkap →
                                </a>
                            </div>

                            {{-- Aksi Approve / Reject --}}
                            <div class="w-full md:w-72 flex flex-col gap-3">

                                {{-- Approve --}}
                                <form action="{{ route('verifications.approve', $req) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-full px-4 py-2 rounded-full
                                                   bg-emerald-500 text-slate-950 text-[0.75rem] font-semibold
                                                   tracking-[0.18em] uppercase
                                                   shadow-[0_12px_30px_rgba(16,185,129,0.7)]
                                                   hover:bg-emerald-400 hover:-translate-y-[1px] transition">
                                        Approve
                                    </button>
                                </form>

                            {{-- Reject --}}
                                    <details class="w-full group">
                                        <summary
                                            class="px-4 py-2 rounded-full border border-rose-400
                                                bg-rose-50 text-rose-700 text-[0.75rem] font-semibold
                                                tracking-[0.18em] uppercase cursor-pointer
                                                hover:bg-rose-100 transition list-none flex items-center justify-between">
                                            <span>Reject dengan alasan</span>
                                            <span class="text-[0.8rem] group-open:rotate-180 transition-transform">⌄</span>
                                        </summary>

                                        <div class="mt-2 p-3 border border-rose-200 rounded-2xl bg-rose-50">
                                            <form action="{{ route('verifications.reject', $req) }}" method="POST" class="space-y-2">
                                                @csrf
                                                <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-rose-800">
                                                    Alasan Penolakan
                                                </label>
                                                <textarea name="note"
                                                        rows="3"
                                                        class="w-full text-xs rounded-2xl border border-rose-300 bg-white
                                                                px-3 py-2 text-slate-800 resize-y
                                                                focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-rose-400"
                                                        required
                                                        placeholder="Tuliskan alasan penolakan yang jelas...">{{ old('note') }}</textarea>

                                                @error('note')
                                                    <p class="mt-1 text-[0.7rem] text-rose-600 font-semibold">{{ $message }}</p>
                                                @enderror

                                                <button type="submit"
                                                        class="w-full mt-1 px-4 py-2 rounded-full
                                                            bg-rose-600 text-white text-[0.75rem] font-semibold
                                                            tracking-[0.18em] uppercase
                                                            shadow-[0_10px_26px_rgba(225,29,72,0.7)]
                                                            hover:bg-rose-500 hover:-translate-y-[1px] transition">
                                                    Kirim Penolakan
                                                </button>
                                            </form>
                                        </div>
                                    </details>
                            </div>

                        </div>
                    </article>
                @endforeach

            </div>
        @endif
    @endif

</div>
@endsection
