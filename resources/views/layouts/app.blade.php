<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UiTM Hostel Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col md:flex-row font-sans">

    <aside class="w-full md:w-64 bg-purple-950 text-white flex flex-col shadow-xl">
        <div class="p-5 border-b border-purple-900/50 flex items-center gap-3">
            <span class="text-2xl">🏢</span>
            <div>
                <h2 class="font-bold text-sm tracking-wide">UiTM KT Hostel</h2>
                <p class="text-[10px] text-purple-300/70">Kolej Kasa (Male)</p>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1.5">
            <a href="/student/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-semibold tracking-wide transition {{ Request::is('student/dashboard') ? 'bg-purple-800 text-white shadow-md' : 'text-purple-200/70 hover:bg-purple-900/50 hover:text-white' }}">
                <span>📊</span> Dashboard
            </a>
            <a href="/student/rooms" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-semibold tracking-wide transition {{ Request::is('student/rooms') ? 'bg-purple-800 text-white shadow-md' : 'text-purple-200/70 hover:bg-purple-900/50 hover:text-white' }}">
    <span>🔑</span> Book Room
</a>
            <a href="/student/bookings" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-semibold tracking-wide transition {{ Request::is('student/bookings') ? 'bg-purple-800 text-white shadow-md' : 'text-purple-200/70 hover:bg-purple-900/50 hover:text-white' }}">
    <span>📋</span> My Bookings
</a>
            <a href="/student/eligibility" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-semibold tracking-wide transition {{ Request::is('student/eligibility') ? 'bg-purple-800 text-white shadow-md' : 'text-purple-200/70 hover:bg-purple-900/50 hover:text-white' }}">
    <span>🛡️</span> Eligibility
</a>
            <a href="/student/announcements" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-semibold tracking-wide transition {{ Request::is('student/announcements') ? 'bg-purple-800 text-white shadow-md' : 'text-purple-200/70 hover:bg-purple-900/50 hover:text-white' }}">
    <span>📢</span> Announcements
</a>
        </nav>

        <div class="p-4 border-t border-purple-900/50 bg-purple-950/50 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-purple-800 flex items-center justify-center text-xs font-bold ring-2 ring-purple-500/30">
                    AI
                </div>
                <div>
                    <h4 class="text-xs font-bold leading-tight truncate max-w-[110px]">{{ session('user_name') }}</h4>
                    <p class="text-[9px] text-purple-300/60 font-mono mt-0.5">{{ session('user_id') }}</p>
                </div>
            </div>
            <a href="/logout" class="p-1.5 hover:bg-purple-900 rounded-lg text-purple-300 hover:text-rose-400 transition" title="Sign Out">
                🚪
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0">
        <header class="bg-white border-b border-slate-100 h-16 flex items-center justify-between px-6 md:px-8 shadow-sm">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                <span>Portal Gateway</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-800 font-bold capitalize">{{ Request::segment(2) }}</span>
            </div>
            
            <div class="flex items-center gap-4">
                <button class="relative p-1.5 text-slate-400 hover:text-slate-600 rounded-full transition">
                    🔔
                    <span class="absolute top-1 right-1 w-2 h-2 bg-purple-600 rounded-full"></span>
                </button>
            </div>
        </header>

        <div class="p-6 md:p-8 flex-1 overflow-y-auto">
            @yield('content')
        </div>
    </main>

</body>
</html>