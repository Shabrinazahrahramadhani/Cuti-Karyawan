@extends('layouts.app')

@section('title', 'Edit Divisi')

@section('content')

<style>
    @keyframes fadeInUpSoft {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim-form { animation: fadeInUpSoft .4s ease-out forwards; }
</style>

<div class="max-w-3xl mx-auto mt-10 px-4 anim-form">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-[0_12px_32px_rgba(15,23,42,0.08)] p-8">

        <h2 class="text-2xl md:text-3xl font-semibold text-slate-900 mb-6 flex items-center gap-3">
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-full
                         bg-sky-100 text-[0.6rem] font-bold text-slate-900 tracking-[0.18em] uppercase">
                DIV
            </span>
            <span class="tracking-[0.14em] uppercase text-sm text-slate-700">
                Edit Divisi
            </span>
        </h2>

        <form action="{{ route('admin.division.update', $division->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Divisi --}}
            <div>
                <label for="nama_divisi"
                       class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                    Nama Divisi
                </label>
                <input
                    type="text"
                    name="nama_divisi"
                    id="nama_divisi"
                    value="{{ old('nama_divisi', $division->nama_divisi) }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                           focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
            </div>

            <div>
                <label for="deskripsi"
                       class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                    Deskripsi
                </label>
                <textarea
                    name="deskripsi"
                    id="deskripsi"
                    rows="4"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                           focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">{{ old('deskripsi', $division->deskripsi) }}</textarea>
            </div>

            <div>
                <label for="ketua_divisi_id"
                    class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                    Leader
                </label>
                <select
                    name="ketua_divisi_id"
                    id="ketua_divisi_id"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm bg-white
                        focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                    <option value="">Pilih Leader</option>
                    @foreach ($leaders as $user)
                        <option
                            value="{{ $user->id }}"
                            {{ $division->ketua_divisi_id == $user->id ? 'selected' : '' }}>
                            {{ $user->profile->nama_lengkap ?? $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-3 flex gap-3">
                <button
                    type="submit"
                    class="px-6 py-2.5 rounded-xl bg-sky-600 text-white text-[0.75rem] font-semibold
                           tracking-[0.16em] uppercase shadow-[0_10px_24px_rgba(37,99,235,0.45)]
                           hover:bg-sky-700 transition">
                    Update Divisi
                </button>

                <a href="{{ route('admin.division.index') }}"
                   class="px-6 py-2.5 rounded-xl bg-slate-50 text-slate-700 text-[0.75rem] font-semibold
                          tracking-[0.16em] uppercase border border-slate-200 hover:bg-slate-100 transition">
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>

@endsection
