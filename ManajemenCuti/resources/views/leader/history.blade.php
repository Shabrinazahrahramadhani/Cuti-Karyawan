@extends('layouts.app')

@section('title', 'Riwayat Cuti Tim')

@section('content')
<div class="max-w-6xl mx-auto px-4 lg:px-0 py-8 space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="space-y-3">
            <div class="inline-flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl
                             bg-gradient-to-tr from-purple-600 via-fuchsia-500 to-sky-500
                             text-[0.6rem] font-bold text-white tracking-[0.22em] uppercase shadow-md">
                    Leader
                </span>

                <div class="flex flex-col">
                    <span class="text-[0.7rem] tracking-[0.25em] text-slate-500 uppercase">
                        Riwayat Pengajuan Cuti Tim
                    </span>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-slate-900 tracking-[0.18em] uppercase">
                        Riwayat Cuti Tim
                    </h1>
                </div>
            </div>
            <p class="text-xs sm:text-sm text-slate-600 max-w-2xl">
                Lihat dan telusuri semua pengajuan cuti anggota divisi yang kamu pimpin, lengkap dengan status dan riwayat approval.
            </p>
        </div>

        @if($division)
            <div class="w-full lg:w-auto">
                <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                            shadow-[0_20px_50px_rgba(15,23,42,0.18)] px-5 py-4 min-w-[260px]">
                    <p class="text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-slate-500">
                        Divisi
                    </p>
                    <p class="text-sm sm:text-base font-semibold text-slate-900 mt-1">
                        {{ $division->nama_divisi }}
                    </p>
                    @if($division->deskripsi)
                        <p class="text-[0.7rem] text-slate-500 mt-1">
                            {{ $division->deskripsi }}
                        </p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    @if(!$division)
        <div class="rounded-3xl border border-amber-200 bg-amber-50 px-5 py-4 text-xs sm:text-sm text-amber-900 shadow-sm">
            <p class="font-semibold">
                Kamu belum terdaftar sebagai Ketua di divisi manapun.
            </p>
            <p class="mt-1">
                Silakan hubungi Admin untuk mengatur kamu sebagai Ketua Divisi pada salah satu divisi.
            </p>
        </div>
    @else

        {{-- FILTER BAR --}}
        <form method="GET"
              class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                     shadow-[0_18px_45px_rgba(15,23,42,0.15)] px-5 sm:px-7 py-5 mb-6
                     grid grid-cols-1 md:grid-cols-[minmax(0,1.2fr)_minmax(0,1.2fr)_minmax(0,0.8fr)] gap-4">

            {{-- Status --}}
            <div class="space-y-1.5">
                <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                    Status
                </label>
                <select name="status"
                        class="w-full text-sm rounded-2xl border border-slate-300 bg-slate-50/80
                               px-3 py-2.5 text-slate-800
                               focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    @php
                        $statusOptions = [
                            'all'               => 'Semua Status',
                            'Pending'           => 'Pending',
                            'Approved by Leader'=> 'Approved by Leader',
                            'Approved'          => 'Approved (HRD)',
                            'Rejected'          => 'Rejected',
                            'Cancelled'         => 'Cancelled',
                        ];
                    @endphp
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}"
                            {{ ($statusFilter ?? 'all') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Jenis Cuti --}}
            <div class="space-y-1.5">
                <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                    Jenis Cuti
                </label>
                <select name="jenis_cuti"
                        class="w-full text-sm rounded-2xl border border-slate-300 bg-slate-50/80
                               px-3 py-2.5 text-slate-800
                               focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <option value="all" {{ ($jenisFilter ?? 'all') == 'all' ? 'selected' : '' }}>
                        Semua Jenis
                    </option>
                    <option value="Tahunan" {{ ($jenisFilter ?? '') == 'Tahunan' ? 'selected' : '' }}>
                        Cuti Tahunan
                    </option>
                    <option value="Sakit" {{ ($jenisFilter ?? '') == 'Sakit' ? 'selected' : '' }}>
                        Cuti Sakit
                    </option>
                </select>
            </div>

            {{-- Tombol (SOFT BUTTON) --}}
            <div class="flex items-end justify-start md:justify-end">
                <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-full
                               bg-purple-50 text-purple-700 text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                               border border-purple-300 shadow-sm
                               hover:bg-purple-100 hover:border-purple-400 hover:-translate-y-[1px] transition">
                    Terapkan Filter
                </button>
            </div>
        </form>

        {{-- TABEL RIWAYAT --}}
        @if($requests->isEmpty())
            <div class="rounded-3xl border border-slate-200 bg-white px-5 py-5 text-xs sm:text-sm text-slate-500 shadow-sm">
                Tidak ada pengajuan cuti dari anggota divisi ini.
            </div>
        @else
            <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                        shadow-[0_20px_50px_rgba(15,23,42,0.18)] overflow-hidden">
                <div class="px-5 sm:px-7 py-4 flex items-center justify-between gap-3 border-b border-slate-100">
                    <div>
                        <p class="text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-slate-500">
                            Daftar Pengajuan
                        </p>
                        <p class="text-xs text-slate-500">
                            Total: {{ $requests->total() }} pengajuan
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Nama Karyawan
                                </th>
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Jenis Cuti
                                </th>
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Periode
                                </th>
                                <th class="px-4 py-2 text-center text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Total Hari
                                </th>
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Status
                                </th>
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Tgl Pengajuan
                                </th>
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    Leader
                                </th>
                                <th class="px-4 py-2 text-left text-[0.7rem] font-semibold tracking-[0.16em] uppercase text-slate-500">
                                    HRD
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($requests as $req)
                                @php
                                    $status = $req->status;
                                    $badgeClass = 'bg-slate-100 text-slate-700';
                                    if ($status === 'Pending') {
                                        $badgeClass = 'bg-yellow-100 text-yellow-700';
                                    } elseif ($status === 'Approved by Leader' || $status === 'Approved') {
                                        $badgeClass = 'bg-emerald-100 text-emerald-700';
                                    } elseif ($status === 'Rejected' || $status === 'Cancelled') {
                                        $badgeClass = 'bg-rose-100 text-rose-700';
                                    }
                                @endphp
                                <tr>
                                    {{-- Nama Karyawan --}}
                                    <td class="px-4 py-2 text-slate-800 text-xs sm:text-sm">
                                        {{ $req->user->name ?? '-' }}
                                    </td>

                                    {{-- Jenis Cuti --}}
                                    <td class="px-4 py-2 text-slate-700 text-xs sm:text-sm">
                                        {{ $req->jenis_cuti }}
                                    </td>

                                    {{-- Periode --}}
                                    <td class="px-4 py-2 text-slate-700 text-xs sm:text-sm">
                                        {{ optional($req->tanggal_mulai)->format('d M Y') }}
                                        &mdash;
                                        {{ optional($req->tanggal_selesai)->format('d M Y') }}
                                    </td>

                                    {{-- Total Hari --}}
                                    <td class="px-4 py-2 text-center text-slate-700 text-xs sm:text-sm">
                                        {{ $req->total_hari }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.65rem] font-semibold {{ $badgeClass }}">
                                            {{ $status }}
                                        </span>
                                    </td>

                                    {{-- Tgl Pengajuan --}}
                                    <td class="px-4 py-2 text-slate-700 text-xs sm:text-sm">
                                        {{ optional($req->tanggal_pengajuan)->format('d M Y') }}
                                    </td>

                                   {{-- Info Leader --}}
                                    <td class="px-4 py-2 text-slate-700 text-[0.7rem]">
                                        @if($req->leader_id)
                                            @php
                                                $isRejected = $req->status === 'Rejected';
                                            @endphp

                                            <span class="block font-semibold {{ $isRejected ? 'text-rose-600' : 'text-emerald-600' }}">
                                                {{ $isRejected ? 'Rejected' : 'Approved' }}
                                            </span>

                                            <span class="block text-slate-500">
                                                {{ optional($req->approved_leader_at ?? $req->updated_at)->format('d M Y') }}
                                            </span>

                                            <span class="block text-slate-500 text-[0.65rem]">
                                                {{ optional($req->leader)->name ?? '-' }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-[0.7rem]">-</span>
                                        @endif
                                    </td>

                                    {{-- Info HRD --}}
                                    <td class="px-4 py-2 text-slate-700 text-[0.7rem]">
                                        @php
                                            // dianggap sudah ada keputusan HRD kalau status final (Approved / Rejected / Cancelled)
                                            $hasHrdDecision = in_array($req->status, ['Approved', 'Rejected', 'Cancelled']);
                                            $isRejectedHrd  = $req->status === 'Rejected';
                                            $hrdName        = optional($req->hrd)->name ?? 'HRD';
                                            $hrdDate        = $req->approved_hrd_at ?? $req->updated_at;
                                        @endphp

                                        @if($hasHrdDecision)
                                            <span class="block font-semibold {{ $isRejectedHrd ? 'text-rose-600' : 'text-emerald-600' }}">
                                                {{ $isRejectedHrd ? 'Rejected' : 'Approved' }}
                                            </span>

                                            @if($hrdDate)
                                                <span class="block text-slate-500">
                                                    {{ optional($hrdDate)->format('d M Y') }}
                                                </span>
                                            @endif

                                            <span class="block text-slate-500 text-[0.65rem]">
                                                {{ $hrdName }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-[0.7rem]">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-5 sm:px-7 py-4 border-t border-slate-100">
                    {{ $requests->links() }}
                </div>
            </div>
        @endif
    @endif

</div>
@endsection
