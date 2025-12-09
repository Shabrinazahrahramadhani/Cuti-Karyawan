<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Daftar Akun - Sistem Cuti Karyawan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Google Font: Space Grotesk --}}
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Space Grotesk', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        // WARNA SESUAI APP.BLADE.PHP
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
                            400: '#94a3b8',
                            500: '#64748b',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
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
    <style>
        body {
            background-color: #f1f5f9; 
        }
 
        .app-bg-layer {
            display: none;
        }

        .auth-card {
            background: #ffffff;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9; 
        }

        .btn-brand-primary {
            background: linear-gradient(135deg, #1d4ed8, #38bdf8);
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.4); 
            text-transform: uppercase;
            letter-spacing: 0.16em;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        .btn-brand-primary:hover {
            background: linear-gradient(135deg, #2563eb, #22d3ee);
            transform: translateY(-1px);
            box-shadow: 0 16px 30px rgba(37, 99, 235, 0.55);
        }

        .form-input-modern {
            background: #ffffff;
            border-radius: 0.9rem;
            border: 1px solid #cbd5e1; 
            padding: 0.7rem 0.95rem;
            font-size: 0.9rem;
            color: #0f172a; 
            box-shadow: 0 0 0 1px rgba(148, 163, 184, 0.1);
            outline: none;
            transition: all 0.16s ease;
        }
        .form-input-modern:focus {
            background: #ffffff;
            border-color: #2563eb; 
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.4);
        }

        .form-label-modern {
            color: #334155; 
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.14em;
        }
    </style>
</head>


<body class="font-sans min-h-screen flex items-center justify-center py-12 text-slate-900">

    <div class="w-full max-w-md px-4">
        <div class="auth-card p-8 sm:p-10 rounded-3xl relative z-10">

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-300 text-red-700 rounded-xl">
                    <p class="font-semibold text-sm mb-1">Terjadi Kesalahan</p>
                    <ul class="text-xs list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-col items-center mb-10 text-center">
                {{-- Logo --}}
                <div class="w-12 h-12 rounded-xl bg-brand-primary text-white flex items-center justify-center text-xl font-extrabold uppercase shadow-lg shadow-brand-primary/40">
                    SCK
                </div>
                <h1 class="text-2xl font-extrabold mt-5 tracking-tight text-slate-900">
                    Daftar Akun Baru
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Silakan isi data diri Anda untuk membuat akun.
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="form-label-modern block mb-2">
                        Nama Lengkap
                    </label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" 
                           required autofocus autocomplete="name"
                           class="form-input-modern w-full">
                </div>

                <div>
                    <label for="email" class="form-label-modern block mb-2">
                        Email
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" 
                           required autocomplete="username"
                           class="form-input-modern w-full">
                </div>

                <div>
                    <label for="password" class="form-label-modern block mb-2">
                        Password
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="form-input-modern w-full">
                </div>

                <div>
                    <label for="password_confirmation" class="form-label-modern block mb-2">
                        Konfirmasi Password
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="form-input-modern w-full">
                </div>

                <div class="pt-4">
                    <button type="submit" 
                        class="btn-brand-primary w-full inline-flex items-center justify-center px-6 py-3 rounded-xl text-white font-bold uppercase">
                        Daftar Akun
                    </button>
                </div>
            </form>
            
            <div class="text-center mt-6 text-sm text-slate-500">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-brand-primary hover:text-brand-deep font-medium transition">
                    Masuk Sistem
                </a>
            </div>

        </div>
    </div>

</body>
</html>