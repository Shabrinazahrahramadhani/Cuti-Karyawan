<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cuti Karyawan')</title>

    <!-- Google Font: Space Grotesk -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        brutal: ['Space Grotesk', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            900: '#020617',
                            800: '#02081a',
                            700: '#0b1727',
                        },
                        brand: {
                            primary: '#1d4ed8',
                            soft: '#e0f2fe',
                            accent: '#38bdf8',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Styling form & card versi kantor biru gelap -->
    <style>
        body {
            font-family: "Space Grotesk", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        /* CARD / FORM CONTAINER */
        .max-w-3xl.mx-auto.mt-10 > .bg-white.shadow-xl.rounded-2xl.p-10.border.border-gray-100,
        .max-w-2xl.mx-auto.bg-white.shadow-lg.rounded-lg.p-8.mt-10 {
            background: #0b1120;              /* very dark navy */
            border-radius: 1.25rem;
            border: 1px solid #1f2937;        /* subtle border */
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.45);
        }

        /* TITLE */
        .max-w-3xl h2.text-3xl.font-bold.text-gray-800.mb-8.border-l-8.border-blue-600.pl-4,
        .max-w-2xl h2.text-2xl.font-bold.mb-6.text-gray-800 {
            border-left-width: 0;
            padding-left: 0;
            margin-bottom: 2.25rem;
            font-size: 1.9rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #e5e7eb;
        }

        .max-w-3xl h2.text-3xl.font-bold.text-gray-800.mb-8.border-l-8.border-blue-600.pl-4::before,
        .max-w-2xl h2.text-2xl.font-bold.mb-6.text-gray-800::before {
            content: "";
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-right: 10px;
            border-radius: 9999px;
            background: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.35);
            vertical-align: middle;
        }

        /* LABEL */
        .max-w-3xl label.block.font-semibold.text-gray-700.mb-1,
        .max-w-2xl label.block.text-gray-700.font-medium.mb-1 {
            color: #9ca3af;
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.14em;
        }

        /* INPUT / SELECT / TEXTAREA */
        .max-w-3xl input[type="text"],
        .max-w-3xl input[type="email"],
        .max-w-3xl input[type="password"],
        .max-w-3xl textarea,
        .max-w-3xl select,
        .max-w-2xl input[type="text"],
        .max-w-2xl input[type="email"],
        .max-w-2xl input[type="password"],
        .max-w-2xl textarea,
        .max-w-2xl select {
            background: #020617;
            border-radius: 0.75rem;
            border: 1px solid #1f2937;
            padding: 0.7rem 0.9rem;
            font-size: 0.9rem;
            color: #e5e7eb;
            box-shadow: 0 0 0 1px rgba(148, 163, 184, 0.2);
            outline: none;
            transition: transform 0.14s ease, box-shadow 0.14s ease, background-color 0.14s ease, border-color 0.14s ease;
        }

        .max-w-3xl textarea,
        .max-w-2xl textarea {
            min-height: 110px;
        }

        .max-w-3xl input::placeholder,
        .max-w-3xl textarea::placeholder,
        .max-w-2xl input::placeholder,
        .max-w-2xl textarea::placeholder {
            color: #6b7280;
        }

        .max-w-3xl input[type="text"]:focus,
        .max-w-3xl input[type="email"]:focus,
        .max-w-3xl input[type="password"]:focus,
        .max-w-3xl textarea:focus,
        .max-w-3xl select:focus,
        .max-w-2xl input[type="text"]:focus,
        .max-w-2xl input[type="email"]:focus,
        .max-w-2xl input[type="password"]:focus,
        .max-w-2xl textarea:focus,
        .max-w-2xl select:focus {
            background: #02081a;
            border-color: #38bdf8;
            transform: translateY(-1px);
            box-shadow: 0 0 0 1px rgba(56, 189, 248, 0.8), 0 18px 30px rgba(15, 23, 42, 0.6);
        }

        /* ERROR TEXT */
        .max-w-3xl p.text-red-600.text-sm.mt-1,
        .max-w-2xl p.text-red-600.text-sm.mt-1 {
            color: #fca5a5;
            font-weight: 600;
        }

        /* PRIMARY BUTTON */
        .max-w-3xl button.bg-green-600,
        .max-w-2xl button.bg-blue-600 {
            background: linear-gradient(135deg, #1d4ed8, #38bdf8);
            border-radius: 0.75rem;
            border: 0;
            box-shadow: 0 16px 30px rgba(37, 99, 235, 0.40);
            text-transform: uppercase;
            letter-spacing: 0.16em;
            font-size: 0.78rem;
        }

        .max-w-3xl button.bg-green-600:hover,
        .max-w-2xl button.bg-blue-600:hover {
            background: linear-gradient(135deg, #2563eb, #22d3ee);
            transform: translateY(-1px);
            box-shadow: 0 22px 40px rgba(37, 99, 235, 0.55);
        }

        /* SECONDARY BUTTON (BACK) */
        .max-w-3xl a.bg-gray-200 {
            background: transparent;
            color: #e5e7eb;
            border-radius: 0.75rem;
            border: 1px solid #4b5563;
            box-shadow: none;
            font-weight: 500;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
        }

        .max-w-3xl a.bg-gray-200:hover {
            background: rgba(148, 163, 184, 0.08);
            transform: translateY(-1px);
            box-shadow: 0 12px 25px rgba(15, 23, 42, 0.45);
        }

        .max-w-3xl.mx-auto.mt-10,
        .max-w-2xl.mx-auto.mt-10 {
            margin-top: 3.5rem;
        }
    </style>
</head>

<body class="font-brutal bg-slate-100 min-h-screen text-slate-900">

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
    }

    // === Dropdown user di topbar ===
    function toggleUserMenu() {
        const menu = document.getElementById('userMenuDropdown');
        if (!menu) return;
        menu.classList.toggle('hidden');
    }

    document.addEventListener('click', function (e) {
        const wrapper = document.getElementById('userMenuWrapper');
        const menu = document.getElementById('userMenuDropdown');
        if (!wrapper || !menu) return;

        // kalau klik di luar wrapper -> close
        if (!wrapper.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
</script>

<div class="flex">

{{-- ======================= SIDEBAR ======================= --}}
<aside id="sidebar"
       class="fixed lg:static z-50 w-64 min-h-screen 
              bg-gradient-to-b from-slate-900 via-slate-900 to-slate-800
              border-r border-slate-800
              transform -translate-x-full lg:translate-x-0 transition-all duration-300">

    {{-- Header Sidebar --}}
    <div class="p-6 flex justify-between items-center border-b border-slate-800">
        <div>
            <h1 class="text-xl font-semibold text-white tracking-[0.22em] uppercase">
                Cuti
            </h1>
            <p class="text-[0.7rem] text-slate-400 mt-1">
                Manajemen Cuti &amp; Approval
            </p>
        </div>
        <button onclick="toggleSidebar()"
                class="lg:hidden px-3 py-2 rounded-lg
                       bg-slate-800 text-slate-100 border border-slate-600
                       shadow-sm text-sm">
            ☰
        </button>
    </div>

    {{-- NAV --}}
    <nav class="px-4 py-5 space-y-4 text-sm font-medium">

        {{-- ========== MENU ADMIN ========== --}}
        @if(auth()->check() && Auth::user()->role == 'Admin')
            <p class="px-2 text-[0.65rem] tracking-[0.2em] uppercase text-slate-400">
                Admin 
            </p>

            <nav class="space-y-3">

                    {{-- DASHBOARD --}}
                    <a href="{{ route('admin.dashboard') }}"
                    class="block px-4 py-2.5 rounded-2xl text-sm font-semibold
                            {{ request()->routeIs('admin.dashboard') 
                                    ? 'bg-white text-slate-900 shadow-lg' 
                                    : 'bg-slate-900/40 text-slate-100 hover:bg-slate-800/80' }}">
                        Dashboard
                    </a>

                    {{-- dst untuk menu lain --}}
                </nav>


            <a href="{{ route('admin.division.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('admin.division.*')
                            ? 'bg-gradient-to-r from-sky-500 to-sky-600 text-white border-sky-500 shadow-[0_8px_18px_rgba(56,189,248,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('admin.division.*') ? 'bg-white/80' : 'bg-sky-400/70' }}"></span>
                <span>Manajemen Divisi</span>
            </a>

            <a href="{{ route('admin.manajemen_user.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('admin.manajemen_user.*')
                            ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white border-indigo-500 shadow-[0_8px_18px_rgba(79,70,229,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('admin.manajemen_user.*') ? 'bg-white/80' : 'bg-indigo-400/70' }}"></span>
                <span>Manajemen Pengguna</span>
            </a>

            <a href="{{ route('admin.cuti.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('admin.cuti.*')
                            ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white border-emerald-500 shadow-[0_8px_18px_rgba(16,185,129,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('admin.cuti.*') ? 'bg-white/80' : 'bg-emerald-400/70' }}"></span>
                <span>Manajemen Cuti</span>
            </a>

            <a href="{{ route('admin.laporan_masalah.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('admin.laporan_masalah.*')
                            ? 'bg-gradient-to-r from-sky-500 to-sky-600 text-white border-sky-500 shadow-[0_8px_18px_rgba(56,189,248,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('admin.laporan_masalah.*') ? 'bg-white/80' : 'bg-sky-400/70' }}"></span>
                <span>Laporan Cuti</span>
            </a>
        @endif

        {{-- ========== MENU USER (KARYAWAN) ========== --}}
        @if(auth()->check() && Auth::user()->role == 'User')
            <p class="px-2 pt-1 text-[0.65rem] tracking-[0.2em] uppercase text-slate-400">
                Karyawan
            </p>

            <a href="{{ route('user.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('user.dashboard')
                            ? 'bg-gradient-to-r from-sky-500 to-sky-600 text-white border-sky-500 shadow-[0_8px_18px_rgba(56,189,248,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('user.dashboard') ? 'bg-white/80' : 'bg-sky-400/70' }}"></span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('user.leave.create') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('leave.create')
                            ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white border-emerald-500 shadow-[0_8px_18px_rgba(16,185,129,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('leave.create') ? 'bg-white/80' : 'bg-emerald-400/70' }}"></span>
                <span>Ajukan Cuti</span>
            </a>

            <a href="{{ route('user.leave.history') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('leave.history')
                            ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white border-indigo-500 shadow-[0_8px_18px_rgba(79,70,229,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('leave.history') ? 'bg-white/80' : 'bg-indigo-400/70' }}"></span>
                <span>Riwayat Cuti</span>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('profile.edit')
                            ? 'bg-slate-700 text-white border-slate-700 shadow-[0_8px_18px_rgba(30,64,175,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('profile.edit') ? 'bg-white/80' : 'bg-slate-400/70' }}"></span>
                <span>Profil</span>
            </a>
        @endif

        {{-- ========== MENU LEADER ========== --}}
        @if(auth()->check() && auth()->user()->role === 'Leader')
            <p class="px-2 pt-1 text-[0.65rem] tracking-[0.2em] uppercase text-slate-400">
                Leader
            </p>

            <a href="{{ route('leader.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('leader.dashboard')
                            ? 'bg-gradient-to-r from-sky-500 to-sky-600 text-white border-sky-500 shadow-[0_8px_18px_rgba(56,189,248,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('leader.dashboard') ? 'bg-white/80' : 'bg-sky-400/70' }}"></span>
                <span>Dashboard Leader</span>
            </a>

            <a href="{{ route('leave-history') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('leave-history')
                            ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white border-indigo-500 shadow-[0_8px_18px_rgba(79,70,229,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('leave-history') ? 'bg-white/80' : 'bg-indigo-400/70' }}"></span>
                <span>Semua Pengajuan</span>
            </a>

            <a href="{{ route('verifications.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('verifications.*')
                            ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white border-emerald-500 shadow-[0_8px_18px_rgba(16,185,129,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('verifications.*') ? 'bg-white/80' : 'bg-emerald-400/70' }}"></span>
                <span>Menunggu Persetujuan</span>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('profile.edit')
                            ? 'bg-slate-700 text-white border-slate-700 shadow-[0_8px_18px_rgba(30,64,175,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('profile.edit') ? 'bg-white/80' : 'bg-slate-400/70' }}"></span>
                <span>Profil</span>
            </a>

            <a href="{{ route('leader.leave.create') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-xl
                    border
                    {{ request()->routeIs('leader.leave.create')
                            ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white border-emerald-500 shadow-[0_8px_18px_rgba(16,185,129,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                    transition">
                <span class="w-2 h-8 rounded-full
                            {{ request()->routeIs('leader.leave.create') ? 'bg-white/80' : 'bg-emerald-400/70' }}"></span>
                <span>Ajukan Cuti (Leader)</span>
            </a>

        @endif

        {{-- ========== MENU HRD ========== --}}
        @if(auth()->check() && Auth::user()->role == 'HRD')
        
        <a href="{{ route('hrd.employees.index') }}">Data Karyawan</a>
        <a href="{{ route('hrd.divisions.index') }}">Data Divisi</a>

            <p class="px-2 pt-1 text-[0.65rem] tracking-[0.2em] uppercase text-slate-400">
                HRD
            </p>

            <a href="{{ route('hrd.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('hrd.dashboard')
                            ? 'bg-gradient-to-r from-sky-500 to-sky-600 text-white border-sky-500 shadow-[0_8px_18px_rgba(56,189,248,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('hrd.dashboard') ? 'bg-white/80' : 'bg-sky-400/70' }}"></span>
                <span>Dashboard HRD</span>
            </a>

            <a href="{{ route('approvals.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('approvals.*')
                            ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white border-emerald-500 shadow-[0_8px_18px_rgba(16,185,129,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('approvals.*') ? 'bg-white/80' : 'bg-emerald-400/70' }}"></span>
                <span>Approval Cuti</span>
            </a>

            <a href="{{ route('hrd.employees.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('admin.manajemen_user.*')
                            ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white border-indigo-500 shadow-[0_8px_18px_rgba(79,70,229,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('admin.manajemen_user.*') ? 'bg-white/80' : 'bg-indigo-400/70' }}"></span>
                <span>Data Karyawan</span>
            </a>

            <a href="{{ route('hrd.divisions.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('admin.division.*')
                            ? 'bg-gradient-to-r from-sky-500 to-sky-600 text-white border-sky-500 shadow-[0_8px_18px_rgba(56,189,248,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('admin.division.*') ? 'bg-white/80' : 'bg-sky-400/70' }}"></span>
                <span>Data Divisi</span>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl
                      border
                      {{ request()->routeIs('profile.edit')
                            ? 'bg-slate-700 text-white border-slate-700 shadow-[0_8px_18px_rgba(30,64,175,0.45)]'
                            : 'bg-white text-slate-800 border-slate-200 hover:bg-sky-50 hover:border-sky-300' }}
                      transition">
                <span class="w-2 h-8 rounded-full
                             {{ request()->routeIs('profile.edit') ? 'bg-white/80' : 'bg-slate-400/70' }}"></span>
                <span>Profil</span>
            </a>
        @endif

    </nav>
</aside>



    {{-- ======================= MAIN CONTENT ======================= --}}
    <div class="flex-1 flex flex-col min-h-screen">

        {{-- ======================= TOPBAR (yang di gambar) ======================= --}}
       <header class="h-16 bg-navy-800/95 border-b border-slate-800 flex 
                       items-center justify-between px-6 shadow-[0_10px_30px_rgba(15,23,42,0.6)] backdrop-blur">

            <div class="text-xs sm:text-sm md:text-base font-semibold text-slate-100 tracking-[0.18em] flex items-center gap-3 uppercase">
                <!-- garis biru kiri -->
                <span class="w-1 h-8 rounded-full bg-brand-accent shadow-[0_0_0_3px_rgba(56,189,248,0.35)]"></span>
                @yield('title', 'Dashboard')
            </div>

            {{-- USER DROPDOWN --}}
            @if(auth()->check())
                <div class="relative" id="userMenuWrapper">
                    <button type="button"
                            onclick="toggleUserMenu()"
                            class="flex items-center gap-2 bg-slate-900/70 px-3 py-2 rounded-full border border-slate-600
                                   shadow-[0_15px_30px_rgba(15,23,42,0.7)] hover:bg-slate-900/90 transition">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0f172a&color=38bdf8"
                             class="w-9 h-9 rounded-full border border-slate-700">
                        <div class="flex flex-col items-start leading-tight">
                            <span class="text-[0.72rem] text-slate-400 tracking-[0.18em] uppercase">Logged in as</span>
                            <span class="text-slate-50 text-sm font-semibold">{{ Auth::user()->name }}</span>
                        </div>
                    </button>

                    <div id="userMenuDropdown"
                         class="absolute right-0 mt-3 w-56 bg-navy-900/95 shadow-[0_20px_40px_rgba(15,23,42,0.75)] rounded-xl 
                                hidden border border-slate-700 backdrop-blur">
                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-3 text-slate-100 hover:bg-slate-800 text-sm font-medium rounded-t-xl">
                            Profil
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-3 text-red-400 hover:bg-red-950/40 text-sm font-semibold rounded-b-xl">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </header>

        {{-- ======================= PAGE CONTENT ======================= --}}
        <main class="p-4 sm:p-6 lg:p-8 flex-1 bg-gradient-to-b from-slate-100 via-slate-100 to-slate-200">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>