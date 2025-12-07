<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen space-y-6">
        
        <h1 class="text-3xl font-bold text-gray-800">
            Selamat Datang
        </h1>
        
        <p class="text-gray-600">
            Sistem Manajemen Cuti Karyawan
        </p>

        <div class="flex space-x-4 mt-6">
            <a href="{{ route('login') }}"
               class="px-6 py-2 font-semibold text-white bg-blue-500 rounded-lg">
                Login
            </a>

            <a href="{{ route('register') }}"
               class="px-6 py-2 font-semibold text-blue-500 border border-blue-500 rounded-lg">
                Register
            </a>
        </div>
    </div>
</x-guest-layout>
