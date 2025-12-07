@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-4xl mx-auto px-4 lg:px-0 py-8 space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl
                             bg-gradient-to-tr from-sky-600 via-indigo-500 to-emerald-500
                             text-[0.6rem] font-bold text-white tracking-[0.22em] uppercase shadow-md">
                    Profil
                </span>

                <div class="flex flex-col">
                    <span class="text-[0.7rem] tracking-[0.25em] text-slate-500 uppercase">
                        Edit Data Profil
                    </span>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-slate-900 tracking-[0.18em] uppercase">
                        Edit Profil
                    </h1>
                </div>
            </div>
            <p class="text-xs sm:text-sm text-slate-600 max-w-md">
                Perbarui informasi pribadi, foto profil, dan password akunmu.
            </p>
        </div>

        <a href="{{ route('profile.show') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-full
                  border border-slate-300 bg-white text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                  text-slate-700 hover:bg-slate-50 transition">
            ← Kembali
        </a>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs sm:text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs sm:text-sm text-rose-800">
            <p class="font-semibold mb-1">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM EDIT (PASTI POST) --}}
    <form action="{{ route('profile.update') }}"
          method="POST"
          enctype="multipart/form-data"
          class="grid grid-cols-1 md:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] gap-6 items-start">
        @csrf

        {{-- KIRI: DATA PROFIL --}}
        <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                    shadow-[0_18px_45px_rgba(15,23,42,0.15)] px-6 py-5 space-y-4">

            <h2 class="text-sm font-semibold text-slate-900 tracking-[0.18em] uppercase">
                Data Profil
            </h2>

            {{-- Nama Lengkap --}}
            <div class="space-y-1.5">
                <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                    Nama Lengkap
                </label>
                <input type="text"
                       name="nama_lengkap"
                       value="{{ old('nama_lengkap', $profile->nama_lengkap ?? $user->name) }}"
                       class="w-full text-sm rounded-2xl border border-slate-300 bg-slate-50/80
                              px-3 py-2.5 text-slate-800
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Nomor Telepon --}}
            <div class="space-y-1.5">
                <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                    Nomor Telepon
                </label>
                <input type="text"
                       name="nomor_telepon"
                       value="{{ old('nomor_telepon', $profile->nomor_telepon ?? '') }}"
                       class="w-full text-sm rounded-2xl border border-slate-300 bg-slate-50/80
                              px-3 py-2.5 text-slate-800
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Contoh: 08xxxxxxxxxx">
            </div>

            {{-- Alamat --}}
            <div class="space-y-1.5">
                <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-slate-500">
                    Alamat
                </label>
                <textarea name="alamat"
                          rows="3"
                          class="w-full text-sm rounded-2xl border border-slate-300 bg-slate-50/80
                                 px-3 py-2.5 text-slate-800 resize-y
                                 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Tulis alamat lengkap tempat tinggalmu">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
            </div>
        </div>

        {{-- KANAN: FOTO & PASSWORD --}}
        <div class="space-y-4">

            {{-- FOTO PROFIL --}}
            <div class="rounded-3xl bg-white/95 backdrop-blur border border-slate-200
                        shadow-[0_16px_40px_rgba(15,23,42,0.15)] px-6 py-5 space-y-4">
                <h2 class="text-sm font-semibold text-slate-900 tracking-[0.18em] uppercase">
                    Foto Profil
                </h2>

                <div class="flex items-center gap-4">
                   @if($profile->foto)
                        <img src="{{ asset('storage/' . $profile->foto) }}"
                            class="w-40 h-40 rounded-full object-cover shadow"
                            alt="Foto Profil">
                    @else
                        <img src="{{ asset('images/default-avatar.png') }}"
                            class="w-40 h-40 rounded-full object-cover shadow"
                            alt="Default Foto Profil">
                    @endif

                    <div class="space-y-1">
                        <input type="file" name="foto"
                               class="block w-full text-xs text-slate-700
                                      file:mr-3 file:py-1.5 file:px-3 file:rounded-full
                                      file:border-0 file:text-xs file:font-semibold
                                      file:bg-slate-900 file:text-white
                                      hover:file:bg-slate-800">
                        <p class="text-[0.68rem] text-slate-500">
                            Format: JPG, JPEG, PNG, maksimal 2MB.
                        </p>
                    </div>
                </div>
            </div>

            {{-- PASSWORD --}}
            <div class="rounded-3xl bg-slate-900 text-slate-50 px-6 py-5 space-y-4
                        shadow-[0_18px_45px_rgba(15,23,42,0.85)]">
                <h2 class="text-sm font-semibold tracking-[0.18em] uppercase text-slate-100">
                    Ubah Password (Opsional)
                </h2>
                <p class="text-[0.7rem] text-slate-300">
                    Kosongkan bagian ini jika kamu tidak ingin mengganti password.
                </p>

                <div class="space-y-1.5">
                    <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-slate-300">
                        Password Baru
                    </label>
                    <input type="password"
                           name="password"
                           class="w-full text-sm rounded-2xl border border-slate-600 bg-slate-900
                                  px-3 py-2.5 text-slate-50
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-[0.7rem] font-semibold tracking-[0.18em] uppercase text-slate-300">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password"
                           name="password_confirmation"
                           class="w-full text-sm rounded-2xl border border-slate-600 bg-slate-900
                                  px-3 py-2.5 text-slate-50
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-full
                                   bg-emerald-500 text-slate-950 text-[0.7rem] font-semibold tracking-[0.18em] uppercase
                                   shadow-[0_14px_35px_rgba(16,185,129,0.85)]
                                   hover:bg-emerald-400 hover:-translate-y-[1px] transition">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection
