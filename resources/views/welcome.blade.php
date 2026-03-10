<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Helpdesk & Ticketing Support IT Berbasis Web</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col selection:bg-blue-500 selection:text-white">

    <div class="relative flex-grow flex flex-col overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-600/20 rounded-full blur-[100px]"></div>
            <div class="absolute top-1/2 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-[100px]"></div>
        </div>

        <header class="relative z-10 bg-white/50 backdrop-blur-md border-b border-gray-200/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
                        </div>
                        <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400">Tickety</span>
                    </div>
                    <nav class="flex items-center gap-4 text-sm font-medium">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-gray-900 transition-colors">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition-colors">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 border border-transparent text-white rounded-xl transition-all shadow-md shadow-blue-500/25 transform hover:-translate-y-0.5">Register</a>
                                @endif
                            @endauth
                        @endif
                    </nav>
                </div>
            </div>
        </header>

        <main class="relative z-10 flex-grow flex items-center justify-center container mx-auto px-4 py-16 text-center lg:py-32">
            <div class="max-w-4xl space-y-8">
                <div class="inline-flex items-center px-4 py-2 rounded-full border border-blue-500/30 bg-blue-500/10 text-blue-400 text-sm font-medium mb-4">
                    <span class="flex w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span>
                    Sistem Ticketing Modern
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-bold text-gray-900 tracking-tight leading-tight">
                    Sistem Helpdesk & <br>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500">Ticketing Support IT</span><br>
                    Berbasis Web
                </h1>
                
                <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    Solusi cepat dan efisien untuk menangani keluhan, permintaan, dan insiden IT di lingkungan perusahaan Anda dengan antarmuka yang modern dan responsif.
                </p>
                
                <div class="pt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-4 border border-transparent text-lg font-medium rounded-xl shadow-lg shadow-blue-500/25 text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-500 transition-all transform hover:scale-105">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 border border-transparent text-lg font-medium rounded-xl shadow-lg shadow-blue-500/25 text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-500 transition-all transform hover:scale-105">
                            Daftar Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 border border-gray-200 bg-white/80 hover:bg-gray-50 text-lg font-medium rounded-xl text-gray-700 hover:text-gray-900 border border-gray-300 backdrop-blur-sm transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-gray-500">
                            Masuk
                        </a>
                    @endauth
                </div>

                <div class="pt-16 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-lg mx-auto md:max-w-none text-left">
                    <div class="bg-white/90 backdrop-blur-sm border border-gray-200/50 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 border border-blue-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Penanganan Cepat</h3>
                        <p class="text-gray-600 text-sm">Prioritaskan dan selesaikan isu IT dengan lebih cepat melalui sistem tracking yang terorganisir dengan baik.</p>
                    </div>
                    <div class="bg-white/90 backdrop-blur-sm border border-gray-200/50 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4 border border-indigo-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.957 11.957 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aman & Terpusat</h3>
                        <p class="text-gray-600 text-sm">Semua tiket, riwayat komunikasi, dan lampiran tersimpan secara terpusat dengan infrastruktur yang aman.</p>
                    </div>
                    <div class="bg-white/90 backdrop-blur-sm border border-gray-200/50 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-4 border border-purple-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Lacak Progres</h3>
                        <p class="text-gray-600 text-sm">Pantau status tiket secara realtime dan dapatkan update riwayat pengerjaan secara transparan.</p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="relative z-10 bg-gray-50 border-t border-gray-200 py-8 text-center text-sm font-medium text-gray-500">
            <p>© {{ date('Y') }} Sistem Helpdesk & Ticketing Support IT. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>