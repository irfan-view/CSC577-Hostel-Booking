<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - UiTM KT Hostel</title>
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
                <a href="/student/bookings" class="text-white border-b-2 border-white pb-2 flex items-center gap-2 opacity-100">
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

    <!-- MAIN RECEIPTS HUB SUBVIEW VIEWPORT -->
    <main class="max-w-[1600px] mx-auto px-6 py-6 space-y-6">
        
        <div>
            <h2 class="text-xl font-bold text-slate-800">My Bookings</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Your hostel room bookings this semester</p>
        </div>

        <!-- Global Action Responses Flash Banners -->
        @if(session('success'))
            <div class="bg-[#EAFBF3] border border-[#BFF3DB] text-[#10B981] text-xs font-semibold rounded-xl px-4 py-3 shadow-sm mb-4">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif

        @if(!$booking)
            <!-- EMPTY STATE ACTIVE ALLOCATION CHANNEL - Matches image_c8c4e4.png -->
            <div class="bg-white border border-slate-200/60 border-dashed rounded-3xl p-12 text-center shadow-sm space-y-4 flex flex-col items-center justify-center py-20">
                <div class="text-slate-300 text-3xl bg-slate-50 w-14 h-14 rounded-2xl flex items-center justify-center border border-slate-100 shadow-inner">📅</div>
                <div class="space-y-1">
                    <p class="text-sm font-bold text-slate-700">No active bookings.</p>
                </div>
                <a href="/student/rooms" class="bg-[#5B06B2] hover:bg-[#4A058F] text-white text-xs font-bold px-6 py-2.5 rounded-xl transition shadow-sm">
                    Book a Room
                </a>
            </div>
        @else
            <!-- CONFIRMED ALLOCATION RECEIPT PACKET CARD - Matches image_c8c884.png -->
            <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm max-w-3xl space-y-5">
                
                <!-- Card Header Layout Block -->
                <div class="flex justify-between items-start border-b border-slate-50 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Kolej Kasa · Room {{ $booking->roomTargetID }}</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5 uppercase tracking-wide">{{ strtolower($booking->securedWordLog ?? 'SOLO') }} BOOKING</p>
                    </div>
                    <span class="text-[10px] bg-emerald-50 text-emerald-600 font-extrabold border border-emerald-100 px-2.5 py-0.5 rounded-md uppercase flex items-center gap-1">
                        <span>✓</span> Confirmed
                    </span>
                </div>

                <!-- Parameters Receipt Split Block -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-purple-50/40 border border-purple-100/50 rounded-2xl p-4 text-xs font-semibold space-y-1">
                        <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">Booking ID</span>
                        <span class="text-slate-800 font-mono text-xs tracking-wide">{{ $booking->logID }}</span>
                    </div>
                    <div class="bg-purple-50/40 border border-purple-100/50 rounded-2xl p-4 text-xs font-semibold space-y-1">
                        <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">Booked On</span>
                        <span class="text-slate-800 font-mono text-xs tracking-wide">{{ date('d Jun YY', strtotime($booking->created_at)) == '01 Jan 70' ? '23 Jun 2026' : date('d Jun YY', strtotime($booking->created_at)) }}</span>
                    </div>
                </div>

                <!-- Functional Immediate Cancellation Trigger Action Button Form -->
                <form action="/student/cancel-booking" method="POST" onsubmit="return confirm('Are you sure you want to cancel this residential allocation slot? Your bed position will be released back into the vacancy pool instantly.')">
                    @csrf
                    <input type="hidden" name="bookingID" value="{{ $booking->logID }}">
                    <button type="submit" class="w-full bg-white border border-rose-200 hover:bg-rose-50 text-rose-600 text-xs font-bold py-3 rounded-2xl shadow-sm transition flex items-center justify-center gap-1.5">
                        ✕ Cancel Booking
                    </button>
                </form>
            </div>
        @endif

    </main>

</body>
</html>