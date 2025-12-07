@extends('layouts.app')

@section('title', 'Tambah Divisi')

@section('content')

<style>
    @keyframes fadeInUpSoft {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim-form { animation: fadeInUpSoft .4s ease-out forwards; }
</style>

<div class="max-w-3xl mx-auto mt-10 px-4 anim-form">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-[0_12px_32px_rgba(15,23,42,0.08)] p-8 relative overflow-hidden">

        {{-- dekorasi --}}
        <div class="pointer-events-none absolute -top-16 -right-8 w-40 h-40 bg-sky-100 rounded-full blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 -left-10 w-48 h-48 bg-blue-50 rounded-full blur-3xl"></div>

        <div class="relative">
            <h2 class="text-2xl md:text-3xl font-semibold text-slate-900 mb-6 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full
                             bg-sky-100 text-[0.6rem] font-bold text-slate-900 tracking-[0.18em] uppercase">
                    DIV
                </span>
                <span class="tracking-[0.14em] uppercase text-sm text-slate-700">
                    Tambah Divisi Baru
                </span>
            </h2>

            <form action="{{ route('admin.division.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Nama Divisi --}}
                <div>
                    <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                        Nama Divisi
                    </label>
                    <input
                        type="text"
                        name="nama_divisi"
                        value="{{ old('nama_divisi') }}"
                        required
                        placeholder="Contoh: Human Resource, Finance, IT Support"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400
                               @error('nama_divisi') border-rose-400 ring-rose-200 @enderror">
                    @error('nama_divisi')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                        Deskripsi
                    </label>
                    <textarea
                        name="deskripsi"
                        rows="4"
                        placeholder="Tuliskan deskripsi singkat mengenai peran dan tanggung jawab divisi"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Leader --}}
                <div>
                    <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                        Leader
                    </label>

                    <select
                        name="ketua_divisi_id"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">

                        <option value=""> Pilih Leader </option>

                        @foreach ($leaders as $leader)
                            <option
                                value="{{ $leader->id }}"
                                {{ old('ketua_divisi_id') == $leader->id ? 'selected' : '' }}>
                                {{ $leader->profile->nama_lengkap ?? $leader->name }}
                            </option>
                        @endforeach

                        @if($leaders->count() == 0)
                            <option disabled>Belum ada Leader tersedia</option>
                        @endif
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-3">
                    <button
                        type="submit"
                        class="inline-flex justify-center items-center px-6 py-2.5 rounded-xl
                               bg-sky-600 text-white text-[0.75rem] font-semibold tracking-[0.16em] uppercase
                               shadow-[0_10px_24px_rgba(37,99,235,0.45)]
                               hover:bg-sky-700 transition">
                        Simpan Divisi
                    </button>

                    <a href="{{ route('admin.division.index') }}"
                       class="inline-flex justify-center items-center px-6 py-2.5 rounded-xl
                              bg-slate-50 text-slate-700 text-[0.75rem] font-semibold tracking-[0.16em] uppercase
                              border border-slate-200 hover:bg-slate-100 transition">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
