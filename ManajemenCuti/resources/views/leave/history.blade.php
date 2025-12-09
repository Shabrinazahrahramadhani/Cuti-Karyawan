@extends('layouts.app')

@section('title', 'Riwayat Cuti')

@section('content')
@php
    $user = auth()->user();
    $role = $user->role ?? 'User';

    // route untuk tombol Ajukan Cuti disesuaikan per role
    $createRoute = $role === 'Leader' ? 'leader.leave.create' : 'user.leave.create';
@endphp

<div class="max-w-6xl mx-auto mt-10 px-4 space-y-6">

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-2 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-sm text-emerald-800">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-500 text-white text-xs font-bold">
                ✓
            </span>
            <span class="font-medium">
                {{ session('success') }}
            </span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-2 flex items-center gap-3 px-4 py-3 bg-rose-50 border border-rose-200 rounded-2xl text-sm text-rose-700">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-rose-500 text-white text-xs font-bold">
                !
            </span>
            <span class="font-medium">
                {{ session('error') }}
            </span>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
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
                Lihat status pengajuan cuti kamu, cek alamat selama cuti, dan batalkan pengajuan yang masih pending.
            </p>
        </div>

        <a href="{{ route($createRoute) }}"
           class="inline-flex items-center justify-center px-5 py-2.5 rounded-2xl
                  bg-sky-600 text-white text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                  shadow-[0_8px_20px_rgba(37,99,235,0.45)] hover:bg-sky-500 hover:-translate-y-0.5 transition">
            + Ajukan Cuti
        </a>
    </div>

    {{-- List Riwayat (kartu-kartu) --}}
    @if($leaves->isEmpty())
        <div class="mt-6 text-center text-sm text-slate-500 py-10 bg-white/80 rounded-3xl border border-slate-200">
            Belum ada pengajuan cuti.
        </div>
    @else
        <div class="space-y-4">
            @foreach ($leaves as $leave)
                @php
                    $rawStatus = $leave->status ?? 'Pending';
                    $status    = strtolower($rawStatus);

                    $badgeClass = match($status) {
                        'pending'               => 'bg-amber-100 text-amber-800 ring-amber-200',
                        'approved by leader'    => 'bg-sky-100 text-sky-800 ring-sky-200',
                        'approved'              => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
                        'rejected'              => 'bg-rose-100 text-rose-800 ring-rose-200',
                        'cancelled'             => 'bg-slate-100 text-slate-700 ring-slate-200',
                        default                 => 'bg-slate-100 text-slate-700 ring-slate-200',
                    };

                    $alamatCuti = $leave->alamat_selama_cuti ?? '-';
                    $nomorDarurat = $leave->nomor_darurat ?? '-';
                @endphp

                <article
                    class="rounded-3xl bg-white border border-slate-200 overflow-hidden
                           shadow-[0_18px_40px_rgba(15,23,42,0.08)]">

                    {{-- top accent bar --}}
                    <div class="h-1.5 bg-gradient-to-r from-sky-500 via-blue-500 to-indigo-500"></div>

                    <div class="px-5 sm:px-7 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        {{-- Info utama --}}
                        <div class="space-y-2">
                            <div class="flex items-center gap-3">
                                <h3 class="font-semibold text-slate-900 tracking-[0.18em] uppercase text-xs sm:text-sm">
                                    {{ ucfirst($leave->jenis_cuti ?? 'Cuti') }}
                                </h3>

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[0.7rem] font-semibold tracking-[0.12em] uppercase ring-1 {{ $badgeClass }}">
                                    {{ $rawStatus }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-600">
                                Diajukan pada:
                                <span class="font-semibold text-slate-800">
                                    {{ $leave->tanggal_pengajuan ? \Carbon\Carbon::parse($leave->tanggal_pengajuan)->format('d M Y') : '-' }}
                                </span>
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1 text-xs text-slate-600 mt-1">
                                <p>
                                    <span class="text-slate-500">Periode:</span>
                                    <span class="font-semibold">
                                        {{ \Carbon\Carbon::parse($leave->tanggal_mulai)->format('d M Y') }}
                                        –
                                        {{ \Carbon\Carbon::parse($leave->tanggal_selesai)->format('d M Y') }}
                                        ({{ $leave->total_hari }} hari)
                                    </span>
                                </p>

                                <p>
                                    <span class="text-slate-500">Kontak Darurat:</span>
                                    <span class="font-semibold">
                                        {{ $nomorDarurat }}
                                    </span>
                                </p>
                            </div>

                            {{-- Alamat Selama Cuti --}}
                            <p class="text-xs text-slate-600 mt-1">
                                <span class="text-slate-500">Alamat selama cuti:</span>
                                <span class="font-medium text-slate-800">
                                    {{ $alamatCuti }}
                                </span>
                            </p>

                            {{-- Alasan & Alasan Pembatalan jika ada --}}
                            <div class="mt-2 text-xs text-slate-700 space-y-1">
                                @if(strtolower($leave->status) === 'cancelled' && !empty($leave->alasan_pembatalan))
                                    <div>
                                        <span class="block text-[0.7rem] font-semibold text-slate-600">Alasan Pengajuan:</span>
                                        <span class="block text-sm text-slate-800">{{ $leave->alasan }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[0.7rem] font-semibold text-rose-600">Alasan Pembatalan:</span>
                                        <span class="block text-sm text-rose-700">{{ $leave->alasan_pembatalan }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-500">Alasan:</span>
                                    <span class="font-medium text-slate-800">
                                        {{ $leave->alasan }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col sm:items-end gap-2">

                            {{-- Tombol Detail --}}
                            <a href="{{ route('leave.show', $leave->id) }}"
                               class="inline-flex items-center justify-center px-4 py-1.5 rounded-full
                                      bg-white text-slate-900 text-[0.7rem] font-semibold tracking-[0.12em] uppercase
                                      border border-slate-300 shadow-sm hover:bg-slate-50 transition">
                                Detail
                            </a>

                            {{-- Batalkan (hanya pending) --}}
                            @if($status === 'pending')
                                <button type="button"
                                        onclick="openCancelModal({{ $leave->id }})"
                                        class="inline-flex items-center justify-center px-4 py-1.5 rounded-full
                                            bg-rose-600 text-white text-[0.7rem] font-semibold tracking-[0.12em] uppercase
                                            shadow-[0_8px_20px_rgba(225,29,72,0.45)]
                                            hover:bg-rose-500 hover:-translate-y-0.5 transition">
                                    Batalkan
                                </button>
                            @else
                                <span class="block text-[0.7rem] text-slate-400 italic">
                                    Tidak bisa dibatalkan
                                </span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Pagination (kalau pakai paginate) --}}
        @if(method_exists($leaves, 'links'))
            <div class="mt-6">
                {{ $leaves->links() }}
            </div>
        @endif
    @endif

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
