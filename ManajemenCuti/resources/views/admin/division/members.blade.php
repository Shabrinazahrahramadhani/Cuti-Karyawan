@extends('layouts.app')

@section('title', 'Manajemen Anggota Divisi')

@section('content')

<style>
    @keyframes fadeInUpSoft {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim-page  { animation: fadeInUpSoft .4s ease-out forwards; }
</style>

<div class="max-w-6xl mx-auto mt-10 px-4 anim-page">

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 px-5 py-3 bg-emerald-50 border border-emerald-200 
                    text-emerald-800 rounded-xl text-xs shadow-sm">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-500 text-slate-100 text-xs font-bold">
                ✓
            </span>
            <span class="font-semibold text-slate-700">
                {{ session('success') }}
            </span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 px-5 py-3 bg-rose-50 border border-rose-200 
                    text-rose-700 rounded-xl text-xs shadow-sm">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-rose-500 text-slate-100 text-xs font-bold">
                !
            </span>
            <span class="font-semibold text-slate-700">
                {{ session('error') }}
            </span>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="flex items-center gap-3 text-xl sm:text-2xl md:text-3xl font-semibold text-slate-800">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full
                             bg-sky-100 text-[0.6rem] font-bold text-slate-800 tracking-[0.18em] uppercase">
                    ADMIN
                </span>
                <span class="tracking-[0.14em] uppercase text-sm sm:text-base text-slate-700">
                    Anggota Divisi – {{ $division->nama_divisi }}
                </span>
            </h2>
            <p class="mt-2 text-xs text-slate-500">
                Kelola anggota karyawan untuk divisi ini. Penghapusan anggota tidak menghapus akun pengguna.
            </p>
        </div>

        <a href="{{ route('admin.division.index') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-full
                  bg-white text-slate-700 text-[0.7rem] font-semibold tracking-[0.16em] uppercase
                  border border-slate-200 shadow-sm hover:bg-slate-50 transition">
            ← Kembali ke Data Divisi
        </a>
    </div>

    {{-- Info Divisi + Form --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        {{-- Info --}}
        <div class="lg:col-span-1 bg-white border border-slate-200 rounded-2xl p-4 shadow-[0_10px_26px_rgba(15,23,42,0.06)]">
            <h3 class="text-sm font-semibold tracking-[0.16em] uppercase text-slate-700 mb-2">
                Info Divisi
            </h3>
            <p class="text-xs text-slate-700">
                <span class="font-semibold">Nama Divisi:</span>
                {{ $division->nama_divisi }}
            </p>
            <p class="text-xs text-slate-700 mt-1">
                <span class="font-semibold">Ketua Divisi:</span>
                {{ optional($division->ketuaDivisi)->name ?? '-' }}
            </p>
            <p class="text-xs text-slate-700 mt-1">
                <span class="font-semibold">Jumlah Anggota:</span>
                {{ $division->members_count }}
            </p>

            @if($division->deskripsi)
                <p class="text-xs text-slate-700 mt-2">
                    <span class="font-semibold">Deskripsi:</span><br>
                    {{ $division->deskripsi }}
                </p>
            @endif
        </div>

        {{-- Form Tambah --}}
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-4 shadow-[0_10px_26px_rgba(15,23,42,0.06)]">
            <h3 class="text-sm font-semibold tracking-[0.16em] uppercase text-slate-700 mb-3">
                Tambah Anggota
            </h3>

            @if($availableEmployees->isEmpty())
                <p class="text-xs text-slate-500">
                    Semua karyawan sudah memiliki divisi, atau belum ada karyawan dengan role <strong>User</strong>.
                </p>
            @else
                <form method="POST" action="{{ route('admin.division.members.add', $division->id) }}"
                      class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-end">
                    @csrf

                    <div class="flex-1">
                        <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1">
                            Pilih Karyawan
                        </label>
                        <select name="user_id"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs bg-white text-slate-700
                                       focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400"
                                required>
                            <option value="" class="text-slate-500">-- Pilih Karyawan --</option>
                            @foreach($availableEmployees as $emp)
                                <option value="{{ $emp->id }}" class="text-slate-700">
                                    {{ $emp->name }} ({{ $emp->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <button type="submit"
                                class="px-4 py-2 rounded-xl bg-sky-600 text-slate-100 text-[0.7rem] font-semibold
                                       tracking-[0.18em] uppercase shadow-[0_10px_24px_rgba(37,99,235,0.45)]
                                       hover:bg-sky-700 transition">
                            + Tambah
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    {{-- TABEL --}}
    <div class="bg-white shadow-[0_12px_32px_rgba(15,23,42,0.08)] rounded-2xl border border-slate-200 overflow-x-auto">
        <table class="min-w-full text-xs">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold tracking-[0.16em] uppercase text-[0.7rem]">No</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold tracking-[0.16em] uppercase text-[0.7rem]">Nama</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold tracking-[0.16em] uppercase text-[0.7rem]">Email</th>
                    <th class="px-4 py-3 text-left text-slate-600 font-semibold tracking-[0.16em] uppercase text-[0.7rem]">Role</th>
                    <th class="px-4 py-3 text-center text-slate-600 font-semibold tracking-[0.16em] uppercase text-[0.7rem]">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($division->members as $member)
                    @php $user = $member->user; @endphp

                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 text-slate-600">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-4 py-3 text-slate-700 font-semibold">
                            {{ $user->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-slate-700">
                            {{ $user->email ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-slate-700">
                            {{ $user->role ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.division.members.remove', [$division->id, $user->id]) }}"
                                  method="POST"
                                  onsubmit="return confirm('Keluarkan {{ $user->name }} dari divisi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-rose-100 text-rose-700 py-1.5 px-3 rounded-lg text-[0.7rem] font-semibold
                                               tracking-[0.12em] uppercase border border-rose-300 hover:bg-rose-200 transition">
                                    Keluarkan
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-5 text-center text-slate-500">
                            Belum ada anggota di divisi ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
