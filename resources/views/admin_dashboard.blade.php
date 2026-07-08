<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - System Overview</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans min-h-screen">

    <!-- MASTER PURPLE PROTOTYPE TOPBAR HEADER -->
    <header class="bg-[#5B06B2] text-white shadow-sm sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-6">
            <!-- Top branding section -->
            <div class="flex justify-between items-center py-4 border-b border-purple-500/30">
                <div class="flex items-center gap-3">
                    <div class="border-2 border-white/80 rounded-xl p-1.5 flex items-center justify-center">
                        <span class="text-sm font-semibold tracking-wider">🛡️</span>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold tracking-wide">Admin Panel</h1>
                        <p class="text-[10px] text-purple-200/80 font-medium">Kolej Kasa & Kolej Sutera</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-5">
                    <div class="text-right">
    <!-- Capitalizes and renders the active administrator's real name from the database table -->
    <p class="text-xs font-bold text-white tracking-wide capitalize">{{ $adminProfile->userName ?? 'JPK Kolej Kasa & Sutera' }}</p>
    <!-- Renders the exact active ID token from the session pool -->
    <p class="text-[10px] text-purple-200 font-mono tracking-wider uppercase">{{ Session::get('user_id', 'ADMIN001') }}</p>
</div>
                    <a href="/logout" class="text-purple-200 hover:text-white transition duration-150 p-1">
                        <span class="text-lg">➔</span>
                    </a>
                </div>
            </div>

            <!-- Horizontal navigation bar tabs -->
            <nav class="flex gap-6 text-xs font-semibold pt-3 pb-1">
                <a href="/admin/dashboard" class="text-white border-b-2 border-white pb-2 flex items-center gap-2 opacity-100">
                    <span>📋</span> Overview
                </a>
                <a href="/admin/rooms" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
    <span>🔧</span> Room Management
</a>
                <a href="/admin/vacancy" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
    <span>👁️</span> Room Vacancy
</a>
                <a href="/admin/bookings" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
    <span>📅</span> All Bookings
</a>
                <a href="/admin/announcements" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
    <span>📢</span> Announcements
