@extends('layouts.app')

@section('title', 'Profile Akun')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-6">

        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            Profile Akun & Cuti
        </h1>

        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            {{-- INFO AKUN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap"
                           value="{{ old('nama_lengkap', $profile->nama_lengkap ?? $user->name) }}"
                           class="w-full border rounded px-3 py-2 text-sm @error('nama_lengkap') border-red-500 @enderror">
                    @error('nama_lengkap')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email"
                           value="{{ old('email', $user->email) }}"
                           class="w-full border rounded px-3 py-2 text-sm @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- INFO KONTAK --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="text" name="nomor_telepon"
                           value="{{ old('nomor_telepon', $profile->nomor_telepon ?? '') }}"
                           class="w-full border rounded px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
                    <input type="text" name="alamat"
                           value="{{ old('alamat', $profile->alamat ?? '') }}"
                           class="w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>

            {{-- INFO DIVISI & ROLE --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Role</p>
                    <p class="text-sm text-gray-800">
                        {{ $user->role ?? 'User' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Divisi Saat Ini</p>
                    <p class="text-sm text-gray-800">
                        {{ $currentDivision->nama_divisi ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- PILIH DIVISI & LIHAT KETUA DIVISI (UNTUK KARYAWAN) --}}
            @if($user->role === 'User')
                <div class="mt-2 mb-6 border-t pt-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">
                        Divisi & Atasan Langsung
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Dropdown Divisi --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Divisi</label>
                            <select name="divisi_id"
                                    class="w-full border rounded px-3 py-2 text-sm @error('divisi_id') border-red-500 @enderror">
                                <option value="">-- Pilih Divisi --</option>
                                @foreach($divisions as $div)
                                    <option value="{{ $div->id }}"
                                        {{ optional($profile)->divisi_id == $div->id ? 'selected' : '' }}>
                                        {{ $div->nama_divisi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('divisi_id')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Info Ketua Divisi --}}
                        <div>
                            <p class="block text-sm font-semibold text-gray-700 mb-1">Ketua Divisi</p>
                            @php
                                 if (!$currentDivision && optional($profile)->divisi_id) {
                                    $currentDivision = $divisions->firstWhere('id', $profile->divisi_id);
                                }
                            @endphp
                           <p class="text-sm text-gray-800">
                                @if($currentDivision && $currentDivision->leader)
                                    {{ $currentDivision->leader->name }}
                                @else
                                    -
                                @endif
                            </p>
                                <p class="text-xs text-gray-500 mt-1">
                                Ketua divisi diatur oleh Admin/HRD. Kamu hanya memilih divisinya.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- INFO KUOTA CUTI --}}
            <div class="mb-6 p-4 rounded-lg bg-yellow-50 border border-yellow-200">
                @php
                    $kuota = $profile->kuota_cuti ?? 0;
                @endphp
                <p class="text-sm font-semibold text-gray-800">
                    Kuota Cuti Tahunan: {{ $kuota }} hari
                </p>
                <p class="text-xs text-gray-600 mt-1">
                    Kuota awal: 12 hari kerja per tahun. Cuti sakit tidak mengurangi kuota tahunan.
                </p>
            </div>

            {{-- TOMBOL SIMPAN --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="px-5 py-2 rounded bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
