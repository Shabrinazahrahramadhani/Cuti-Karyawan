@props(['leave'])

<div class="space-y-4">

    {{-- STEP 1: PENGAJUAN --}}
    <div class="flex items-start gap-3">
        <div class="mt-1 h-3 w-3 rounded-full bg-blue-500"></div>
        <div>
            <div class="font-semibold text-gray-800">Pengajuan Cuti</div>
            <div class="text-sm text-gray-600">
                Diajukan oleh <span class="font-medium">{{ $leave->user->name }}</span>
                @if($leave->tanggal_pengajuan)
                    pada {{ \Carbon\Carbon::parse($leave->tanggal_pengajuan)->format('d-m-Y') }}
                @endif
            </div>
            <div class="text-sm text-gray-600">
                Jenis: <span class="font-medium">{{ $leave->jenis_cuti }}</span> |
                Periode:
                {{ \Carbon\Carbon::parse($leave->tanggal_mulai)->format('d-m-Y') }}
                s/d
                {{ \Carbon\Carbon::parse($leave->tanggal_selesai)->format('d-m-Y') }}
                ({{ $leave->total_hari }} hari)
            </div>
        </div>
    </div>

    {{-- Garis vertikal --}}
    <div class="ml-1 border-l border-gray-300 h-6"></div>

    {{-- STEP 2: VERIFIKASI LEADER --}}
    <div class="flex items-start gap-3">
        <div class="mt-1 h-3 w-3 rounded-full
            @if($leave->status === 'Approved by Leader' || $leave->status === 'Approved' || $leave->status === 'Rejected')
                bg-green-500
            @else
                bg-gray-400
            @endif
        "></div>
        <div>
            <div class="font-semibold text-gray-800">Verifikasi Ketua Divisi</div>

            @if($leave->status === 'Pending')
                <div class="text-sm text-gray-600">
                    Menunggu verifikasi ketua divisi.
                </div>
            @else
                <div class="text-sm text-gray-600">
                    Ketua Divisi:
                    <span class="font-medium">
                        {{ optional($leave->leader)->name ?? '-' }}
                    </span>
                </div>

                @if($leave->status === 'Approved by Leader' || $leave->status === 'Approved')
                    <div class="text-sm text-green-600">
                        Disetujui oleh Ketua Divisi.
                    </div>
                @elseif($leave->status === 'Rejected')
                    <div class="text-sm text-red-600">
                        Pengajuan ditolak (oleh Leader atau HRD).
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Garis vertikal --}}
    <div class="ml-1 border-l border-gray-300 h-6"></div>

    {{-- STEP 3: FINAL HRD --}}
    <div class="flex items-start gap-3">
        <div class="mt-1 h-3 w-3 rounded-full
            @if($leave->status === 'Approved')
                bg-green-500
            @elseif($leave->status === 'Rejected')
                bg-red-500
            @else
                bg-gray-400
            @endif
        "></div>
        <div>
            <div class="font-semibold text-gray-800">Persetujuan Final HRD</div>

            @if($leave->status === 'Approved')
                <div class="text-sm text-green-600">
                    Pengajuan cuti telah <span class="font-bold">disetujui</span> HRD.
                </div>
            @elseif($leave->status === 'Rejected')
                <div class="text-sm text-red-600">
                    Pengajuan cuti telah <span class="font-bold">ditolak</span>.
                </div>
            @else
                <div class="text-sm text-gray-600">
                    Menunggu keputusan HRD.
                </div>
            @endif

            @if($leave->catatan_penolakan && $leave->status === 'Rejected')
                <div class="text-sm text-gray-700 mt-1">
                    <span class="font-semibold">Catatan Penolakan:</span><br>
                    {{ $leave->catatan_penolakan }}
                </div>
            @endif
        </div>
    </div>

</div>
