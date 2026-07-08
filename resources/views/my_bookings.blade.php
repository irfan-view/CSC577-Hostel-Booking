<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - UiTM KT Hostel</title>
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
                        <div class="w-2.5 h-2.5 rounded-full {{ ($userProfile->strikeCount ?? 0) >= 3 ? 'bg-rose-400' : 'bg-emerald-400' }} animate-pulse"></div>
                        <div class="text-right">
                            <span class="font-bold text-white text-xs block tracking-wide capitalize">
                                {{ str_replace('_', ' ', $userProfile->userName ?? 'Hostel Student') }}
                            </span>
                            <span class="text-[10px] font-mono text-purple-200 tracking-wider block">
                                {{ $userProfile->userID ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                    <a href="/logout" class="text-purple-200 hover:text-white transition p-1">➔</a>
                </div>
            </div>

            <!-- Dashboard Navigation Tabs -->
            <nav class="flex gap-6 text-xs font-semibold pt-3 pb-1">
                <a href="/student/dashboard" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
                    <span>🏠</span> Dashboard
                </a>
                <a href="/student/rooms" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
                    <span>🛏️</span> Book Room
                </a>
                <a href="/student/bookings" class="text-white border-b-2 border-white pb-2 flex items-center gap-2 opacity-100 font-bold">
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
         📊 MAIN CONTENT VIEWPORT
         ========================================================================= -->
    <main class="max-w-[1600px] mx-auto px-6 py-6 space-y-6">

        <div>
            <h2 class="text-xl font-bold text-slate-800">My Bookings</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Your hostel room bookings this semester</p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-semibold rounded-xl px-4 py-3 shadow-sm">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <!-- BOOKING DETAILS CARD -->
        @if($booking)
            <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm max-w-4xl space-y-6">
                
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">
                            {{ strcasecmp($userProfile->gender ?? 'Male', 'Female') === 0 ? 'Kolej Sutera' : 'Kolej Kasa' }} · Room {{ $booking->roomTargetID }}
                        </h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-0.5">{{ $booking->securedWordLog }} BOOKING</p>
                    </div>
                    <span class="text-[10px] uppercase font-extrabold border px-2.5 py-0.5 rounded-md shadow-sm bg-emerald-50 text-emerald-600 border-emerald-100">
                        ✓ {{ $booking->bookingStatus ?? 'Confirmed' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Column 1: Booking ID -->
                    <div class="bg-slate-50/60 border border-slate-100 p-4 rounded-2xl">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide block">Booking ID</span>
                        <span class="text-xs font-mono font-bold text-slate-700 block mt-1">{{ $booking->logID }}</span>
                    </div>

                    <!-- Column 2: Date Parser -->
                    <div class="bg-slate-50/60 border border-slate-100 p-4 rounded-2xl">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide block">Booked On</span>
                        <span class="text-xs font-mono font-bold text-slate-700 block mt-1">
                            @if(empty($booking->created_at) || strlen($booking->created_at) < 6 || str_contains($booking->created_at, '0000'))
                                08 July 2026
                            @else
                                {{ date('d F Y', strtotime($booking->created_at)) }}
                            @endif
                        </span>
                    </div>

                    <!-- Column 3: Billing Summary Rate (Adjusted to RM 210) -->
                    <div class="bg-slate-50/60 border border-slate-100 p-4 rounded-2xl">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide block">Semester Rate</span>
                        <span class="text-xs font-bold text-purple-700 block mt-1">RM 210 <span class="text-[10px] text-slate-400 font-medium">/ sem</span></span>
                    </div>
                </div>

                <!-- Cancel Booking Workflow -->
                <form action="/student/cancel-booking" method="POST" onsubmit="return confirm('Are you absolutely sure you want to cancel this booking slot? This action will free up bed spaces instantly.');" class="pt-2">
                    @csrf
                    <input type="hidden" name="bookingID" value="{{ $booking->logID }}">
                    <button type="submit" class="w-full border border-rose-200 hover:bg-rose-50 text-rose-600 font-bold text-xs py-3 rounded-2xl transition flex items-center justify-center gap-1.5">
                        ✕ Cancel Booking
                    </button>
                </form>

            </div>
        @else
            <!-- Empty State Fallback Screen -->
            <div class="bg-white rounded-3xl border border-slate-200/60 p-12 text-center max-w-4xl">
                <div class="h-14 w-14 bg-slate-50 border border-slate-200/60 rounded-2xl flex items-center justify-center text-2xl shadow-sm mx-auto mb-4">
                    📅
                </div>
                <h4 class="text-sm font-bold text-slate-700">No active bookings found</h4>
                <p class="text-xs text-slate-400 max-w-xs mx-auto mt-1 font-medium leading-relaxed">
                    You have not completed any residential room allocations for this active semester session cycle.
                </p>
                
                <!-- SECURITY PRIVILEGE PROTECTION LAYER CHECK -->
                <div class="mt-5 flex justify-center">
                    @if(($userProfile->strikeCount ?? 0) >= 3)
                        <a href="/student/eligibility" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl text-[11px] font-bold shadow-sm transition tracking-wide">
                            ⚠️ View Account Restrictions
                        </a>
                    @else
                        <a href="/student/rooms" class="inline-flex px-5 py-2.5 bg-[#5B06B2] hover:bg-[#4A058F] text-white rounded-2xl text-[11px] font-bold shadow-sm transition">
                            Book a Room Now
                        </a>
                    @endif
                </div>
            </div>
        @endif

    </main>
</body>
</html>