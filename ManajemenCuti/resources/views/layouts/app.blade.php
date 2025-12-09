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
                            primary: '#2563eb',
                            soft: '#e0f2fe',
                            accent: '#38bdf8',
                            deep: '#1e293b',
                        }
                    },
                    boxShadow: {
                        neo: '0 22px 60px rgba(15,23,42,0.55)',
                        card: '0 18px 40px rgba(15,23,42,0.32)',
                    },
                    borderRadius: {
                        '3xl': '1.5rem',
                    }
                }
            }
        }
    </script>

    <!-- Styling global, sidebar, form-card, dll -->
    <style>
        body {
            font-family: "Space Grotesk", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #020617;
        }

        .app-bg-layer {
            position: fixed;
            inset: 0;
            z-index: -10;
            background:
                radial-gradient(circle at top left, rgba(56,189,248,0.22), transparent 55%),
                radial-gradient(circle at bottom right, rgba(79,70,229,0.21), transparent 55%),
                linear-gradient(135deg, #020617 0%, #020617 40%, #020617 100%);
        }

        .app-bg-noise {
            position: fixed;
            inset: 0;
            z-index: -9;
            opacity: 0.35;
            pointer-events: none;
            background-image:
                linear-gradient(0deg, rgba(148,163,184,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148,163,184,0.06) 1px, transparent 1px);
            background-size: 80px 80px;
        }

        /* ====== ANIMASI HALUS ====== */
        @keyframes fadeInUpSoft {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes floatSoft {
            from { transform: translateY(0); }
            50%  { transform: translateY(-4px); }
            to   { transform: translateY(0); }
        }

        /* ====== SIDEBAR & NAV ====== */
        #sidebar {
            box-shadow: 0 30px 90px rgba(0,0,0,0.85);
            animation: fadeInUpSoft .45s ease-out;
        }

        #sidebar nav a {
            position: relative;
            overflow: hidden;
        }

        #sidebar nav a::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: 0;
            background: radial-gradient(circle at top left, rgba(56,189,248,0.22), transparent 60%);
            transition: opacity .2s ease;
            pointer-events: none;
        }

        #sidebar nav a:hover::before {
            opacity: 1;
        }

        /* text menu kecil & rapi */
        #sidebar nav a span.nav-label {
            font-size: 0.78rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        /* ====== TOPBAR ====== */
        header.h-16 {
            animation: fadeInUpSoft .45s ease-out;
            backdrop-filter: blur(18px);
        }

        /* ====== CARD / FORM CONTAINER (form cuti, dll) ====== */
        .max-w-3xl.mx-auto.mt-10 > .bg-white.shadow-xl.rounded-2xl.p-10.border.border-gray-100,
        .max-w-2xl.mx-auto.bg-white.shadow-lg.rounded-lg.p-8.mt-10 {
            background: radial-gradient(circle at top left, #0b1220 0, #020617 60%, #020617 100%);
            border-radius: 1.5rem;
            border: 1px solid rgba(30,64,175,0.55);
            box-shadow: 0 28px 60px rgba(15, 23, 42, 0.85);
            animation: fadeInUpSoft .5s ease-out;
        }

        /* TITLE di dalam card */
        .max-w-3xl h2.text-3xl.font-bold.text-gray-800.mb-8.border-l-8.border-blue-600.pl-4,
        .max-w-2xl h2.text-2xl.font-bold.mb-6.text-gray-800 {
            border-left-width: 0;
            padding-left: 0;
            margin-bottom: 2.25rem;
            font-size: 1.9rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #e5e7eb;
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .max-w-3xl h2.text-3xl.font-bold.text-gray-800.mb-8.border-l-8.border-blue-600.pl-4::before,
        .max-w-2xl h2.text-2xl.font-bold.mb-6.text-gray-800::before {
            content: "";
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-right: 2px;
            border-radius: 9999px;
            background: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.30);
            flex-shrink: 0;
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
        .max-w-3xl input[type="date"],
        .max-w-3xl input[type="number"],
        .max-w-3xl textarea,
        .max-w-3xl select,
        .max-w-2xl input[type="text"],
        .max-w-2xl input[type="email"],
        .max-w-2xl input[type="password"],
        .max-w-2xl input[type="date"],
        .max-w-2xl input[type="number"],
        .max-w-2xl textarea,
        .max-w-2xl select {
            background: #020617;
            border-radius: 0.9rem;
            border: 1px solid #1f2937;
            padding: 0.7rem 0.95rem;
            font-size: 0.9rem;
            color: #e5e7eb;
            box-shadow: 0 0 0 1px rgba(148, 163, 184, 0.2);
            outline: none;
            transition:
                transform 0.16s ease,
                box-shadow 0.16s ease,
                background-color 0.16s ease,
                border-color 0.16s ease;
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
        .max-w-3xl input[type="date"]:focus,
        .max-w-3xl input[type="number"]:focus,
        .max-w-3xl textarea:focus,
        .max-w-3xl select:focus,
        .max-w-2xl input[type="text"]:focus,
        .max-w-2xl input[type="email"]:focus,
        .max-w-2xl input[type="password"]:focus,
        .max-w-2xl input[type="date"]:focus,
        .max-w-2xl input[type="number"]:focus,
        .max-w-2xl textarea:focus,
        .max-w-2xl select:focus {
            background: #02081a;
            border-color: #38bdf8;
            transform: translateY(-1px);
            box-shadow:
                0 0 0 1px rgba(56, 189, 248, 0.9),
                0 22px 34px rgba(15, 23, 42, 0.7);
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
            border-radius: 0.9rem;
            border: 0;
            box-shadow: 0 18px 32px rgba(37, 99, 235, 0.55);
            text-transform: uppercase;
            letter-spacing: 0.16em;
            font-size: 0.78rem;
        }

        .max-w-3xl button.bg-green-600:hover,
        .max-w-2xl button.bg-blue-600:hover {
            background: linear-gradient(135deg, #2563eb, #22d3ee);
            transform: translateY(-1px);
            box-shadow: 0 24px 44px rgba(37, 99, 235, 0.65);
        }

        /* SECONDARY BUTTON (BACK) */
        .max-w-3xl a.bg-gray-200 {
            background: transparent;
            color: #e5e7eb;
            border-radius: 0.9rem;
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
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.65);
        }

        .max-w-3xl.mx-auto.mt-10,
        .max-w-2xl.mx-auto.mt-10 {
            margin-top: 3.5rem;
        }

        /* custom scrollbar halus */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(15,23,42,0.9);
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #38bdf8, #6366f1);
            border-radius: 999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #22d3ee, #4f46e5);
        }
    </style>
</head>

<body class="font-brutal min-h-screen text-slate-100 antialiased">
    <div class="app-bg-layer"></div>
    <div class="app-bg-noise"></div>

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

            if (!wrapper.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>

    <div class="flex relative">

        {{-- ======================= SIDEBAR ======================= --}}
        <aside id="sidebar"
               class="fixed lg:static z-50 w-64 min-h-screen 
                      bg-gradient-to-b from-slate-950 via-slate-900 to-slate-800/95
                      border-r border-slate-800
                      transform -translate-x-full lg:translate-x-0 transition-all duration-300
                      flex flex-col justify-between">

            <div>
                {{-- Header Sidebar --}}
                <div class="p-6 flex justify-between items-center border-b border-slate-800/80">
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-3">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-3xl
                                        bg-gradient-to-tr from-sky-500 via-indigo-500 to-violet-500 shadow-2xl">
                                <span class="text-[0.6rem] tracking-[0.22em] font-semibold uppercase text-white">
                                    HR
                                </span>
                            </div>
                            <div>
                                <h1 class="text-[0.7rem] font-semibold text-slate-100 tracking-[0.22em] uppercase">
                                    Sistem Cuti
                                </h1>
                                <p class="text-[0.65rem] text-slate-400">
                                    Manajemen Cuti &amp; Approval
                                </p>
                            </div>
                        </div>

                        @auth
                            <div class="mt-4 flex items-center gap-3 rounded-3xl bg-slate-900/80 px-3 py-3 border border-slate-700/80">
                                <div class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center text-[0.7rem] font-semibold shadow-inner">
                                    {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 2)) }}
                                </div>
                                <div class="leading-tight">
                                    <p class="text-[0.7rem] font-semibold text-slate-50 truncate">
                                        {{ auth()->user()->name }}
                                    </p>
                                    <p class="text-[0.6rem] text-emerald-300 flex items-center gap-1">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Online · {{ ucfirst(auth()->user()->role ?? 'User') }}
                                    </p>
                                </div>
                            </div>
                        @endauth
                    </div>
                    <button onclick="toggleSidebar()"
                            class="lg:hidden px-3 py-2 rounded-xl
                                   bg-slate-900/90 text-slate-100 border border-slate-700
                                   shadow-sm text-sm">
                        ☰
                    </button>
                </div>

                {{-- NAV --}}
                <nav class="px-4 py-5 space-y-5 text-sm font-medium overflow-y-auto max-h-[calc(100vh-10rem)]">

                    {{-- ========== MENU ADMIN ========== --}}
                    @if(auth()->check() && Auth::user()->role == 'Admin')
                        <div class="space-y-3">
                            <p class="px-2 text-[0.65rem] tracking-[0.2em] uppercase text-slate-400">
                                Admin 
                            </p>

                            <nav class="space-y-3">

                                {{-- DASHBOARD --}}
                                <a href="{{ route('admin.dashboard') }}"
                                   class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-sm font-semibold
                                          {{ request()->routeIs('admin.dashboard') 
                                                ? 'bg-white text-slate-900 shadow-lg' 
                                                : 'bg-slate-900/50 text-slate-100 hover:bg-slate-800/80 border border-slate-700/60' }}">
                                    <span class="w-8 h-8 rounded-xl flex items-center justify-center
                                                 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white' : 'bg-slate-800 text-slate-200' }}">
                                        {{-- icon home --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M3 10.75 12 4l9 6.75M4.5 10.75V20h5v-4.25h5V20h5v-9.25"/>
                                        </svg>
                                    </span>
                                    <span class="nav-label">Dashboard</span>
                                </a>
                            </nav>

                            <a href="{{ route('admin.division.index') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('admin.division.*')
                                            ? 'bg-gradient-to-r from-sky-500 to-sky-600 text-white border-sky-500 shadow-[0_12px_30px_rgba(56,189,248,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-sky-500/20">
                                    {{-- icon grid --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Manajemen Divisi</span>
                            </a>

                            <a href="{{ route('admin.manajemen_user.index') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('admin.manajemen_user.*')
                                            ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white border-indigo-500 shadow-[0_12px_30px_rgba(79,70,229,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-indigo-500/20">
                                    {{-- icon users --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M16 14a4 4 0 10-8 0m8 0v1a4 4 0 01-4 4m4-5a4 4 0 00-4-4m0 9a4 4 0 01-4-4v-1m4 5H5m14 0h-3"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Manajemen Pengguna</span>
                            </a>

                            <a href="{{ route('admin.cuti.index') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('admin.cuti.*')
                                            ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white border-emerald-500 shadow-[0_12px_30px_rgba(16,185,129,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-emerald-500/20">
                                    {{-- icon calendar --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M8 7V4m8 3V4M4 9h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1z"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Manajemen Cuti</span>
                            </a>

                            <a href="{{ route('admin.laporan_masalah.index') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('admin.laporan_masalah.*')
                                            ? 'bg-gradient-to-r from-sky-500 to-sky-600 text-white border-sky-500 shadow-[0_12px_30px_rgba(56,189,248,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-sky-500/20">
                                    {{-- icon report --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M5 3h6l2 3h6v13H5V3zm4 8h6m-6 4h3"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Laporan Cuti</span>
                            </a>
                        </div>
                    @endif

                    {{-- ========== MENU USER (KARYAWAN) ========== --}}
                    @if(auth()->check() && Auth::user()->role == 'User')
                        <div class="space-y-3">
                            <p class="px-2 pt-1 text-[0.65rem] tracking-[0.2em] uppercase text-slate-400">
                                Karyawan
                            </p>

                            <a href="{{ route('user.dashboard') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('user.dashboard')
                                            ? 'bg-gradient-to-r from-sky-500 to-sky-600 text-white border-sky-500 shadow-[0_12px_30px_rgba(56,189,248,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-sky-500/20">
                                    {{-- icon dashboard --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M4 13h6V4H4v9zm10 7h6V4h-6v16zM4 20h6v-5H4v5z"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Dashboard</span>
                            </a>

                            <a href="{{ route('user.leave.create') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('leave.create')
                                            ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white border-emerald-500 shadow-[0_12px_30px_rgba(16,185,129,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-emerald-500/20">
                                    {{-- icon plus --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 4v16m8-8H4"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Ajukan Cuti</span>
                            </a>

                            <a href="{{ route('user.leave.history') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('leave.history')
                                            ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white border-indigo-500 shadow-[0_12px_30px_rgba(79,70,229,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-indigo-500/20">
                                    {{-- icon clock --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 6v6l3 3m5-3a8 8 0 11-16 0 8 8 0 0116 0z"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Riwayat Cuti</span>
                            </a>

                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('profile.edit')
                                            ? 'bg-slate-700 text-white border-slate-700 shadow-[0_12px_30px_rgba(30,64,175,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-slate-500/20">
                                    {{-- icon user --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 12a4 4 0 100-8 4 4 0 000 8zM6 20a6 6 0 1112 0H6z"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Profil</span>
                            </a>
                        </div>
                    @endif

                    {{-- ========== MENU LEADER ========== --}}
                    @if(auth()->check() && auth()->user()->role === 'Leader')
                        <div class="space-y-3">
                            <p class="px-2 pt-1 text-[0.65rem] tracking-[0.2em] uppercase text-slate-400">
                                Leader
                            </p>

                            <a href="{{ route('leader.dashboard') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('leader.dashboard')
                                            ? 'bg-gradient-to-r from-sky-500 to-sky-600 text-white border-sky-500 shadow-[0_12px_30px_rgba(56,189,248,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-sky-500/20">
                                    {{-- icon dashboard --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M4 13h6V4H4v9zm10 7h6V4h-6v16zM4 20h6v-5H4v5z"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Dashboard Leader</span>
                            </a>

                            <a href="{{ route('leave-history') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('leave-history')
                                            ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white border-indigo-500 shadow-[0_12px_30px_rgba(79,70,229,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-indigo-500/20">
                                    {{-- icon list --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M4 6h16M4 12h10M4 18h7"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Semua Pengajuan</span>
                            </a>

                            <a href="{{ route('verifications.index') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('verifications.*')
                                            ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white border-emerald-500 shadow-[0_12px_30px_rgba(16,185,129,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-emerald-500/20">
                                    {{-- icon check --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Menunggu Persetujuan</span>
                            </a>

                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('profile.edit')
                                            ? 'bg-slate-700 text-white border-slate-700 shadow-[0_12px_30px_rgba(30,64,175,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-slate-500/20">
                                    {{-- icon user --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 12a4 4 0 100-8 4 4 0 000 8zM6 20a6 6 0 1112 0H6z"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Profil</span>
                            </a>

                            <a href="{{ route('leader.leave.create') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('leader.leave.create')
                                            ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white border-emerald-500 shadow-[0_12px_30px_rgba(16,185,129,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-emerald-500/20">
                                    {{-- icon plus --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 4v16m8-8H4"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Ajukan Cuti (Leader)</span>
                            </a>
                        </div>
                    @endif

                    {{-- ========== MENU HRD ========== --}}
                    @if(auth()->check() && Auth::user()->role == 'HRD')
                        {{-- quick link versi teks lama (tetap dipertahankan, cuma dibikin lebih rapi) --}}
                        <div class="px-2 space-y-1 text-[0.78rem] text-slate-300">
                            <a href="{{ route('hrd.employees.index') }}" class="inline-flex items-center gap-2 hover:text-sky-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                                Data Karyawan
                            </a>
                            <a href="{{ route('hrd.divisions.index') }}" class="inline-flex items-center gap-2 hover:text-sky-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                                Data Divisi
                            </a>
                        </div>

                        <div class="space-y-3 mt-3">
                            <p class="px-2 pt-1 text-[0.65rem] tracking-[0.2em] uppercase text-slate-400">
                                HRD
                            </p>

                            <a href="{{ route('hrd.dashboard') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('hrd.dashboard')
                                            ? 'bg-gradient-to-r from-sky-500 to-sky-600 text-white border-sky-500 shadow-[0_12px_30px_rgba(56,189,248,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-sky-500/20">
                                    {{-- icon dashboard --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M4 13h6V4H4v9zm10 7h6V4h-6v16zM4 20h6v-5H4v5z"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Dashboard HRD</span>
                            </a>

                            <a href="{{ route('approvals.index') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('approvals.*')
                                            ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white border-emerald-500 shadow-[0_12px_30px_rgba(16,185,129,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-emerald-500/20">
                                    {{-- icon check --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Approval Cuti</span>
                            </a>

                            <a href="{{ route('hrd.employees.index') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('admin.manajemen_user.*')
                                            ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white border-indigo-500 shadow-[0_12px_30px_rgba(79,70,229,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-indigo-500/20">
                                    {{-- icon users --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M16 14a4 4 0 10-8 0m8 0v1a4 4 0 01-4 4m4-5a4 4 0 00-4-4m0 9a4 4 0 01-4-4v-1m4 5H5m14 0h-3"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Data Karyawan</span>
                            </a>

                            <a href="{{ route('hrd.divisions.index') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('admin.division.*')
                                            ? 'bg-gradient-to-r from-sky-500 to-sky-600 text-white border-sky-500 shadow-[0_12px_30px_rgba(56,189,248,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-sky-500/20">
                                    {{-- icon grid --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Data Divisi</span>
                            </a>

                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-3 px-4 py-2 rounded-2xl
                                      border relative overflow-hidden
                                      {{ request()->routeIs('profile.edit')
                                            ? 'bg-slate-700 text-white border-slate-700 shadow-[0_12px_30px_rgba(30,64,175,0.55)]'
                                            : 'bg-slate-900/60 text-slate-100 border-slate-700 hover:bg-slate-800/90' }}
                                      transition">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-slate-500/20">
                                    {{-- icon user --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 12a4 4 0 100-8 4 4 0 000 8zM6 20a6 6 0 1112 0H6z"/>
                                    </svg>
                                </span>
                                <span class="nav-label">Profil</span>
                            </a>
                        </div>
                    @endif

                </nav>
            </div>

            {{-- footer kecil di sidebar --}}
            <div class="px-4 pb-4 pt-2 border-t border-slate-800/70 text-[0.65rem] text-slate-500 flex items-center justify-between">
                <span>Cuti Karyawan v1.0</span>
                <span class="inline-flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span>Secure Workspace</span>
                </span>
            </div>
        </aside>



        {{-- ======================= MAIN CONTENT ======================= --}}
        <div class="flex-1 flex flex-col min-h-screen lg:ml-0">

            {{-- ======================= TOPBAR ======================= --}}
            <header class="h-16 bg-white/90 border-b border-slate-200/70 flex 
                           items-center justify-between px-4 sm:px-6 shadow-sm backdrop-blur-xl">

                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline-block w-1 h-8 rounded-full bg-brand-accent shadow-[0_0_0_3px_rgba(56,189,248,0.35)]"></span>
                    <div class="flex flex-col">
                        <div class="text-[0.72rem] text-slate-400 tracking-[0.20em] uppercase">
                            Sistem Manajemen Cuti
                        </div>
                        <div class="text-xs sm:text-sm md:text-base font-semibold text-slate-800 tracking-[0.18em] uppercase">
                            @yield('title', 'Dashboard')
                        </div>
                    </div>
                </div>

                {{-- USER DROPDOWN --}}
                @if(auth()->check())
                    <div class="relative" id="userMenuWrapper">
                        <button type="button"
                                onclick="toggleUserMenu()"
                                class="flex items-center gap-3 bg-slate-100/80 px-3 py-1.5 rounded-full border border-slate-300/80
                                       shadow-sm hover:bg-slate-200/90 transition">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=e5e7eb&color=0f172a"
                                 class="w-9 h-9 rounded-full border border-slate-300 shadow-sm">
                            <div class="flex flex-col items-start leading-tight">
                                <span class="text-[0.72rem] text-slate-500 tracking-[0.18em] uppercase">Logged in as</span>
                                <span class="text-slate-800 text-sm font-semibold">{{ Auth::user()->name }}</span>
                            </div>
                        </button>

                        <div id="userMenuDropdown"
                             class="absolute right-0 mt-3 w-56 bg-white/95 shadow-[0_20px_40px_rgba(15,23,42,0.18)] rounded-2xl 
                                    hidden border border-slate-200 backdrop-blur-xl overflow-hidden">
                            <a href="{{ route('profile.edit') }}"
                               class="block px-4 py-3 text-slate-800 hover:bg-slate-50 text-sm font-medium">
                                Profil
                            </a>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-3 text-red-500 hover:bg-red-50 text-sm font-semibold">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

            </header>

            {{-- ======================= PAGE CONTENT ======================= --}}
            <main class="p-4 sm:p-6 lg:p-8 flex-1 bg-gradient-to-b from-slate-100 via-slate-100 to-slate-200/90">
                <div class="max-w-6xl mx-auto">
                    @yield('content')
                </div>
            </main>

        </div>

    </div>

</body>
</html>
