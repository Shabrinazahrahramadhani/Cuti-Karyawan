@extends('layouts.app')

@section('title', 'Riwayat Cuti')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-sm text-emerald-800">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-500 text-white text-xs font-bold">
                ✓
            </span>
            <span class="font-medium">
                {{ session('success') }}
            </span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-rose-50 border border-rose-200 rounded-2xl text-sm text-rose-700">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-rose-500 text-white text-xs font-bold">
                !
            </span>
            <span class="font-medium">
                {{ session('error') }}
            </span>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="flex items-center gap-3 text-2xl md:text-3xl font-semibold text-slate-900">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl
                             bg-gradient-to-tr from-sky-500 via-blue-500 to-indigo-500
                             text-[0.6rem] font-bold text-white tracking-[0.18em] uppercase">
                    CUTI
                </span>
                <span class="tracking-[0.12em] uppercase text-sm sm:text-base">
                    Riwayat Pengajuan Cuti
                </span>
            </h2>
            <p class="mt-2 text-xs text-slate-600">
                Lihat status pengajuan cuti kamu dan batalkan pengajuan yang masih pending.
            </p>
        </div>

        <a href="{{ route('user.leave.create') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-2xl
                  bg-sky-600 text-white text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                  shadow-sm hover:bg-sky-700 hover:-translate-y-0.5 transition">
            + Ajukan Cuti
        </a>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="overflow-x-auto bg-white rounded-3xl border border-slate-200 shadow-sm">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-500">No</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-500">Jenis</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-500">Tgl Pengajuan</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-500">Periode</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-500">Hari</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-500">Status</th>
                    <th class="px-4 py-3 text-left text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-500">Alasan</th>
                    <th class="px-4 py-3 text-center text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-slate-500">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($leaves as $leave)
                    @php
                        $rawStatus = $leave->status ?? 'Pending';
                        $status = strtolower($rawStatus);

                        $badgeClass = match($status) {
                            'pending'               => 'bg-amber-100 text-amber-800 ring-amber-200',
                            'approved by leader'    => 'bg-sky-100 text-sky-800 ring-sky-200',
                            'approved'              => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
                            'rejected'              => 'bg-rose-100 text-rose-800 ring-rose-200',
                            'cancelled'             => 'bg-slate-100 text-slate-700 ring-slate-200',
                            default                 => 'bg-slate-100 text-slate-700 ring-slate-200',
                        };
                    @endphp

                    <tr class="border-t border-slate-100 hover:bg-slate-50/60">
                        {{-- No --}}
                        <td class="px-4 py-3 align-top text-slate-900">
                            {{ $loop->iteration }}
                        </td>

                        {{-- Jenis Cuti --}}
                        <td class="px-4 py-3 align-top text-slate-900">
                            {{ ucfirst($leave->jenis_cuti ?? '-') }}
                        </td>

                        {{-- Tanggal Pengajuan --}}
                        <td class="px-4 py-3 align-top text-slate-900">
                            {{ $leave->tanggal_pengajuan ? \Carbon\Carbon::parse($leave->tanggal_pengajuan)->format('d M Y') : '-' }}
                        </td>

                        {{-- Periode --}}
                        <td class="px-4 py-3 align-top text-slate-900">
                            {{ \Carbon\Carbon::parse($leave->tanggal_mulai)->format('d M Y') }}
                            –
                            {{ \Carbon\Carbon::parse($leave->tanggal_selesai)->format('d M Y') }}
                        </td>

                        {{-- Hari --}}
                        <td class="px-4 py-3 align-top text-slate-900">
                            {{ $leave->total_hari }}
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3 align-top">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[0.7rem] font-semibold tracking-[0.12em] uppercase ring-1 {{ $badgeClass }}">
                                {{ $rawStatus }}
                            </span>
                        </td>

                        {{-- Alasan --}}
                        <td class="px-4 py-3 align-top text-slate-900">
                            @if(strtolower($leave->status) === 'cancelled' && !empty($leave->alasan_pembatalan))
                                <div class="space-y-1">
                                    <div>
                                        <span class="block text-[0.7rem] font-semibold text-slate-600">Alasan Pengajuan:</span>
                                        <span class="block text-sm">{{ $leave->alasan }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[0.7rem] font-semibold text-rose-600">Alasan Pembatalan:</span>
                                        <span class="block text-sm text-rose-700">{{ $leave->alasan_pembatalan }}</span>
                                    </div>
                                </div>
                            @else
                                {{ $leave->alasan }}
                            @endif
                        </td>

                        <td class="px-4 py-3 align-top text-center space-y-2">

                            {{-- (kalau mau, boleh tulis teks kecil info) --}}
                            <span class="block text-[0.7rem] text-slate-400">
                                Detail belum tersedia
                            </span>

                            {{-- Batalkan (hanya pending) --}}
                            @if($status === 'pending')
                                <button type="button"
                                        onclick="openCancelModal({{ $leave->id }})"
                                        class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl
                                            bg-rose-500 text-white text-[0.7rem] font-semibold tracking-[0.12em] uppercase
                                            shadow-sm hover:bg-rose-600 hover:-translate-y-0.5 transition">
                                    Batalkan
                                </button>
                            @else
                                <span class="block text-[0.7rem] text-slate-400 italic">
                                    Tidak bisa dibatalkan
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-sm text-slate-500">
                            Belum ada pengajuan cuti.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- MODAL PEMBATALAN CUTI --}}
<div id="cancelModal"
     class="fixed inset-0 bg-slate-900/40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-md w-full p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-1">
            Batalkan Pengajuan Cuti
        </h3>

        <p class="text-xs text-slate-600 mb-4">
            Alasan pembatalan <span class="font-semibold">wajib</span> diisi. Kuota cuti tahunan akan dikembalikan jika ini Cuti Tahunan.
        </p>

        <form id="cancelForm" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1">
                    Alasan Pembatalan
                </label>
                <textarea name="alasan_pembatalan"
                          id="alasanPembatalan"
                          rows="3"
                          class="w-full rounded-2xl border border-slate-300 px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-sky-300 focus:border-sky-400"
                          placeholder="Contoh: jadwal berubah, ada kebutuhan mendesak, dsb"
                          required></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button"
                        onclick="closeCancelModal()"
                        class="px-4 py-2 rounded-xl bg-slate-100 text-slate-800 text-xs font-semibold tracking-[0.14em] uppercase
                               hover:bg-slate-200 transition">
                    Batal
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-rose-600 text-white text-xs font-semibold tracking-[0.14em] uppercase
                               hover:bg-rose-700 transition">
                    Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCancelModal(id) {
        let modal = document.getElementById('cancelModal');
        let form  = document.getElementById('cancelForm');

        form.action = "{{ route('leave.cancel', ':id') }}".replace(':id', id);
        document.getElementById('alasanPembatalan').value = "";

        modal.classList.remove('hidden');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }
</script>
@endsection
