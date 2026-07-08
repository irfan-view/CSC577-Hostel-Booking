<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans min-h-screen">

    <!-- =========================================================================
         🏢 TOP NAVIGATION HEADER BAR BLOCK
         ========================================================================= -->
    <header class="bg-[#5B06B2] text-white shadow-sm sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-6">
            <div class="flex justify-between items-center py-4 border-b border-purple-500/30">
                <div class="flex items-center gap-3">
                    <div class="border-2 border-white/80 rounded-xl p-1.5 flex items-center justify-center">
                        <span class="text-sm font-semibold tracking-wider">🏢</span>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold tracking-wide">UiTM KT Hostel</h1>
                        <p class="text-[10px] text-purple-200/80 font-medium">
                            {{ strcasecmp($userProfile->gender ?? 'Male', 'Female') === 0 ? 'Kolej Sutera (Female)' : 'Kolej Kasa (Male)' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-5">
                    <div class="flex items-center gap-3">
                        <!-- Dynamic status indicator dot changes based on strike account health -->
                        <div class="w-2.5 h-2.5 rounded-full {{ ($userProfile->strikeCount ?? 0) >= 3 ? 'bg-rose-400' : 'bg-emerald-400' }} animate-pulse"></div>
                        <div class="text-right">
                            <span class="font-bold text-white text-xs block tracking-wide capitalize">{{ $userProfile->userName ?? 'Student Account' }}</span>
                            <span class="text-[9px] font-mono text-purple-200 tracking-wider block uppercase">{{ $userProfile->userID ?? Session::get('user_id') }}</span>
                        </div>
                    </div>
                    <a href="/logout" class="text-purple-200 hover:text-white transition p-1">➔</a>
                </div>
            </div>

            <!-- Navigation Menu Links Tabs -->
            <nav class="flex gap-6 text-xs font-semibold pt-3 pb-1">
                <a href="/student/dashboard" class="text-white border-b-2 border-white pb-2 flex items-center gap-1.5 opacity-100 font-bold">🏠 Dashboard</a>
                <a href="/student/rooms" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-1.5 opacity-80">🗺️ Book Room</a>
                <a href="/student/bookings" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-1.5 opacity-80">🎫 My Bookings</a>
                <a href="/student/eligibility" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-1.5 opacity-80">📋 Eligibility</a>
                <a href="/student/announcements" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-1.5 opacity-80">📢 Announcements</a>
            </nav>
        </div>
    </header>

    <!-- =========================================================================
         📊 MAIN STUDENT OVERVIEW VIEWPORT
         ========================================================================= -->
    <main class="max-w-[1600px] mx-auto px-6 py-6 space-y-6">

        <!-- Welcome Banner Segment Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Welcome back, <span class="capitalize text-[#5B06B2]">{{ explode(' ', trim($userProfile->userName ?? 'Student'))[0] }}</span></h2>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Semester March 2026 – August 2026 • UiTM Kampus Kuala Terengganu</p>
            </div>
            <span class="text-xs font-bold text-slate-500 bg-white border border-slate-200/60 rounded-xl px-3 py-1.5 shadow-sm uppercase">
                Part {{ $userProfile->semester ?? 1 }} • {{ $userProfile->program ?? 'CS230' }}
            </span>
        </div>

        <!-- Success/Error Interception Flash Channel Banners -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-semibold rounded-xl px-4 py-3 shadow-sm">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-100 text-rose-600 text-xs font-semibold rounded-xl px-4 py-3 shadow-sm">
                <span>⚠️</span> {{ $errors->first() }}
            </div>
        @endif

        <!-- UPPER 4-COLUMN SUMMARY METRIC ROW GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">Available Rooms</span>
                    <span class="text-2xl font-black text-slate-800 mt-1 block font-mono">{{ $availableRoomsCount ?? 0 }}</span>
                </div>
                <div class="bg-emerald-50 text-emerald-600 p-2.5 rounded-xl text-sm">🛏️</div>
            </div>

            <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">Occupied Rooms</span>
                    <span class="text-2xl font-black text-slate-800 mt-1 block font-mono">{{ $occupiedRoomsCount ?? 0 }}</span>
                </div>
                <div class="bg-purple-50 text-[#5B06B2] p-2.5 rounded-xl text-sm">👥</div>
            </div>

            <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">Active Bookings</span>
                    <span class="text-2xl font-black text-slate-800 mt-1 block font-mono">{{ $activeBooking ? '1' : '0' }}</span>
                </div>
                <div class="bg-blue-50 text-blue-600 p-2.5 rounded-xl text-sm">🎫</div>
            </div>

            <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">System Bulletins</span>
                    <span class="text-2xl font-black text-slate-800 mt-1 block font-mono">{{ $announcementsCount ?? 0 }}</span>
                </div>
                <div class="bg-amber-50 text-amber-600 p-2.5 rounded-xl text-sm">📢</div>
            </div>

        </div>

        <!-- TWO-COLUMN SECTION GRID layouts: ALLOCATION WORKFLOWS vs OFFICIAL BULLETINS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- LEFT WRAPPER PANELS (OCCUPIES 2 COLS ON LARGER RENDER DESKS) -->
            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm space-y-6 flex flex-col justify-between">
                <div>
                    <div class="border-b border-slate-100 pb-3 flex items-center gap-2">
                        <span class="text-[#5B06B2] text-sm">🛡️</span>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Allocation Status Ledger</h3>
                    </div>

                    <div class="mt-6">
                        @if($activeBooking)
                            <!-- Confirmed Active Booking Record Container -->
                            <div class="border border-emerald-100 bg-emerald-50/20 rounded-2xl p-5 flex flex-col sm:flex-row justify-between items-start sm:flex-wrap sm:items-center gap-4">
                                <div class="space-y-1">
                                    <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 font-bold rounded-lg text-[10px] uppercase">Active Confirmation Pass</span>
                                    <h4 class="text-sm font-bold text-slate-800 pt-1">Room Secure Token: <span class="font-mono text-[#5B06B2]">{{ $activeBooking->roomTargetID }}</span></h4>
                                    <p class="text-xs text-slate-400">Transaction ID: <span class="font-mono font-bold">{{ $activeBooking->logID }}</span> • Secured Allocation via {{ $activeBooking->securedWordLog }} track</p>
                                </div>
                                <a href="/student/bookings" class="bg-[#5B06B2] hover:bg-purple-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow-sm w-full sm:w-auto text-center">
                                    View Receipt Pass
                                </a>
                            </div>
                        @else
                            <!-- No Active Booking Template Fallback View -->
                            <div class="text-center py-8 space-y-4">
                                <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-lg mx-auto">🛏️</div>
                                <div class="space-y-1 max-w-sm mx-auto">
                                    <h4 class="text-xs font-bold text-slate-800 uppercase">No active room reservation secured</h4>
                                    <p class="text-[11px] text-slate-400 font-medium">You have not registered a residential room allocation footprint for the current academic semester cycle session.</p>
                                </div>
                                
                                <div class="pt-2 flex justify-center">
                                    @if(($userProfile->strikeCount ?? 0) >= 3)
                                        <!-- If student has 3 or more strikes, redirect them to check their suspension ledger -->
                                        <a href="/student/eligibility" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-5 py-3 rounded-xl shadow-sm transition flex items-center gap-2">
                                            ⚠️ View Account Restrictions
                                        </a>
                                    @else
                                        <!-- Normal active student navigation path shortcut -->
                                        <a href="/student/rooms" class="bg-[#5B06B2] hover:bg-purple-700 text-white text-xs font-bold px-5 py-3 rounded-xl shadow-sm transition flex items-center gap-2">
                                            🚀 Browse Available Rooms
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- RIGHT WRAPPER PANELS: OFFICIAL SYSTEM BULLETINS LIVE FEED (OCCUPIES 1 COL) -->
            <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-3 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="text-amber-500 text-sm">📢</span>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Latest Bulletins</h3>
                    </div>
                    <a href="/student/announcements" class="text-[11px] font-bold text-[#5B06B2] hover:underline">View All</a>
                </div>

                <!-- Live Announcements Dynamic Deck Iteration -->
                <div class="space-y-3 overflow-y-auto max-h-[320px] pr-1">
                    @forelse($announcements as $bulletin)
                        <div class="p-3 border rounded-xl shadow-2xs transition-all duration-200 hover:border-purple-200
                            {{ $bulletin->is_urgent ? 'bg-amber-50/40 border-amber-200' : 'bg-slate-50/50 border-slate-100' }}">
                            <div class="flex justify-between items-start gap-2">
                                <h4 class="text-xs font-bold text-slate-800 line-clamp-1 leading-snug">{{ $bulletin->title }}</h4>
                                @if($bulletin->is_urgent)
                                    <span class="bg-amber-100 text-amber-800 font-bold text-[8px] px-1.5 py-0.5 rounded-md uppercase shrink-0 tracking-wide">Urgent</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-500 line-clamp-2 mt-1 leading-normal">{{ $bulletin->body }}</p>
                            <span class="text-[9px] font-mono text-slate-400 mt-2 block">{{ date('d M Y, h:i A', strtotime($bulletin->created_at)) }}</span>
                        </div>
                    @empty
                        <div class="text-center py-12 space-y-2">
                            <span class="text-xl block">📭</span>
                            <p class="text-[11px] font-medium text-slate-400">No recent official notices broadcasted.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </main>

</body>
</html>