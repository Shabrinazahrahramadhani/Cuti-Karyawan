@extends('layouts.app')

@section('title', 'Detail Approval Cuti HRD')

@section('content')
<div class="container mx-auto p-6">

    <h1 class="text-3xl font-bold text-emerald-700 mb-2">
        Detail Approval Cuti
    </h1>
    <p class="text-gray-600 mb-4 text-sm">
        Lihat detail pengajuan cuti dan riwayat persetujuan sebelum memberikan keputusan akhir sebagai HRD.
    </p>

    <a href="{{ route('approvals.index') }}"
       class="inline-block mb-4 text-xs text-emerald-600 hover:underline">
        ← Kembali ke daftar approval
    </a>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        {{-- KIRI: Informasi Pengajuan --}}
        <div class="bg-white rounded-lg shadow p-5 text-gray-800">
            <h2 class="text-xl font-semibold text-gray-800 mb-3">
                Informasi Pengajuan
            </h2>

            @php
                $divisionName = optional(optional($leaveRequest->user)->profile->division)->nama_divisi;
            @endphp

            <div class="mb-2">
                <span class="text-sm text-gray-500">Nama Karyawan</span>
                <p class="text-base font-semibold text-gray-900">
                    {{ $leaveRequest->user->name ?? '-' }}
                </p>
                <p class="text-xs text-gray-500">
                    {{ $divisionName ? 'Divisi: ' . $divisionName : 'Divisi belum diatur' }}
                </p>
            </div>

            <div class="mb-2">
                <span class="text-sm text-gray-500">Jenis Cuti</span>
                <p class="text-base text-gray-800">
                    {{ $leaveRequest->jenis_cuti }}
                </p>
            </div>

            <div class="mb-2">
                <span class="text-sm text-gray-500">Periode Cuti</span>
                <p class="text-base text-gray-800">
                    {{ optional($leaveRequest->tanggal_mulai)->format('d M Y') }}
                    &mdash;
                    {{ optional($leaveRequest->tanggal_selesai)->format('d M Y') }}
                    ({{ $leaveRequest->total_hari }} hari)
                </p>
            </div>

            <div class="mb-2">
                <span class="text-sm text-gray-500">Tanggal Pengajuan</span>
                <p class="text-base text-gray-800">
                    {{ optional($leaveRequest->tanggal_pengajuan)->format('d M Y') }}
                </p>
            </div>

            @php
                $badgeClass = 'bg-gray-100 text-gray-700';
                if ($leaveRequest->status === 'Pending') {
                    $badgeClass = 'bg-yellow-100 text-yellow-700';
                } elseif ($leaveRequest->status === 'Approved by Leader') {
                    $badgeClass = 'bg-blue-100 text-blue-700';
                } elseif ($leaveRequest->status === 'Approved') {
                    $badgeClass = 'bg-green-100 text-green-700';
                } elseif (str_contains($leaveRequest->status, 'Rejected')) {
                    $badgeClass = 'bg-red-100 text-red-700';
                }
            @endphp

            <div class="mb-2">
                <span class="text-sm text-gray-500">Status Saat Ini</span>
                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                    {{ $leaveRequest->status }}
                </span>
            </div>

            <div class="mt-3">
                <span class="text-sm text-gray-500">Alasan Cuti</span>
                <p class="text-sm text-gray-800 mt-1">
                    {{ $leaveRequest->alasan }}
                </p>
            </div>

            @if($leaveRequest->alamat_selama_cuti)
                <div class="mt-3">
                    <span class="text-sm text-gray-500">Alamat selama cuti</span>
                    <p class="text-sm text-gray-800 mt-1">
                        {{ $leaveRequest->alamat_selama_cuti }}
                    </p>
                </div>
            @endif

            @if($leaveRequest->nomor_darurat)
                <div class="mt-3">
                    <span class="text-sm text-gray-500">Kontak darurat</span>
                    <p class="text-sm text-gray-800 mt-1">
                        {{ $leaveRequest->nomor_darurat }}
                    </p>
                </div>
            @endif

            @if($leaveRequest->surat_dokter)
                <div class="mt-3">
                    <span class="text-sm text-gray-500">Surat Keterangan Dokter</span>
                    <p class="mt-1">
                        <a href="{{ asset('storage/' . $leaveRequest->surat_dokter) }}"
                           target="_blank"
                           class="text-sm text-emerald-600 underline">
                            Lihat surat dokter
                        </a>
                    </p>
                </div>
            @endif
        </div>

        {{-- KANAN: Aksi HRD --}}
        <div class="bg-white rounded-lg shadow p-5 text-gray-800">
            <h2 class="text-xl font-semibold text-gray-800 mb-3">
                Keputusan HRD
            </h2>

            @if(! in_array($leaveRequest->status, ['Approved', 'Rejected by HRD']))
                <p class="text-sm text-gray-600 mb-3">
                    Berikan keputusan akhir untuk pengajuan ini. Pastikan kamu sudah membaca
                    catatan dari atasan (jika ada) di bagian timeline.
                </p>

                {{-- APPROVE --}}
                <form method="POST" action="{{ route('approvals.approve', $leaveRequest) }}" class="mb-3">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-2 rounded bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                        Approve sebagai HRD
                    </button>
                </form>

                {{-- REJECT --}}
                <form method="POST" action="{{ route('approvals.reject', $leaveRequest) }}">
                    @csrf
                    <label class="block text-xs font-semibold text-red-800 mb-1">
                        Catatan penolakan HRD (wajib, minimal 10 karakter)
                    </label>
                    <textarea name="note" rows="4"
                        class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring focus:ring-red-200"
                        required></textarea>

                    <button type="submit"
                        class="mt-2 w-full px-4 py-2 rounded bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">
                        Reject pengajuan ini
                    </button>
                </form>
            @else
                <p class="text-sm text-gray-700 mb-2">
                    Pengajuan ini sudah diproses sebagai
                    <span class="font-semibold">{{ $leaveRequest->status }}</span> oleh HRD.
                </p>

                @if($hrdApproval && $hrdApproval->note)
                    <p class="text-xs text-gray-700 mt-2">
                        <span class="font-semibold">Catatan HRD:</span>
                        {{ $hrdApproval->note }}
                    </p>
                @endif
            @endif
        </div>
    </div>

    {{-- TIMELINE APPROVAL --}}
    <div class="bg-white rounded-lg shadow p-5 text-gray-800">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            Timeline Approval
        </h2>

        <ol class="border-l border-gray-300 pl-4 space-y-4 text-sm">

            {{-- Step 1: Pengajuan --}}
            <li>
                <div class="flex gap-2 items-start">
                    <div class="w-3 h-3 rounded-full bg-blue-500 mt-1.5"></div>
                    <div>
                        <p class="font-semibold text-gray-800">
                            Pengajuan dibuat oleh {{ $leaveRequest->user->name ?? 'Karyawan' }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ optional($leaveRequest->tanggal_pengajuan)->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </li>

            {{-- Step 2: Approval atasan (Leader) --}}
            <li>
                <div class="flex gap-2 items-start">
                    <div class="w-3 h-3 rounded-full bg-sky-500 mt-1.5"></div>
                    <div>
                        <p class="font-semibold text-gray-800">
                            Proses di atasan (Ketua Divisi)
                        </p>
                        @if($leaderApproval)
                            <p class="text-xs text-gray-500 mb-1">
                                {{ ucfirst($leaderApproval->status) }} oleh
                                {{ $leaderApproval->approver->name ?? 'Leader' }}
                                pada {{ optional($leaderApproval->approved_at)->format('d M Y H:i') }}
                            </p>
                            @if($leaderApproval->note)
                                <p class="text-xs text-gray-700">
                                    <span class="font-semibold">Catatan atasan:</span>
                                    {{ $leaderApproval->note }}
                                </p>
                            @endif
                        @else
                            <p class="text-xs text-gray-500">
                                Belum ada tindakan dari atasan (Ketua Divisi).
                            </p>
                        @endif
                    </div>
                </div>
            </li>

            {{-- Step 3: Approval HRD --}}
            <li>
                <div class="flex gap-2 items-start">
                    <div class="w-3 h-3 rounded-full bg-emerald-500 mt-1.5"></div>
                    <div>
                        <p class="font-semibold text-gray-800">
                            Proses di HRD
                        </p>
                        @if($hrdApproval)
