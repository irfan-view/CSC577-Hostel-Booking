<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - UiTM KT Hostel</title>
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
                            @if(str_starts_with(Session::get('user_id', '20246'), '20246'))
                                Kolej Kasa (Male)
                            @else
                                Kolej Sutera (Female)
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-5">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-orange-400 animate-pulse"></div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-white tracking-wide">Ahmad Irfan</p>
                            <p class="text-[10px] text-purple-200 font-mono tracking-wider">{{ Session::get('user_id', '2024669856') }}</p>
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
                <a href="/student/eligibility" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
                    <span>📋</span> Eligibility
                </a>
                <a href="/student/announcements" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
    <span>📢</span> Announcements
</a>
            </nav>
        </div>
    </header>

    <!-- MAIN BULLETIN LOG FEED VIEWPORT -->
    <main class="max-w-[1600px] mx-auto px-6 py-6 space-y-6">
        
        <div>
            <h2 class="text-xl font-bold text-slate-800">Announcements</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Official notices from Hostel Management</p>
        </div>

        <!-- DYNAMIC NOTIFICATION CARD LIST STACK -->
        <div class="space-y-4 max-w-5xl">
            @forelse($announcements as $item)
                @if($item->is_urgent)
                    <!-- Urgent Announcement Card Block -->
                    <div class="bg-white border border-rose-200/80 rounded-3xl p-5 shadow-sm flex gap-4 items-start relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500"></div>
                        <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-500 text-sm shrink-0">
                            ❗
                        </div>
                        <div class="space-y-1.5 w-full">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-sm font-bold text-slate-800">{{ $item->title }}</h4>
                                <span class="text-[9px] bg-rose-100 text-rose-700 font-extrabold px-2 py-0.5 rounded-md uppercase tracking-wide">Urgent</span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed">
                                {{ $item->body }}
                            </p>
                            <div class="flex items-center gap-1 text-[10px] text-slate-400 font-medium font-mono pt-0.5">
                                <span>🕒</span> {{ date('Y-m-d', strtotime($item->created_at)) }}
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Regular Announcement Card Block -->
                    <div class="bg-white border border-slate-200/60 rounded-3xl p-5 shadow-sm flex gap-4 items-start relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-purple-600"></div>
                        <div class="w-10 h-10 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#5B06B2] text-sm shrink-0">
                            📢
                        </div>
                        <div class="space-y-1.5 w-full">
                            <h4 class="text-sm font-bold text-slate-800">{{ $item->title }}</h4>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed">
                                {{ $item->body }}
                            </p>
                            <div class="flex items-center gap-1 text-[10px] text-slate-400 font-medium font-mono pt-0.5">
                                <span>🕒</span> {{ date('Y-m-d', strtotime($item->created_at)) }}
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <!-- Empty Placeholder State -->
                <div class="bg-white rounded-3xl border border-slate-200/60 p-8 text-center text-slate-400 text-xs font-medium">
                    📢 No official college notices have been broadcasted yet.
                </div>
            @endforelse
        </div>

    </main>

</body>
</html>