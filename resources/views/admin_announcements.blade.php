<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements Management - Admin Suite</title>
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
                    <div class="flex items-center gap-2">
                        <!-- Replace with this dynamic session rendering block -->
<div class="text-right">
    <p class="text-xs font-bold text-white tracking-wide capitalize">{{ $adminProfile->userName ?? 'JPK Kolej Kasa & Sutera' }}</p>
    <p class="text-[10px] text-purple-200 font-mono tracking-wider uppercase">{{ Session::get('user_id', 'ADMIN001') }}</p>
</div>
                    </div>
                    <a href="/logout" class="text-purple-200 hover:text-white transition p-1">➔</a>
                </div>
            </div>

            <nav class="flex gap-6 text-xs font-semibold pt-3 pb-1">
                <a href="/admin/dashboard" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-1.5 opacity-80">📋 Overview</a>
                <a href="/admin/rooms" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-1.5 opacity-80">🔧 Room Management</a>
                <a href="/admin/vacancy" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-1.5 opacity-80">👁️ Room Vacancy</a>
                <a href="/admin/bookings" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-1.5 opacity-80">🎫 All Bookings</a>
                <a href="/admin/announcements" class="text-white border-b-2 border-white pb-2 flex items-center gap-1.5 opacity-100 font-bold">📢 Announcements</a>
            </nav>
        </div>
    </header>

    <main class="max-w-[1600px] mx-auto px-6 py-6 space-y-6">

        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Announcements</h2>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Broadcast notices to all hostel students</p>
            </div>
            <button onclick="toggleAnnouncementModal(true)" class="bg-[#5B06B2] hover:bg-[#4A058F] text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition flex items-center gap-1">
                <span>+</span> New Announcement
            </button>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-semibold rounded-xl px-4 py-3 shadow-sm">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($bulletins as $notice)
                <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm flex items-start gap-4 hover:shadow-md transition relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $notice->is_urgent ? 'bg-rose-500' : 'bg-[#5B06B2]' }}"></div>
                    
                    <div class="h-10 w-10 min-w-[40px] rounded-xl flex items-center justify-center {{ $notice->is_urgent ? 'bg-rose-50 text-rose-500 border border-rose-100' : 'bg-purple-50 text-[#5B06B2] border border-purple-100' }}">
                        <span>{{ $notice->is_urgent ? '⚠️' : '📢' }}</span>
                    </div>

                    <div class="flex-1 space-y-1">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="text-xs font-bold text-slate-800 flex items-center gap-2">
                                <span class="{{ $notice->is_urgent ? 'text-rose-700 font-extrabold' : '' }}">{{ $notice->title }}</span>
                                @if($notice->is_urgent)
                                    <span class="text-[9px] bg-rose-50 text-rose-600 font-black px-1.5 py-0.5 rounded uppercase tracking-wide border border-rose-100">Urgent</span>
                                @endif
                            </h3>
                            
                            <div class="flex items-center gap-4">
                                <span class="text-[10px] font-mono text-slate-400 font-medium">Published {{ date('Y-m-d', strtotime($notice->created_at)) }}</span>
                                
                                <form action="/admin/announcements/delete" method="POST" onsubmit="return confirm('Are you completely sure you want to permanently delete this notice bulletin?');" class="inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $notice->id }}">
                                    <button type="submit" class="text-slate-300 hover:text-rose-600 font-bold text-xs p-1 transition" title="Delete Notice Pass">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">
                            {{ $notice->body }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl border border-slate-200/60 p-12 text-center">
                    <span class="text-3xl block mb-2">📭</span>
                    <h4 class="text-xs font-bold text-slate-700">No announcements found</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">Click 'New Announcement' above to publish an official broadcast stream node.</p>
                </div>
            @endforelse
        </div>
    </main>

    <div id="newNoticeModalBackdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full shadow-xl overflow-hidden border border-slate-100 animate-in zoom-in-95 duration-150">
            <div class="bg-[#5B06B2] text-white p-4 px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-purple-200">Broadcast Suite</h3>
                    <p class="text-sm font-bold mt-0.5">Compose New Official Bulletin Announcement</p>
                </div>
                <button onclick="toggleAnnouncementModal(false)" class="text-purple-200 hover:text-white transition text-lg font-bold">✕</button>
            </div>

            <form action="/admin/announcements/publish" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Notice Title</label>
                    <input type="text" name="title" required placeholder="e.g., Room Allocation Phase 2 Extension" class="w-full bg-slate-50 border border-slate-200 focus:border-purple-400 rounded-xl px-4 py-2.5 text-xs outline-none transition font-medium text-slate-700">
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Notice Body</label>
                    <textarea name="body" rows="4" required placeholder="Type the structural description info or guidance details for students..." class="w-full bg-slate-50 border border-slate-200 focus:border-purple-400 rounded-xl px-4 py-2.5 text-xs outline-none transition font-medium text-slate-700 leading-relaxed"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="is_urgent" name="is_urgent" class="rounded border-slate-300 text-[#5B06B2] focus:ring-[#5B06B2] h-3.5 w-3.5 cursor-pointer">
                    <label id="is_urgent" for="is_urgent" class="text-[11px] font-bold text-rose-600 select-none cursor-pointer uppercase tracking-wide">Mark Notice Flag As Urgent Bulletin</label>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="toggleAnnouncementModal(false)" class="border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs font-bold py-3 rounded-2xl transition">Cancel</button>
                    <button type="submit" class="bg-[#5B06B2] hover:bg-[#4A058F] text-white text-xs font-bold py-3 rounded-2xl shadow-sm transition">Publish Announcement</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleAnnouncementModal(shouldOpen) {
            const backdrop = document.getElementById('newNoticeModalBackdrop');
            if (shouldOpen) {
                backdrop.classList.remove('hidden');
                backdrop.classList.add('flex');
            } else {
                backdrop.classList.add('hidden');
                backdrop.classList.remove('flex');
            }
        }
    </script>
</body>
</html>