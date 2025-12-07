@extends('layouts.app')

@section('title', 'Approval Cuti HRD')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-4 inline-flex items-center gap-3 px-5 py-3 bg-emerald-50 border border-emerald-300
                    text-emerald-800 rounded-2xl text-xs">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-xl bg-emerald-500 text-white text-xs font-bold">
                ✓
            </span>
            <span class="font-semibold">
                {{ session('success') }}
            </span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 inline-flex items-center gap-3 px-5 py-3 bg-rose-50 border border-rose-300
                    text-rose-700 rounded-2xl text-xs">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-xl bg-rose-500 text-white text-xs font-bold">
                !
            </span>
            <span class="font-semibold">
                {{ session('error') }}
            </span>
        </div>
    @endif

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
                    Approval Cuti HRD
                </span>
            </h2>
            <p class="mt-2 text-xs text-slate-600">
                Kelola persetujuan akhir pengajuan cuti dari seluruh karyawan dan ketua divisi.
            </p>
        </div>

        <a href="{{ route('hrd.dashboard') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-full
                  bg-white text-slate-900 text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                  border border-slate-200 hover:bg-slate-50 transition">
            ← Kembali ke Dashboard
        </a>
    </div>

    {{-- Filter status --}}
    <form method="GET" action="{{ route('approvals.index') }}" class="mb-6">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-4 flex flex-wrap items-end gap-4">

            <div>
                <label class="block text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-600 mb-1">
                    Status
                </label>
                <select name="status"
                        class="w-40 text-xs border border-slate-300 rounded-xl px-3 py-2 bg-white text-slate-900
                               focus:outline-none focus:ring-2 focus:ring-sky-300">
                    <option value="">Hanya yang pending</option>
                    <option value="all_pending" {{ ($statusFilter ?? '') === 'all_pending' ? 'selected' : '' }}>
                        Hanya yang pending
                    </option>
                    <option value="all" {{ ($statusFilter ?? '') === 'all' ? 'selected' : '' }}>
                        Semua (pending & selesai)
                    </option>
                </select>
            </div>

            <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-full
                           bg-sky-200 text-sky-900 text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                           border border-sky-400 hover:bg-sky-300 transition">
                Terapkan Filter
            </button>
        </div>
    </form>

    @if($requests->isEmpty())
        <p class="text-xs text-slate-500">
            Tidak ada pengajuan cuti yang menunggu persetujuan final HRD.
        </p>
    @else
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
            <div class="px-5 py-4 border-b border-slate-200">
                <p class="text-[0.7rem] text-slate-600">
                    Menampilkan
                    <span class="font-semibold text-slate-900">{{ $requests->total() }}</span>
                    pengajuan cuti.
                </p>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($requests as $req)
                    @php
                        $nama      = optional($req->user->profile)->nama_lengkap ?? $req->user->name ?? '-';
                        $divisi    = optional(optional($req->user->profile)->division)->nama_divisi ?? '-';
                        $jenis     = ucfirst($req->jenis_cuti);
                        $status    = $req->status;
                        $mulai     = \Carbon\Carbon::parse($req->tanggal_mulai)->format('d M Y');
                        $selesai   = \Carbon\Carbon::parse($req->tanggal_selesai)->format('d M Y');
                        $pengajuan = \Carbon\Carbon::parse($req->tanggal_pengajuan)->format('d M Y');
                    @endphp

                    <div class="px-5 py-4 text-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                            {{-- Info utama --}}
                            <div>
                                <p class="font-semibold text-slate-900">
                                    {{ $nama }}
                                </p>
                                <p class="text-[0.7rem] text-slate-500">
                                    Divisi: {{ $divisi }} • Jenis: {{ $jenis }}
                                </p>
                                <p class="mt-1 text-[0.7rem] text-slate-600">
                                    Periode:
                                    <span class="font-medium">
                                        {{ $mulai }} – {{ $selesai }}
                                    </span>
                                    ({{ $req->total_hari ?? '-' }} hari kerja)
                                </p>
                                <p class="mt-1 text-[0.7rem] text-slate-500">
                                    Diajukan: {{ $pengajuan }} • Status saat ini:
                                    <span class="font-semibold">
                                        {{ $status }}
                                    </span>
                                </p>
                            </div>

                            {{-- Aksi per item --}}
                            <div class="flex flex-col gap-2 items-stretch sm:items-end">
                                <div class="inline-flex flex-wrap gap-2 justify-end">

                                    {{-- APPROVE SATUAN --}}
                                    <form method="POST" action="{{ route('approvals.process', $req->id) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-full bg-emerald-500 text-white
                                                       text-[0.7rem] font-semibold tracking-[0.14em] uppercase
                                                       hover:bg-emerald-600 transition">
                                            Approve
                                        </button>
                                    </form>

                                    {{-- BUKA FORM REJECT --}}
                                    <button type="button"
                                            onclick="document.getElementById('reject-form-{{ $req->id }}').classList.toggle('hidden')"
                                            class="px-3 py-1.5 rounded-full bg-rose-500 text-white
                                                   text-[0.7rem] font-semibold tracking-[0.14em] uppercase
                                                   hover:bg-rose-600 transition">
                                        Reject
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Collapsible reject form --}}
                        <div id="reject-form-{{ $req->id }}" class="mt-3 hidden">
                            <form method="POST" action="{{ route('approvals.process', $req->id) }}"
                                  class="bg-rose-50 border border-rose-200 rounded-xl p-3 space-y-2">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <label class="block text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-rose-700 mb-1">
                                    Alasan Penolakan (minimal 10 karakter)
                                </label>
                                <textarea name="note" rows="2"
                                          class="w-full text-xs border border-rose-300 rounded-lg px-3 py-2
                                                 focus:outline-none focus:ring-2 focus:ring-rose-300"
                                          placeholder="Tuliskan alasan penolakan di sini..."></textarea>
                                <div class="flex justify-end">
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-full bg-rose-600 text-white
                                                   text-[0.7rem] font-semibold tracking-[0.14em] uppercase
                                                   hover:bg-rose-700 transition"
                                            onclick="return confirm('Yakin menolak pengajuan ini?')">
                                        Kirim Penolakan
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    @endif
</div>
@endsection
