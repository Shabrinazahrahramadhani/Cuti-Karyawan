<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Masuk Sistem - Sistem Cuti Karyawan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            500: '#3b82f6', 
                            600: '#2563eb', 
                            700: '#1d4ed8',
                        },
                        gray: {
                            50: '#fafafa',
                            100: '#f4f4f5',
                            500: '#71717a',
                            900: '#18181b',
                        },
    
                        navy: { 
                            900: '#020617',
                        }
                    },
                    borderRadius: {
                        '2xl': '1rem',
                        '3xl': '1.5rem',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f4f4f5; 
        }
        
        .app-bg-layer {
            display: none;
        }

        .btn-brand-primary {
            transition: all 0.2s ease;
            position: relative;
            z-index: 1;
        }
        .btn-brand-primary:hover {
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.4);
            transform: translateY(-0.5px);
        }
        .btn-brand-primary:active {
            transform: scale(0.99);
        }

        .form-input-modern {
            transition: all 0.2s ease;
            border-radius: 0.75rem; 
        }
        .form-input-modern:focus {
            outline: none;
            border-color: #2563eb; 
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.3);
        }


        .auth-card {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>

<body class="font-sans min-h-screen flex items-center justify-center py-12 text-gray-900">

    <div class="w-full max-w-sm px-4">
        <div class="auth-card bg-white p-8 sm:p-10 rounded-3xl relative z-10">

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
                <div class="w-12 h-12 rounded-xl bg-brand-600 text-white flex items-center justify-center text-xl font-extrabold uppercase shadow-lg shadow-brand-500/40">
                    C
                </div>
                <h1 class="text-2xl font-extrabold mt-5 tracking-tight">
                    Masuk ke Sistem
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola data cuti Anda di sini.
                </p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-gray-700 font-semibold text-sm mb-2">
                        Email
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" 
                           required autofocus autocomplete="username"
                           class="form-input-modern w-full border border-gray-300 text-gray-900 placeholder-gray-500 p-3">
                </div>

                <div>
                    <label for="password" class="block text-gray-700 font-semibold text-sm mb-2">
                        Password
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="form-input-modern w-full border border-gray-300 text-gray-900 placeholder-gray-500 p-3">
                </div>

                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-brand-600 shadow-sm focus:ring-brand-500" name="remember">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                            Ingat Saya
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium transition">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <div class="pt-4">
                    <button type="submit" 
                        class="btn-brand-primary w-full inline-flex items-center justify-center px-6 py-3 rounded-xl bg-brand-600 text-white text-base font-bold tracking-wider uppercase">
                        Masuk Sistem
                    </button>
                </div>
            </form>
            
            <div class="text-center mt-6 text-sm text-gray-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-brand-600 hover:text-brand-700 font-medium transition">
                    Daftar Sekarang
                </a>
            </div>

        </div>
    </div>

</body>
</html>