<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Cuti Karyawan - Otomatisasi Cuti</title>

    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

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
                        },
                        slate: {
                            100: '#f1f5f9',
                            300: '#cbd5e1',
                            700: '#334155',
                            900: '#0f172a',
                        }
                    },
                    boxShadow: {
                        neo: '0 22px 60px rgba(15,23,42,0.55)',
                        card: '0 18px 40px rgba(15,23,42,0.32)',
                        'light-card': '0 15px 30px rgba(15,23,42,0.1)',
                    },
                    borderRadius: {
                        '3xl': '1.5rem',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: "Space Grotesk", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #020617;
            min-height: 100vh;
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

        .button-gradient {
            background: linear-gradient(135deg, #2563eb, #38bdf8);
            box-shadow: 0 16px 30px rgba(37, 99, 235, 0.55);
            transition: all 0.2s ease;
        }
        .button-gradient:hover {
            background: linear-gradient(135deg, #2563eb, #22d3ee);
            transform: translateY(-1px);
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.7);
        }

        .dark-card {
            background: #0b1727;
            border: 1px solid #1e293b;
            box-shadow: 0 18px 40px rgba(15,23,42,0.32);
            color: #e5e7eb;
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>

<body class="font-brutal antialiased">
    <div class="app-bg-layer"></div>
    <div class="app-bg-noise"></div>

    <div class="min-h-screen flex flex-col">
        <header class="w-full fixed top-0 z-40 bg-navy-900/90 backdrop-blur-sm border-b border-slate-800/70">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-primary text-white flex items-center justify-center text-sm font-extrabold tracking-widest uppercase shadow-lg shadow-brand-primary/40">
                        SCK
                    </div>
                    <div class="leading-tight">
                        <p class="text-[0.7rem] tracking-[0.3em] uppercase text-slate-400 font-semibold">
                            HR MANAGEMENT
                        </p>
                        <p class="text-base font-bold tracking-wider uppercase text-slate-100">
                            SISTEM CUTI
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('register') }}" class="text-sm font-medium text-slate-300 hover:text-brand-accent transition">
                        Daftar Akun
                    </a>
                    <a href="{{ route('login') }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wider text-white button-gradient">
                        MASUK SISTEM
                    </a>
                </div>
            </div>
        </header>

        <main class="flex-1 w-full pt-32 pb-24 bg-navy-900/0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-16 items-center text-slate-100">
                <div class="space-y-6">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-brand-accent">
                        LEAVE MANAGEMENT SYSTEM V2.0
                    </p>
                    
                    <h1 class="text-5xl sm:text-6xl font-extrabold leading-tight">
                        Otomatisasi Cuti
                        <span class="block text-brand-accent">dengan Dashboard Cerdas.</span>
                    </h1>
                    
                    <p class="text-lg text-slate-400 max-w-md">
                        Sistem Cuti Karyawan yang terintegrasi penuh. Kelola kuota, status persetujuan, dan rekapitulasi data real-time, kapan pun dan dari mana pun.
                    </p>

                    <div class="flex space-x-4 pt-4">
                        <a href="{{ route('login') }}"
                           class="px-8 py-3 rounded-xl text-lg font-bold uppercase tracking-wider text-white button-gradient">
                            MULAI OTOMATISASI
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-8 py-3 rounded-xl text-lg font-bold uppercase tracking-wider text-slate-200 border border-slate-700 hover:bg-slate-800 transition">
                            DAFTAR AKUN
                        </a>
                    </div>
                </div>

                <div class="relative pt-10 flex flex-col items-center">
                    <div class="dark-card rounded-2xl p-6 w-72 transform rotate-2 transition duration-500 ease-in-out hover:shadow-neo absolute top-0 left-0">
                        <p class="text-xs font-bold tracking-widest uppercase text-slate-400 mb-4">DATA CUTI AGREGAT (HRD)</p>
                        <div class="flex justify-between items-center text-center">
                            <div>
                                <p class="text-4xl font-extrabold text-brand-accent">25</p>
                                <p class="text-sm text-slate-400 mt-1">TOTAL PENGAJUAN</p>
                            </div>
                            <div>
                                <p class="text-4xl font-extrabold text-emerald-400">19</p>
                                <p class="text-sm text-slate-400 mt-1">DISETUJUI</p>
                            </div>
                        </div>
                    </div>

                    <div class="dark-card rounded-2xl p-6 w-60 transform -rotate-3 transition duration-500 ease-in-out hover:shadow-neo absolute top-32 right-0">
                         <p class="text-xs font-bold tracking-widest uppercase text-slate-400 mb-4">PERSONAL BALANCE</p>
                        <div class="flex items-center gap-4">
                            <p class="text-5xl font-extrabold text-yellow-300">7</p>
                            <div>
                                <p class="text-xl font-bold text-slate-100">SISA CUTI TAHUNAN</p>
                                <p class="text-sm text-slate-400">HARI</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </main>

        <section class="bg-white text-slate-900 border-t border-slate-200 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                
                <h2 class="text-3xl font-extrabold mb-12 uppercase tracking-wider">
                    KENAPA HARUS MENGGUNAKAN SISTEM INI?
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-slate-50 p-6 rounded-2xl shadow-lg border border-slate-200">
                        <div class="w-12 h-12 bg-brand-primary/10 text-brand-primary rounded-xl inline-flex items-center justify-center mb-4">
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">PROSES CEPAT</h3>
                        <p class="text-slate-600">Pengajuan dan persetujuan cuti diproses secara digital, memangkas waktu administrasi.</p>
                    </div>

                    <div class="bg-slate-50 p-6 rounded-2xl shadow-lg border border-slate-200">
                         <div class="w-12 h-12 bg-emerald-500/10 text-emerald-600 rounded-xl inline-flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">DATA AKURAT</h3>
                        <p class="text-slate-600">Kuota dan riwayat cuti diperbarui secara real-time, meminimalisir kesalahan hitung.</p>
                    </div>

                    <div class="bg-slate-50 p-6 rounded-2xl shadow-lg border border-slate-200">
                        <div class="w-12 h-12 bg-indigo-500/10 text-indigo-600 rounded-xl inline-flex items-center justify-center mb-4">
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">AKSES MUDAH</h3>
                        <p class="text-slate-600">Akses sistem dari mana saja, baik melalui desktop maupun perangkat seluler.</p>
                    </div>
                </div>

            </div>
        </section>
        
        <footer class="w-full bg-navy-900 border-t border-slate-800/70 mt-auto py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} Sistem Cuti Karyawan. All rights reserved.
            </div>
        </footer>

    </div>

</body>
</html>