</a>
            </nav>
        </div>
    </header>

    <!-- CONTENT FRAME CONTAINER -->
    <main class="max-w-[1600px] mx-auto px-6 py-6 space-y-6">

        <!-- Title Row block header -->
        <div>
            <h2 class="text-xl font-bold text-slate-800">System Overview</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Semester March 2026 – August 2026 · Kolej Kasa & Kolej Sutera</p>
        </div>

        <!-- UPPER 4-COLUMN SUMMARY METRIC ROW -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Card 1: Available Rooms -->
            <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm flex flex-col justify-between min-h-[130px]">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-medium text-slate-400">Available Rooms</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $availableRooms }}</h3>
                    </div>
                    <div class="bg-emerald-50 text-emerald-600 rounded-xl p-2.5 text-xs font-bold border border-emerald-100 shadow-sm">🛏️</div>
                </div>
                <p class="text-[10px] font-medium text-slate-400 mt-2">Ready to book</p>
            </div>

            <!-- Card 2: Occupied Rooms -->
            <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm flex flex-col justify-between min-h-[130px]">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-medium text-slate-400">Occupied Rooms</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $occupiedRooms }}</h3>
                    </div>
                    <div class="bg-purple-50 text-purple-600 rounded-xl p-2.5 text-xs font-bold border border-purple-100 shadow-sm">👥</div>
                </div>
                <p class="text-[10px] font-medium text-slate-400 mt-2">Currently filled</p>
            </div>

            <!-- Card 3: Active Bookings -->
            <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm flex flex-col justify-between min-h-[130px]">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-medium text-slate-400">Active Bookings</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $activeBookings }}</h3>
                    </div>
                    <div class="bg-indigo-50 text-indigo-600 rounded-xl p-2.5 text-xs font-bold border border-indigo-100 shadow-sm">📅</div>
                </div>
                <p class="text-[10px] font-medium text-slate-400 mt-2">This semester</p>
            </div>

            <!-- Card 4: Under Maintenance -->
            <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm flex flex-col justify-between min-h-[130px]">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-medium text-slate-400">Under Maintenance</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-2">31</h3>
                    </div>
                    <div class="bg-slate-50 text-slate-600 rounded-xl p-2.5 text-xs font-bold border border-slate-100 shadow-sm">🔧</div>
                </div>
                <p class="text-[10px] font-medium text-slate-400 mt-2">Temporarily closed</p>
            </div>

        </div>

        <!-- MID ROW: PROGRESS HOSTEL BARS -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            
            <!-- Kolej Kasa Summary -->
            <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-xs">🏢</div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">Kolej Kasa</h4>
                            <p class="text-[10px] font-medium text-slate-400">Male Hostel</p>
                        </div>
                    </div>
                    <span class="text-base font-extrabold text-slate-800">20%</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden mb-3">
                    <div class="bg-[#5B06B2] h-full rounded-full" style="width: 20%"></div>
                </div>
                <div class="flex justify-between text-[10px] font-medium text-slate-400">
                    <span>80 available</span>
                    <span>28 occupied</span>
                    <span>141 total</span>
                </div>
            </div>

            <!-- Kolej Sutera Summary -->
            <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-pink-50 text-pink-600 border border-pink-100 flex items-center justify-center text-xs">🛏️</div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">Kolej Sutera</h4>
                            <p class="text-[10px] font-medium text-slate-400">Female Hostel</p>
                        </div>
                    </div>
                    <span class="text-base font-extrabold text-slate-800">18%</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden mb-3">
                    <div class="bg-[#5B06B2] h-full rounded-full" style="width: 18%"></div>
                </div>
                <div class="flex justify-between text-[10px] font-medium text-slate-400">
                    <span>81 available</span>
                    <span>26 occupied</span>
                    <span>141 total</span>
                </div>
            </div>

        </div>

        <!-- LOWER ROW: CHARTS AREA VISUAL PLACEHOLDERS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm min-h-[220px] flex flex-col justify-between">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">📊 Room Status Distribution</h4>
                <div class="flex-1 flex items-center justify-center border border-dashed border-slate-200 rounded-xl bg-slate-50/50 p-4">
                    <p class="text-[11px] font-medium text-purple-600/70 font-mono text-center">Available: 161 &nbsp;|&nbsp; Occupied: 54 &nbsp;|&nbsp; Reserved: 36 &nbsp;|&nbsp; Maintenance: 31</p>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm min-h-[220px] flex flex-col justify-between">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">📈 Occupancy by Hostel Cluster</h4>
                <div class="flex-1 flex items-center justify-center border border-dashed border-slate-200 rounded-xl bg-slate-50/50 p-4">
                    <p class="text-[11px] font-medium text-amber-600 font-mono text-center">Kolej Kasa: 80 Vacant Beds &nbsp;|&nbsp; Kolej Sutera: 81 Vacant Beds</p>
                </div>
            </div>
        </div>

        <!-- BOTTOM ROW: RECENT ACTIVITY TIMELINE ENGINE -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 border-b border-slate-100 pb-3">Recent Activity Log</h3>
            
            <div class="space-y-4">
                <!-- Log 1 -->
                <div class="flex gap-4 items-start text-xs border-b border-slate-50 pb-3 last:border-0 last:pb-0">
                    <div class="text-slate-400 mt-0.5">🕒</div>
                    <div>
                        <p class="font-semibold text-slate-700">Kolej Kasa Room KASA-301 set to Maintenance</p>
                        <p class="text-[10px] text-slate-400 mt-0.5 font-mono">ADMIN001 · 2026-06-12 09:14</p>
                    </div>
                </div>

                <!-- Log 2 -->
                <div class="flex gap-4 items-start text-xs border-b border-slate-50 pb-3 last:border-0 last:pb-0">
                    <div class="text-slate-400 mt-0.5">🕒</div>
                    <div>
                        <p class="font-semibold text-slate-700">Kolej Sutera Floor 3 reserved for PALAPES</p>
                        <p class="text-[10px] text-slate-400 mt-0.5 font-mono">ADMIN001 · 2026-06-11 17:30</p>
                    </div>
                </div>

                <!-- Log 3 -->
                <div class="flex gap-4 items-start text-xs border-b border-slate-50 pb-3 last:border-0 last:pb-0">
                    <div class="text-slate-400 mt-0.5">🕒</div>
                    <div>
                        <p class="font-semibold text-slate-700">Booking BK0003 cancelled by Admin</p>
                        <p class="text-[10px] text-slate-400 mt-0.5 font-mono">ADMIN001 · 2026-06-11 08:45</p>
                    </div>
                </div>

                <!-- Log 4 -->
                <div class="flex gap-4 items-start text-xs">
                    <div class="text-slate-400 mt-0.5">🕒</div>
                    <div>
                        <p class="font-semibold text-slate-700">Announcement published: Hostel Booking Open</p>
                        <p class="text-[10px] text-slate-400 mt-0.5 font-mono">ADMIN001 · 2026-06-10 11:20</p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer class="text-center py-6 text-[10px] text-slate-400 font-medium border-t border-slate-200/40 bg-white mt-12">
        UiTM Single Sign-On Secured Administration Gateway · Core Build 2026
    </footer>

</body>
</html>