@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')

<style>
    @keyframes fadeInUpSoft {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim-form { animation: fadeInUpSoft .4s ease-out forwards; }
</style>

<div class="max-w-4xl mx-auto mt-10 px-4 anim-form">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-[0_12px_32px_rgba(15,23,42,0.08)] p-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-semibold text-slate-900">
                    Edit Pengguna
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Ubah data akun dan profil karyawan.
                </p>
            </div>

            <a href="{{ route('admin.manajemen_user.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200
                      text-[0.75rem] font-semibold uppercase tracking-[0.16em] text-slate-700
                      hover:bg-slate-50 transition">
                ← Kembali
            </a>
        </div>

        {{-- FORM --}}
        <form action="{{ route('admin.manajemen_user.update', ['manajemen_user' => $user->id]) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Username & Email --}}
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                        Username
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                            focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">


                <div>
                    <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                </div>
            </div>

            {{-- Nama Lengkap --}}
            <div>
                <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                    Nama Lengkap
                </label>
                <input
                    type="text"
                    name="nama_lengkap"
                    value="{{ old('nama_lengkap', optional($user->profile)->nama_lengkap) }}"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                           focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
            </div>

            {{-- Role & Divisi --}}
            <div class="grid md:grid-cols-2 gap-4">
                {{-- ROLE --}}
                <div>
                    <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                        Role
                    </label>
                    <select
                        name="role"
                        id="role-select"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                        <option value="User"   {{ $user->role == 'User'   ? 'selected' : '' }}>User</option>
                        <option value="Leader" {{ $user->role == 'Leader' ? 'selected' : '' }}>Leader</option>
                    </select>
                </div>
                {{-- DIVISI (hanya untuk role User) --}}
                <div id="divisi-group">
                    <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                        Divisi (khusus Role User)
                    </label>
                    <select
                        name="divisi_id"
                        id="divisi-select"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                        <option value="">Belum ada divisi</option>
                        @foreach($divisions as $division)
                            <option
                                value="{{ $division->id }}"
                                {{ optional($user->profile)->divisi_id == $division->id ? 'selected' : '' }}>
                                {{ $division->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Status Aktif --}}
            <div>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox"
                           name="status_aktif"
                           value="1"
                           {{ optional($user->profile)->status_aktif ? 'checked' : '' }}
                           class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span class="text-xs text-slate-800">User aktif</span>
                </label>
            </div>

            {{-- Alamat & Telepon (opsional) --}}
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                        Alamat
                    </label>
                    <textarea
                        name="alamat"
                        rows="2"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">{{ old('alamat', optional($user->profile)->alamat) }}</textarea>
                </div>
                <div>
                    <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                        Nomor Telepon
                    </label>
                    <input
                        type="text"
                        name="nomor_telepon"
                        value="{{ old('nomor_telepon', optional($user->profile)->nomor_telepon) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                </div>
            </div>

            {{-- Tombol --}}
            <div class="pt-4 flex flex-col sm:flex-row gap-3">
                <button
                    type="submit"
                    class="inline-flex justify-center items-center px-6 py-2.5 rounded-xl
                           bg-sky-600 text-white text-[0.75rem] font-semibold tracking-[0.16em] uppercase
                           shadow-[0_10px_24px_rgba(37,99,235,0.45)]
                           hover:bg-sky-700 transition">
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.manajemen_user.index') }}"
                   class="inline-flex justify-center items-center px-6 py-2.5 rounded-xl
                          bg-slate-50 text-slate-700 text-[0.75rem] font-semibold tracking-[0.16em] uppercase
                          border border-slate-200 hover:bg-slate-100 transition">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect   = document.getElementById('role-select');
        const divisiGroup  = document.getElementById('divisi-group');
        const divisiSelect = document.getElementById('divisi-select');

        function toggleDivisi() {
            if (roleSelect.value === 'User') {
                divisiGroup.classList.remove('hidden');
                divisiSelect.disabled = false;
            } else {
                divisiSelect.value = '';
                divisiSelect.disabled = true;
                divisiGroup.classList.add('hidden');
            }
        }

        roleSelect.addEventListener('change', toggleDivisi);
        toggleDivisi();
    });
</script>

@endsection
