<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Announcements Broadcaster</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans min-h-screen">

    <header class="bg-[#5B06B2] text-white shadow-sm sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-6">
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
                        <p class="text-xs font-bold text-white tracking-wide">JPK Kolej Kasa & Sutera</p>
                        <p class="text-[10px] text-purple-200 font-mono tracking-wider">ADMIN001</p>
                    </div>
                    <a href="/logout" class="text-purple-200 hover:text-white transition p-1">➔</a>
                </div>
            </div>

            <nav class="flex gap-6 text-xs font-semibold pt-3 pb-1">
                <a href="/admin/dashboard" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
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

    <main class="max-w-[1600px] mx-auto px-6 py-6 space-y-6">

        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Announcements</h2>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Broadcast notices to all hostel students</p>
            </div>
            <button onclick="toggleComposerForm()" class="bg-[#5B06B2] hover:bg-[#4A058F] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition flex items-center gap-1.5">
                <span>+</span> New Announcement
            </button>
        </div>

        @if(session('success'))
            <div class="bg-[#EAFBF3] border border-[#BFF3DB] text-[#10B981] text-xs font-semibold rounded-xl px-4 py-3 shadow-sm">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif

        <div id="announcementComposerCard" class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm hidden transition duration-200">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 mb-4">Compose Announcement</h3>
            
            <form action="/admin/announcements/publish" method="POST" class="space-y-4">
                @csrf
                <div>
                    <input type="text" name="title" required placeholder="Announcement title..." class="w-full bg-slate-50 border border-slate-200 focus:border-purple-400 rounded-xl px-4 py-3 text-xs font-medium text-slate-700 outline-none transition">
                </div>
                <div>
                    <textarea name="body" required rows="4" placeholder="Announcement body..." class="w-full bg-slate-50 border border-slate-200 focus:border-purple-400 rounded-xl px-4 py-3 text-xs font-medium text-slate-700 outline-none transition resize-none"></textarea>
                </div>
                
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 pt-1">
                    <input type="checkbox" name="is_urgent" id="markUrgentInput" class="w-3.5 h-3.5 text-purple-600 border-slate-300 rounded focus:ring-purple-500">
                    <label for="markUrgentInput" class="cursor-pointer select-none">Mark as Urgent</label>
                </div>

                <div class="flex gap-2 justify-end pt-2 border-t border-slate-50">
                    <button type="button" onclick="toggleComposerForm()" class="bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs font-bold px-4 py-2.5 rounded-xl transition">Cancel</button>
                    <button type="submit" class="bg-[#5B06B2] hover:bg-[#4A058F] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition flex items-center gap-1.5">
                        <span>🚀</span> Publish
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-4">
            @forelse($bulletins as $item)
                <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm flex items-start gap-4 relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $item->is_urgent ? 'bg-rose-500' : 'bg-purple-600' }}"></div>
                    
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm border shadow-sm shrink-0 {{ $item->is_urgent ? 'bg-rose-50 border-rose-100 text-rose-500' : 'bg-purple-50 border-purple-100 text-purple-500' }}">
                        {{ $item->is_urgent ? '⚠️' : '📢' }}
                    </div>

                    <div class="space-y-1 w-full">
                        <div class="flex justify-between items-center flex-wrap gap-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-sm font-bold text-slate-800">{{ $item->title }}</h4>
                                @if($item->is_urgent)
                                    <span class="text-[9px] bg-rose-50 text-rose-600 font-extrabold border border-rose-100 px-2 py-0.5 rounded-md uppercase tracking-wider">Urgent</span>
                                @endif
                            </div>
                            <span class="text-[10px] text-slate-400 font-mono">Published {{ date('Y-m-d', strtotime($item->created_at)) }}</span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed pt-1">{{ $item->body }}</p>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl border border-slate-200/60 p-8 text-center text-slate-400 text-xs font-medium">
                    📢 No official college notices have been broadcasted yet.
                </div>
            @endforelse
        </div>

    </main>

    <script>
        function toggleComposerForm() {
            const card = document.getElementById('announcementComposerCard');
            if (card.classList.contains('hidden')) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        }
    </script>
</body>
</html>