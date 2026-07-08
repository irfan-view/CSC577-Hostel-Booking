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
                        <p class="text-[10px] text-purple-200/80 font-medium">
                            {{ strcasecmp($userProfile->gender ?? 'Male', 'Female') === 0 ? 'Kolej Sutera (Female)' : 'Kolej Kasa (Male)' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-5">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full {{ ($userProfile->strikeCount ?? 0) >= 3 ? 'bg-rose-400' : 'bg-emerald-400' }} animate-pulse"></div>
                        <div class="text-right">
                            <span class="font-bold text-white text-sm block capitalize leading-snug">
                                {{ $userProfile->userName ?? 'Hostel Student' }}
                            </span>
                            <span class="text-[10px] font-mono text-purple-200 tracking-wider block uppercase">
                                {{ $userProfile->userID ?? 'N/A' }}
                            </span>
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

        <!-- DYNAMIC CLEARANCE STATUS BANNER -->
        @if(($userProfile->strikeCount ?? 0) >= 3)
            <div class="bg-rose-600 text-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white text-lg shrink-0">
                    🚫
                </div>
                <div>
                    <h3 class="text-sm font-bold tracking-wide">Account Suspended</h3>
                    <p class="text-xs text-white/90 font-medium mt-0.5">Your booking privileges have been locked due to excessive hostel policy violations. Please consult JPK management.</p>
                </div>
            </div>
        @else
            <div class="bg-[#00966A] text-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white text-lg shrink-0">
                    ✓
                </div>
                <div>
                    <h3 class="text-sm font-bold tracking-wide">Eligible to Book</h3>
                    <p class="text-xs text-white/90 font-medium mt-0.5">Your account is in good standing. You may book hostel rooms.</p>
                </div>
            </div>
        @endif

        <!-- ACCOUNT PROFILE METRICS GRID CARD LAYOUT -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white border border-slate-200/60 rounded-2xl p-4 space-y-1 shadow-sm">
                <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">Student ID</span>
                <span class="text-slate-800 font-bold text-xs font-mono">{{ $userProfile->userID ?? 'N/A' }}</span>
            </div>
            <div class="bg-white border border-slate-200/60 rounded-2xl p-4 space-y-1 shadow-sm">
                <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">Program</span>
                <span class="text-slate-800 font-bold text-xs uppercase">{{ $userProfile->program ?? 'CS230' }}</span>
            </div>
            <div class="bg-white border border-slate-200/60 rounded-2xl p-4 space-y-1 shadow-sm">
                <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">Semester</span>
                <span class="text-slate-800 font-bold text-xs">Part {{ $userProfile->semester ?? 1 }}</span>
            </div>
            <div class="bg-white border border-slate-200/60 rounded-2xl p-4 space-y-1 shadow-sm">
                <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">Assigned Hostel</span>
                <span class="text-slate-800 font-bold text-xs">
                    {{ strcasecmp($userProfile->gender ?? 'Male', 'Female') === 0 ? 'Kolej Sutera (Female)' : 'Kolej Kasa (Male)' }}
                </span>
            </div>
        </div>

        <!-- POLICY VIOLATION STRIKES MODULE -->
        <div class="bg-white border border-slate-200/60 rounded-3xl p-5 shadow-sm space-y-4">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Policy Violation Record</h3>
            
            <div class="flex items-center gap-6">
                <!-- Three Strikes Visual Array -->
                <div class="flex gap-2">
                    <div class="w-8 h-8 rounded-full font-bold text-xs flex items-center justify-center border transition-all duration-200
                        {{ ($userProfile->strikeCount ?? 0) >= 1 ? 'bg-rose-600 text-white border-rose-600 shadow-sm' : 'bg-slate-100 text-slate-400 border-slate-200/30' }}">1</div>
                    <div class="w-8 h-8 rounded-full font-bold text-xs flex items-center justify-center border transition-all duration-200
                        {{ ($userProfile->strikeCount ?? 0) >= 2 ? 'bg-rose-600 text-white border-rose-600 shadow-sm' : 'bg-slate-100 text-slate-400 border-slate-200/30' }}">2</div>
                    <div class="w-8 h-8 rounded-full font-bold text-xs flex items-center justify-center border transition-all duration-200
                        {{ ($userProfile->strikeCount ?? 0) >= 3 ? 'bg-rose-700 text-white border-rose-700 shadow-sm animate-pulse' : 'bg-slate-100 text-slate-400 border-slate-200/30' }}">3</div>
                </div>
                
                <div class="space-y-0.5">
                    <p class="text-xs font-bold text-slate-800">{{ $userProfile->strikeCount ?? 0 }} / 3 strikes</p>
                    <p class="text-[10px] text-slate-400 font-medium">
                        {{ ($userProfile->strikeCount ?? 0) >= 3 ? 'Account locked immediately.' : 'Account locks at 3 strikes' }}
                    </p>
                </div>
            </div>

            <!-- Dynamic Alert Output Messages -->
            @if(($userProfile->strikeCount ?? 0) == 0)
                <p class="text-xs text-emerald-600 font-semibold pt-1">No violations on record. Keep it up!</p>
            @elseif(($userProfile->strikeCount ?? 0) == 1)
                <p class="text-xs text-amber-600 font-semibold pt-1">⚠️ Warning issued. Please adhere closely to hostel guidelines.</p>
            @elseif(($userProfile->strikeCount ?? 0) == 2)
                <p class="text-xs text-orange-600 font-bold pt-1">🚨 Critical Status: Final warning before total suspension.</p>
            @else
                <p class="text-xs text-rose-600 font-bold uppercase tracking-wide pt-1">🚫 Eviction protocol enforced. Account frozen.</p>
            @endif
        </div>

        <!-- DYNAMIC ALLOCATION QUOTA PROGRESS INDICATOR -->
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