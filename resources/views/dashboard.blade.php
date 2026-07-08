<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UiTM KT Hostel Booking System - Student Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans min-h-screen">

    <!-- =========================================================================
         🏢 TOP NAVIGATION HEADER BAR BLOCK (SYNCED TO MATCH THEME COLOR #5B06B2)
         ========================================================================= -->
    <header class="bg-[#5B06B2] text-white shadow-sm sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-6">
            <div class="flex justify-between items-center py-4 border-b border-purple-500/30">
                <!-- Branding Placement -->
                <div class="flex items-center gap-3">
                    <div class="border-2 border-white/80 rounded-xl p-1.5 flex items-center justify-center">
                        <span class="text-sm font-semibold tracking-wider">🏢</span>
                    </div>
                    <!-- Locate this section inside <header> -->
<div>
    <h1 class="text-sm font-bold tracking-wide">UiTM KT Hostel</h1>
    
    <!-- DYNAMIC GENDER HOSTEL SUBTITLE FIX -->
    <p class="text-[10px] text-purple-200/80 font-medium">
        @if(in_array($userProfile->userID, ['2024881234', '2024114567']))
            Kolej Sutera (Female)
        @else
            Kolej Kasa (Male)
        @endif
    </p>
</div>
                </div>

                <!-- Dynamic User Account Profile Badge Details -->
                <div class="flex items-center gap-5">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-orange-400 animate-pulse"></div>
                        <div class="text-right">
                            <span class="font-bold text-white text-sm block capitalize leading-snug">
                                {{ $userProfile->userName ?? 'Hostel Student' }}
                            </span>
                            <span class="text-[10px] font-mono text-purple-200 tracking-wider block">
                                {{ $userProfile->userID ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                    <!-- Logout Control -->
                    <a href="/logout" class="text-purple-200 hover:text-white transition p-1" title="Sign Out Ecosystem">
                        ➔
                    </a>
                </div>
            </div>
        </div>

        <!-- Secondary Core Tab Panel Matrix -->
        <div class="max-w-[1600px] mx-auto px-6">
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

    <!-- =========================================================================
         📊 MAIN CENTRAL CONTENT LAYOUT CONSOLE
         ========================================================================= -->
    <main class="max-w-[1600px] mx-auto px-6 py-6 space-y-6">
        
        <!-- Welcome Greeting Panel Display -->
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                Welcome back, <span class="capitalize text-[#5B06B2]">{{ explode(' ', trim($userProfile->userName ?? 'Student'))[0] }}</span>
            </h2>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">
                Semester March 2026 – August 2026 • UiTM Kampus Kuala Terengganu
            </p>
        </div>

        <!-- Feedback Alerts Interceptor -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-semibold rounded-xl px-4 py-3 shadow-sm">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-100 text-rose-600 text-xs font-semibold rounded-xl px-4 py-3 shadow-sm">
                @foreach ($errors->all() as $error)
                    <div class="flex items-center gap-2"><span>⚠️</span> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- =========================================================================
             📈 ANALYTICS CORE OVERVIEW ROW METRIC MATRIX CARD GRID
             ========================================================================= -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Card 1: Available Rooms Layout Balance -->
            <div class="bg-white border border-slate-200/60 rounded-3xl p-5 shadow-sm flex flex-col justify-between h-28 relative overflow-hidden group hover:shadow-md transition duration-200">
                <div class="absolute right-4 top-4 text-emerald-50 bg-emerald-500/10 p-1.5 rounded-xl border border-emerald-100/30 group-hover:scale-110 transition">🛏️</div>
                <div class="text-2xl font-black text-slate-800 mt-2 font-mono tracking-wide">{{ $availableRoomsCount ?? 0 }}</div>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Available Rooms</div>
            </div>

            <!-- Card 2: My Personal Bookings Count Monitoring -->
            <div class="bg-white border border-slate-200/60 rounded-3xl p-5 shadow-sm flex flex-col justify-between h-28 relative overflow-hidden group hover:shadow-md transition duration-200">
                <div class="absolute right-4 top-4 text-purple-50 bg-[#5B06B2]/10 p-1.5 rounded-xl border border-purple-100/30 group-hover:scale-110 transition">📅</div>
                <div class="text-2xl font-black text-slate-800 mt-2 font-mono tracking-wide">{{ $activeBooking ? 1 : 0 }}</div>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">My Bookings</div>
            </div>

            <!-- Card 3: Dynamic Synchronized Bulletins Tracker -->
            <div class="bg-white border border-slate-200/60 rounded-3xl p-5 shadow-sm flex flex-col justify-between h-28 relative overflow-hidden group hover:shadow-md transition duration-200">
                <div class="absolute right-4 top-4 text-amber-50 bg-amber-500/10 p-1.5 rounded-xl border border-amber-100/30 group-hover:scale-110 transition">📢</div>
                <div class="text-2xl font-black text-slate-800 mt-2 font-mono tracking-wide">{{ $announcementsCount ?? 0 }}</div>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Announcements</div>
            </div>

            <!-- Card 4: Total Physical Structure Counter Block -->
            <div class="bg-white border border-slate-200/60 rounded-3xl p-5 shadow-sm flex flex-col justify-between h-28 relative overflow-hidden group hover:shadow-md transition duration-200">
                <div class="absolute right-4 top-4 text-blue-50 bg-blue-500/10 p-1.5 rounded-xl border border-blue-100/30 group-hover:scale-110 transition">🏢</div>
                <div class="text-2xl font-black text-slate-800 mt-2 font-mono tracking-wide">{{ $totalRoomsCount ?? 0 }}</div>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Total System Rooms</div>
            </div>

        </div>

        <!-- =========================================================================
             🛡️ LOGICAL ACTIVE ALLOCATION TRANSACTION CONSOLE MONITOR
             ========================================================================= -->
        <div class="bg-white border border-slate-200/60 rounded-3xl shadow-sm p-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-6 flex items-center gap-2">
                <span>🛡️</span> Allocation Status Ledger
            </h3>

            @if($activeBooking)
                <!-- State Alpha: Room Already Confirmed Secure -->
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-5 bg-purple-50/60 rounded-2xl border border-purple-100/50 gap-4">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-[#5B06B2] rounded-xl flex items-center justify-center text-xl text-white shadow-md">
                            🎯
                        </div>
                        <div>
                            <div class="text-xs font-bold text-purple-900 uppercase tracking-wide">Active Allocation Confirmed</div>
                            <h4 class="text-lg font-bold text-slate-800 mt-0.5 font-mono tracking-wide">Room Code: {{ $activeBooking->roomTargetID }}</h4>
                            <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                                Arrangement: <span class="badge uppercase bg-purple-200/50 px-1.5 py-0.5 rounded text-[#5B06B2] font-bold text-[10px]">{{ $activeBooking->securedWordLog }}</span> • Receipt Token ID: <span class="font-mono font-bold text-slate-600">{{ $activeBooking->logID }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3 w-full md:w-auto">
                        <a href="/student/bookings" class="w-full md:w-auto text-center px-4 py-2 bg-[#5B06B2] hover:bg-[#4A058F] text-white rounded-xl text-xs font-bold transition shadow-sm">
                            View Receipt Details
                        </a>
                    </div>
                </div>
            @else
                <!-- State Beta: Empty Workspace Index Placeholder -->
                <div class="text-center py-10 flex flex-col items-center justify-center">
                    <div class="h-14 w-14 bg-slate-50 border border-slate-200/60 rounded-2xl flex items-center justify-center text-2xl shadow-sm mb-4">
                        🛏️
                    </div>
                    <h4 class="text-sm font-bold text-slate-700">No active room reservation secure</h4>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1 font-medium leading-relaxed">
                        You have not secured a residential room allocation footprint for the current academic semester session cycle interface.
                    </p>
                    <a href="/student/rooms" class="mt-5 px-5 py-2.5 bg-[#5B06B2] hover:bg-[#4A058F] text-white rounded-2xl text-[11px] font-bold shadow-sm hover:shadow transition flex items-center gap-2">
                        <span>🚀</span> Browse Available Rooms
                    </a>
                </div>
            @endif
        </div>

        <!-- =========================================================================
             📢 LATEST ANNOUNCEMENTS BOARD FEED
             ========================================================================= -->
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                    <span>📢</span> Latest Announcements
                </h3>
                <a href="/student/announcements" class="text-xs font-bold text-[#5B06B2] hover:underline">View all</a>
            </div>

            <div class="bg-white border border-slate-200/60 rounded-3xl p-4 shadow-sm divide-y divide-slate-100">
                <!-- Fetch Announcements Dynamically from Web Controller Data Stream -->
                @php
                    // Fast local injection query fallback so the feed updates dynamically inline 
                    $latestBulletins = DB::table('announcements')->orderBy('created_at', 'desc')->take(3)->get();
                @endphp

                @forelse($latestBulletins as $notice)
                    <div class="py-4 first:pt-1 last:pb-1 flex flex-col space-y-1.5">
                        <div class="flex items-center justify-between gap-4">
                            <h4 class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                @if($notice->is_urgent)
                                    <span class="flex h-2 w-2 rounded-full bg-rose-500 animate-pulse"></span>
                                    <span class="text-rose-600 font-extrabold uppercase text-[9px] bg-rose-50 border border-rose-100 px-1.5 py-0.5 rounded tracking-wide">Urgent</span>
                                @else
                                    <span>📌</span>
                                @endif
                                <span class="{{ $notice->is_urgent ? 'text-rose-700 font-extrabold' : '' }}">{{ $notice->title }}</span>
                            </h4>
                            <span class="text-[10px] font-mono text-slate-400 font-medium">{{ date('Y-m-d', strtotime($notice->created_at)) }}</span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed pl-5">
                            {{ $notice->body }}
                        </p>
                    </div>
                @empty
                    <div class="text-center py-6 text-xs font-medium text-slate-400 flex flex-col items-center gap-2">
                        <span>📭</span> No recent administrative announcements published yet.
                    </div>
                @endforelse
            </div>
        </div>

    </main>
</body>
</html>