<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Eligibility Status - UiTM KT Hostel</title>
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
                            <p class="text-xs font-bold text-white tracking-wide"><span class="font-bold text-white text-sm block capitalize leading-snug">
    {{ $userProfile->userName ?? 'Hostel Student' }}
</span></p>
                            <p class="text-[10px] text-purple-200 font-mono tracking-wider"><span class="text-[10px] font-mono text-purple-200 tracking-wider block">
    {{ $userProfile->userID ?? 'N/A' }}
</span></p>
                        </div>
                    </div>
                    <a href="/logout" class="text-purple-200 hover:text-white transition p-1">➔</a>
                </div>
            </div>

            <!-- Header Navigation Menu Tabs -->
            <nav class="flex gap-6 text-xs font-semibold pt-3 pb-1">
                <a href="/student/dashboard" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
                    <span>🏠</span> Dashboard
                </a>
                <a href="/student/rooms" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
                    <span>🛏️</span> Book Room
                </a>
                <a href="/student/bookings" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
                    <span>📅</span> My Bookings
                </a>
                <a href="/student/eligibility" class="text-white border-b-2 border-white pb-2 flex items-center gap-2 opacity-100">
                    <span>📋</span> Eligibility
                </a>
                <a href="/student/announcements" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
    <span>📢</span> Announcements
</a>
            </nav>
        </div>
    </header>

    <!-- MAIN COMPLIANCE ASSESSMENT VIEWPORT -->
    <main class="max-w-[1600px] mx-auto px-6 py-6 space-y-6">
        
        <div>
            <h2 class="text-xl font-bold text-slate-800">My Eligibility Status</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Your compliance profile and quota status</p>
        </div>

        <!-- GREEN CLEARANCE STATUS BANNER - Matches image_c8cb49.png -->
        <div class="bg-[#00966A] text-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white text-lg shrink-0">
                ✓
            </div>
            <div>
                <h3 class="text-sm font-bold tracking-wide">Eligible to Book</h3>
                <p class="text-xs text-white/90 font-medium mt-0.5">Your account is in good standing. You may book hostel rooms.</p>
            </div>
        </div>

        <!-- ACCOUNT PROFILE METRICS GRID CARD LAYOUT -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white border border-slate-200/60 rounded-2xl p-4 space-y-1 shadow-sm">
                <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">Student ID</span>
                <span class="text-slate-800 font-bold text-xs">2024669856</span>
            </div>
            <div class="bg-white border border-slate-200/60 rounded-2xl p-4 space-y-1 shadow-sm">
                <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">Program</span>
                <span class="text-slate-800 font-bold text-xs">CS241</span>
            </div>
            <div class="bg-white border border-slate-200/60 rounded-2xl p-4 space-y-1 shadow-sm">
                <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">Year of Study</span>
                <span class="text-slate-800 font-bold text-xs">Year 2</span>
            </div>
            <div class="bg-white border border-slate-200/60 rounded-2xl p-4 space-y-1 shadow-sm">
                <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">Assigned Hostel</span>
                <span class="text-slate-800 font-bold text-xs">Kolej Kasa (Male)</span>
            </div>
        </div>

        <!-- POLICY VIOLATION STRIKES MODULE - Matches image_c8cb88.png -->
        <div class="bg-white border border-slate-200/60 rounded-3xl p-5 shadow-sm space-y-4">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Policy Violation Record</h3>
            
            <div class="flex items-center gap-6">
                <!-- Three Strikes Visual Array -->
                <div class="flex gap-2">
                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 font-bold text-xs flex items-center justify-center border border-slate-200/30">1</div>
                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 font-bold text-xs flex items-center justify-center border border-slate-200/30">2</div>
                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 font-bold text-xs flex items-center justify-center border border-slate-200/30">3</div>
                </div>
                
                <div class="space-y-0.5">
                    <p class="text-xs font-bold text-slate-800">0 / 3 strikes</p>
                    <p class="text-[10px] text-slate-400 font-medium">Account locks at 3 strikes</p>
                </div>
            </div>

            <p class="text-xs text-emerald-600 font-semibold pt-1">No violations on record. Keep it up!</p>
        </div>

        <!-- DYNAMIC ALLOCATION QUOTA PROGRESS INDICATOR - Matches image_c8cbcb.png -->
        <div class="bg-white border border-slate-200/60 rounded-3xl p-5 shadow-sm space-y-3">
            <div class="flex justify-between items-center text-xs font-bold text-slate-800">
                <h3 class="uppercase tracking-wider">Booking Quota</h3>
                @php
                    $quotaValue = DB::table('reservations')->where('userID', Session::get('user_id'))->where('bookingStatus', 'Confirmed')->count();
                @endphp
                <span>{{ $quotaValue }}/ 1 booking</span>
            </div>

            <!-- Horizontal Fill Gauge Bar -->
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div class="bg-[#5B06B2] h-full rounded-full transition-all duration-300" style="width: {{ $quotaValue > 0 ? '100%' : '0%' }}"></div>
            </div>

            <p class="text-[10px] text-slate-400 font-medium">One active booking permitted per student per semester.</p>
        </div>

    </main>

</body>
</html>