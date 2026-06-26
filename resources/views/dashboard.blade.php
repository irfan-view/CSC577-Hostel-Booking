<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - UiTM KT Hostel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans min-h-screen">

    <!-- PURPLE BRANDING HEADER BAR -->
    <header class="bg-[#5B06B2] text-white shadow-sm sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-6">
            <div class="flex justify-between items-center py-4 border-b border-purple-500/30">
                <div class="flex items-center gap-3">
                    <div class="border-2 border-white/80 rounded-xl p-1.5 flex items-center justify-center">
                        <span class="text-sm font-semibold tracking-wider">🏢</span>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold tracking-wide">UiTM KT Hostel</h1>
                        <p class="text-[10px] text-purple-200/80 font-medium">Kolej Kasa (Male)</p>
                    </div>
                </div>
                <div class="flex items-center gap-5">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-orange-400 animate-pulse"></div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-white tracking-wide">Ahmad Irfan</p>
                            <p class="text-[10px] text-purple-200 font-mono tracking-wider">2024669856</p>
                        </div>
                    </div>
                    <a href="/logout" class="text-purple-200 hover:text-white transition p-1">➔</a>
                </div>
            </div>

            <!-- Dashboard Navigation Row -->
            <nav class="flex gap-6 text-xs font-semibold pt-3 pb-1">
                <a href="/student/dashboard" class="text-white border-b-2 border-white pb-2 flex items-center gap-2 opacity-100">
                    <span>🏠</span> Dashboard
                </a>
                <a href="/student/rooms" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
                    <span>🛏️</span> Book Room
                </a>
                <a href="/student/bookings" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
                    <span>📅</span> My Bookings
                </a>
                <a href="/student/eligibility" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
                    <span>📋</span> Eligibility
                </a>
                <a href="/student/announcements" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
    <span>📢</span> Announcements
</a>
            </nav>
        </div>
    </header>

    <!-- CONTENT PORTAL GRID BODY -->
    <main class="max-w-[1600px] mx-auto px-6 py-6 space-y-6">
        
        <div>
            <h2 class="text-xl font-bold text-slate-800">Welcome back, Ahmad</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Semester March 2026 – August 2026 · Kolej Kasa (Male)</p>
        </div>

        <!-- FOUR COLUMNS STATS DECK OVERVIEW -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Available Rooms Card -->
            <div class="bg-white border border-emerald-100 rounded-2xl p-5 shadow-sm flex flex-col justify-between h-28 relative overflow-hidden">
                <div class="absolute right-4 top-4 text-emerald-500 bg-emerald-50 p-1.5 rounded-xl border border-emerald-100/30">🛏️</div>
                <div class="text-2xl font-black text-slate-800 mt-2">{{ $availableRoomsCount ?? 80 }}</div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Available Rooms</div>
            </div>

            <!-- My Bookings Card -->
            <div class="bg-white border border-purple-100 rounded-2xl p-5 shadow-sm flex flex-col justify-between h-28 relative overflow-hidden">
                <div class="absolute right-4 top-4 text-purple-500 bg-purple-50 p-1.5 rounded-xl border border-purple-100/30">📅</div>
                <div class="text-2xl font-black text-slate-800 mt-2">{{ $activeBooking ? 1 : 0 }}</div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">My Bookings</div>
            </div>

            <!-- Announcements Card -->
            <div class="bg-white border border-amber-100 rounded-2xl p-5 shadow-sm flex flex-col justify-between h-28 relative overflow-hidden">
                <div class="absolute right-4 top-4 text-amber-500 bg-amber-50/60 p-1.5 rounded-xl border border-amber-100/30">📣</div>
                <div class="text-2xl font-black text-slate-800 mt-2">4</div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Announcements</div>
            </div>

            <!-- Kasa Total Rooms Card -->
            <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm flex flex-col justify-between h-28 relative overflow-hidden">
                <div class="absolute right-4 top-4 text-slate-400 bg-slate-50 p-1.5 rounded-xl border border-slate-200/30">🏢</div>
                <div class="text-2xl font-black text-slate-800 mt-2">{{ $totalRoomsCount ?? 141 }}</div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Kasa Total Rooms</div>
            </div>
        </div>

        <!-- CORE ACTIVE BOOKING BOARD DECK CHANNEL -->
        @if(!$activeBooking)
            <div class="bg-white border-2 border-dashed border-purple-200 rounded-3xl p-8 text-center shadow-sm space-y-4 flex flex-col items-center justify-center py-10">
                <div class="text-purple-400 text-3xl bg-purple-50 w-14 h-14 rounded-2xl flex items-center justify-center border border-purple-100/40 shadow-inner">🛏️</div>
                <div class="space-y-1">
                    <h3 class="text-sm font-bold text-slate-800">No active booking</h3>
                    <p class="text-xs text-slate-400 font-medium">Book a room in Kolej Kasa (Male) before slots fill up</p>
                </div>
                <a href="/student/rooms" class="bg-[#5B06B2] hover:bg-[#4A058F] text-white text-xs font-bold px-6 py-2.5 rounded-xl transition shadow-sm">
                    Browse Rooms
                </a>
            </div>
        @else
            <div class="bg-white border border-purple-100 rounded-3xl p-6 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-white to-purple-50/20">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500 text-lg">✓</div>
                    <div>
                        <span class="text-[9px] bg-emerald-50 border border-emerald-100 text-emerald-600 font-extrabold px-2 py-0.5 rounded-md uppercase tracking-wider">Active Allocation</span>
                        <h3 class="text-sm font-bold text-slate-800 mt-1">Allocated in Room {{ $activeBooking->roomTargetID }}</h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Kolej Kasa (Male) · Status Confirmed</p>
                    </div>
                </div>
                <a href="/student/bookings" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition">
                    View Receipt Details
                </a>
            </div>
        @endif

        <!-- LATEST ANNOUNCEMENTS SUB-STREAM -->
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Latest Announcements</h3>
                <a href="/student/announcements" class="text-xs font-bold text-purple-700 hover:text-purple-900 transition">View all</a>
            </div>

            <div class="space-y-3">
                <!-- Bullet 1: Urgent -->
                <div class="bg-rose-50/50 border border-rose-100 rounded-2xl p-4 flex flex-col space-y-1.5 relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500"></div>
                    <div class="flex items-center gap-2 text-xs font-bold text-rose-700">
                        <span>🚨</span>
                        <h4>Hostel Booking Open — Semester March 2026</h4>
                    </div>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed pl-5">Room booking for Semester March 2026 – August 2026 is now open. Kolej Kasa (Male) and Kolej Sutera (Female) students are encouraged to book early. Booking closes on 30 June 2026.</p>
                    <span class="text-[10px] text-slate-400 font-mono pl-5">2026-06-12</span>
                </div>

                <!-- Bullet 2: Scheduled Maintenance -->
                <div class="bg-white border border-slate-200/60 rounded-2xl p-4 flex flex-col space-y-1.5 relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-purple-600"></div>
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-800">
                        <span>📢</span>
                        <h4>Kolej Kasa Floor 3 — Scheduled Maintenance</h4>
                    </div>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed pl-5">Scheduled maintenance will be conducted on Kolej Kasa Floor 3 from 15–17 June 2026. Affected rooms are temporarily unavailable for booking.</p>
                    <span class="text-[10px] text-slate-400 font-mono pl-5">2026-06-10</span>
                </div>
            </div>
        </div>

    </main>

</body>
</html>