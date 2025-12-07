@extends('layouts.app')

@section('title', 'Riwayat Pengajuan Cuti Tim')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-[#1d4ed8] mb-2">
        Riwayat Pengajuan Cuti
    </h1>

    <p class="text-sm text-gray-600 mb-4">
        Divisi:
        <span class="font-semibold">
            {{ $division->nama_divisi ?? '-' }}
        </span>
    </p>

    @if($leaves->isEmpty())
        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-800">
            Belum ada pengajuan cuti dari anggota divisi ini.
        </div>
    @else
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Nama</th>
                        <th class="px-4 py-2 text-left">Jenis Cuti</th>
                        <th class="px-4 py-2 text-left">Periode</th>
                        <th class="px-4 py-2 text-left">Total Hari</th>
                        <th class="px-4 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaves as $leave)
                        <tr class="border-t">
                            <td class="px-4 py-2">
                                {{ $leave->user->name ?? '-' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $leave->jenis_cuti }}
                            </td>
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($leave->tanggal_mulai)->format('d M Y') }}
                                —
                                {{ \Carbon\Carbon::parse($leave->tanggal_selesai)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $leave->total_hari }} hari
                            </td>
                            <td class="px-4 py-2">
                                {{ $leave->status }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $leaves->links() }}
        </div>
    @endif
</div>
@endsection
