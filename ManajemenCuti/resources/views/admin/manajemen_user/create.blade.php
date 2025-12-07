@extends('layouts.app')

@section('title', 'Buat Pengguna Baru')

@section('content')

<style>
    @keyframes fadeInUpSoft {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim-form { animation: fadeInUpSoft .35s ease-out forwards; }
</style>

<div class="max-w-4xl mx-auto mt-10 px-4 anim-form">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-[0_14px_40px_rgba(15,23,42,0.08)] p-8 relative overflow-hidden">

        {{-- dekorasi halus --}}
        <div class="pointer-events-none absolute -top-16 -right-8 w-40 h-40 bg-sky-100 rounded-full blur-3xl opacity-70"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-10 w-52 h-52 bg-blue-50 rounded-full blur-3xl opacity-80"></div>

        <div class="relative">
            {{-- Header --}}
            <div class="flex justify-between items-start gap-4 mb-6">
                <div>
                    <h2 class="text-2xl md:text-3xl font-semibold text-slate-900 flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full
                                     bg-sky-100 text-[0.6rem] font-bold text-slate-900 tracking-[0.18em] uppercase">
                            USER
                        </span>
                        <span class="tracking-[0.16em] uppercase text-sm text-slate-700">
                            New Create User
                        </span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Lengkapi data akun karyawan, termasuk role, divisi, dan kuota cuti awal.
                    </p>
                </div>

                <a href="{{ route('admin.manajemen_user.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200
                          text-[0.75rem] font-semibold uppercase tracking-[0.16em] text-slate-700
                          hover:bg-slate-50 transition">
                    Kembali
                </a>
            </div>

            {{-- FORM --}}
            <form action="{{ route('admin.manajemen_user.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-5">
                @csrf

                {{-- Nama Pengguna (disimpan ke kolom users.name) & Nama Lengkap --}}
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                            Nama Pengguna
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                        @error('name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                            Nama Lengkap
                        </label>
                        <input
                            type="text"
                            name="nama_lengkap"
                            value="{{ old('nama_lengkap') }}"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                        @error('nama_lengkap')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                    @error('email')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password & Konfirmasi --}}
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                            Kata Sandi
                        </label>
                        <input
                            type="password"
                            name="password"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                        @error('password')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                            Konfirmasi Kata Sandi
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                    </div>
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
                            <option value="">Pilih role</option>
                            <option value="User"   {{ old('role') === 'User'   ? 'selected' : '' }}>User</option>
                            <option value="Leader" {{ old('role') === 'Leader' ? 'selected' : '' }}>Ketua Divisi</option>
                        </select>
                        @error('role')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
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
                                <option value="{{ $division->id }}" {{ old('divisi_id') == $division->id ? 'selected' : '' }}>
                                    {{ $division->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                        @error('divisi_id')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Kuota Cuti Tahunan (info saja, fix 12 hari) --}}
                <div>
                    <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                        Kuota Cuti Tahunan
                    </label>
                    <input
                        type="text"
                        value="12 hari kerja per tahun"
                        disabled
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700">
                    <p class="text-[0.7rem] text-slate-500 mt-1">
                        Kuota cuti tahunan ditetapkan <strong>12 hari</strong> untuk semua karyawan.
                    </p>
                </div>

                {{-- Status Aktif --}}
                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox"
                               name="status_aktif"
                               value="1"
                               {{ old('status_aktif', true) ? 'checked' : '' }}
                               class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                        <span class="text-xs text-slate-800">User aktif</span>
                    </label>
                </div>

                {{-- Alamat & Telepon --}}
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                            Alamat
                        </label>
                        <textarea
                            name="alamat"
                            rows="2"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">{{ old('alamat') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                            Nomor Telepon
                        </label>
                        <input
                            type="text"
                            name="nomor_telepon"
                            value="{{ old('nomor_telepon') }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                    </div>
                </div>

                {{-- Foto Profil (opsional) --}}
                <div>
                    <label class="block text-[0.75rem] font-semibold text-slate-700 mb-1 tracking-[0.14em] uppercase">
                        Foto Profil (opsional)
                    </label>
                    <input type="file" name="foto"
                           class="block w-full text-sm text-slate-600
                                  file:mr-3 file:py-2.5 file:px-4
                                  file:rounded-xl file:border-0
                                  file:text-xs file:font-semibold
                                  file:bg-sky-50 file:text-sky-700
                                  hover:file:bg-sky-100">
                    <p class="text-[0.7rem] text-slate-400 mt-1">
                        Maksimal 2MB. Format: JPG, PNG, dll.
                    </p>
                </div>

                {{-- Tombol --}}
                <div class="pt-4 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('admin.manajemen_user.index') }}"
                       class="px-6 py-2.5 rounded-xl bg-slate-50 text-slate-700 text-[0.75rem] font-semibold
                              tracking-[0.16em] uppercase border border-slate-200 hover:bg-slate-100 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-sky-600 text-white text-[0.75rem] font-semibold
                                   tracking-[0.16em] uppercase shadow-[0_10px_24px_rgba(37,99,235,0.45)]
                                   hover:bg-sky-700 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
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
