<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Ticket System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 h-screen flex selection:bg-blue-500 selection:text-white overflow-hidden">

    <!-- Sidebar -->
    <aside
        class="w-64 flex-shrink-0 bg-white/50 backdrop-blur-xl border-r border-gray-200/50 flex flex-col hidden md:flex transition-all z-20">
        <!-- Brand / Logo -->
        <div class="h-16 flex items-center px-6 border-b border-gray-200/50">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-4 h-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5">
                        </path>
                    </svg>
                </div>
                <span
                    class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400 tracking-tight">Tickety</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Main Menu</p>

            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 {{ request()->routeIs('dashboard') ? 'bg-blue-500/10 text-blue-600 border-r-2 border-blue-500' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }} px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-500' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('tickets.index') }}"
                class="flex items-center gap-3 {{ request()->routeIs('tickets.index') ? 'bg-blue-500/10 text-blue-600 border-r-2 border-blue-500' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }} px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 {{ request()->routeIs('tickets.index') ? 'text-blue-500' : 'text-gray-500' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                All Tickets
            </a>

            @if (Auth::user()->role === 'admin')
                <a href="{{ route('reports.export') }}"
                    class="flex items-center gap-3 text-gray-500 hover:text-gray-900 hover:bg-gray-100 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export Tickets (CSV)
                </a>

                <a href="{{ route('users.index') }}"
                    class="flex items-center gap-3 {{ request()->routeIs('users.*') ? 'bg-blue-500/10 text-blue-600 border-r-2 border-blue-500' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }} px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 {{ request()->routeIs('users.*') ? 'text-blue-500' : 'text-gray-500' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Manage Users
                </a>
            @endif

            <a href="{{ route('knowledge-base.index') }}"
                class="flex items-center gap-3 {{ request()->routeIs('knowledge-base.*') ? 'bg-blue-500/10 text-blue-600 border-r-2 border-blue-500' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }} px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 {{ request()->routeIs('knowledge-base.*') ? 'text-blue-500' : 'text-gray-500' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
                Knowledge Base
            </a>

            @if (Auth::user()->role === 'admin')
                <a href="{{ route('categories.index') }}"
                    class="flex items-center gap-3 {{ request()->routeIs('categories.*') ? 'bg-blue-500/10 text-blue-600 border-r-2 border-blue-500' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }} px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 {{ request()->routeIs('categories.*') ? 'text-blue-500' : 'text-gray-500' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    Manage Categories
                </a>
            @endif

            <div class="pt-4 mt-4 border-t border-gray-200/50">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Actions</p>
                <a href="{{ route('tickets.create') }}"
                    class="flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white px-3 py-2.5 rounded-xl text-sm font-medium transition-all shadow-md shadow-blue-500/20 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Ticket
                </a>
            </div>
        </nav>

        <!-- User Profile Area (Sidebar Bottom) -->
        <div class="border-t border-gray-200/50 p-4 bg-gray-50/40">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-600 to-indigo-500 text-white flex items-center justify-center font-bold text-sm text-gray-900 shadow-inner border border-gray-300">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800 leading-tight truncate max-w-[120px]"
                            title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 flex items-center gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="uppercase tracking-wider font-medium">{{ Auth::user()->role }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 text-gray-500 hover:text-red-600 hover:bg-red-500/10 px-3 py-2 rounded-lg text-sm font-medium transition-colors border border-transparent hover:border-red-500/20 focus:outline-none focus:ring-2 focus:ring-red-500/50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Column (Navbar + Content) -->
    <div class="flex-1 flex flex-col min-w-0 bg-gray-50 overflow-hidden relative">
        <!-- Background Accents (Optional, for flavor) -->
        <div
            class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-600/5 rounded-full blur-[120px] pointer-events-none -translate-y-1/2 translate-x-1/3">
        </div>

        <!-- Top Header (Mobile Navbar + Page Context) -->
        <header
            class="h-16 bg-gray-50/80 backdrop-blur-md border-b border-gray-200/50 flex items-center justify-between px-4 sm:px-6 z-10 sticky top-0">
            <!-- Mobile Menu Toggle (Only visible on small screens) -->
            <div class="flex items-center gap-4 md:hidden">
                <div class="flex items-center gap-2">
                    <div
                        class="w-6 h-6 rounded bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center">
                        <svg class="w-3 h-3 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Page Title Context (Optional, defaults to empty or app name on small screens) -->
            <div class="text-gray-500 text-sm font-medium hidden sm:flex items-center gap-2">
                @if (request()->routeIs('dashboard'))
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    Dashboard
                    <span class="text-gray-600">/</span> Overview
                @elseif(request()->routeIs('tickets.*'))
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Tickets
                    @if (request()->routeIs('tickets.create'))
                        <span class="text-gray-600">/</span> Create
                    @elseif(request()->routeIs('tickets.show'))
                        <span class="text-gray-600">/</span> Details
                    @elseif(request()->routeIs('tickets.edit'))
                        <span class="text-gray-600">/</span> Edit
                    @endif
                @endif
            </div>

            <!-- Header Right Section -->
            <div class="flex items-center gap-4">
                {{-- Notifications Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false"
                        class="relative p-2 text-gray-400 hover:text-gray-500 rounded-full hover:bg-gray-100 transition-colors focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        @if (Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                            <span
                                class="absolute top-1 right-1 flex items-center justify-center min-w-[1rem] h-4 px-1 text-[10px] font-bold text-white bg-red-500 border-2 border-white rounded-full">{{ Auth::user()->unreadNotifications->count() }}</span>
                        @endif
                    </button>

                    <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                        class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 overflow-hidden">
                        <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-gray-800">Notifikasi</h3>
                            @if (Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.markAllRead') }}" method="POST"
                                    class="m-0">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium">Tandai semua
                                        dibaca</button>
                                </form>
                            @endif
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @if (Auth::check())
                                @forelse(Auth::user()->notifications()->take(10)->get() as $notification)
                                    <a href="{{ route('notifications.markAsRead', $notification->id) }}"
                                        class="block px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50/20' }}">
                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $notification->data['message'] ?? 'Notifikasi baru' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $notification->created_at->diffForHumans() }}</p>
                                    </a>
                                @empty
                                    <div class="px-4 py-6 text-center text-sm text-gray-500">
                                        <p>Tidak ada notifikasi.</p>
                                    </div>
                                @endforelse
                            @endif
                        </div>
                    </div>
                </div>

                <span class="text-sm font-semibold text-gray-700 md:hidden">{{ Auth::user()->name }}</span>

                <a href="{{ route('tickets.create') }}"
                    class="md:hidden flex items-center justify-center w-8 h-8 rounded-full bg-blue-600/20 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                </a>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto w-full">
            <!-- Mobile Menu Dropdown (Hidden by default, simplistic implement) -->
            <div
                class="md:hidden flex gap-4 p-4 border-b border-gray-200/50 bg-white/80 overflow-x-auto whitespace-nowrap hide-scrollbar">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center px-4 py-1.5 rounded-full {{ request()->routeIs('dashboard') ? 'bg-blue-500/20 text-blue-600 border border-blue-500/30' : 'bg-gray-700/50 text-gray-700' }} text-sm font-medium">Dashboard</a>
                <a href="{{ route('tickets.index') }}"
                    class="inline-flex items-center px-4 py-1.5 rounded-full {{ request()->routeIs('tickets.index') ? 'bg-blue-500/20 text-blue-600 border border-blue-500/30' : 'bg-gray-700/50 text-gray-700' }} text-sm font-medium">All
                    Tickets</a>
                <form method="POST" action="{{ route('logout') }}" class="m-0 inline-flex">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-4 py-1.5 rounded-full bg-red-500/10 text-red-600 border border-red-500/20 text-sm font-medium">Logout</button>
                </form>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full max-w-full">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Inject CSS to Hide Scrollbar for Mobile nav -->
    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</body>

</html>
