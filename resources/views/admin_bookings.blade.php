<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Booking Records Ledger</title>
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
                <a href="/admin/bookings" class="text-white border-b-2 border-white pb-2 flex items-center gap-2 opacity-100">
                    <span>📅</span> All Bookings
                </a>
                <!-- Find this inside resources/views/admin_bookings.blade.php -->
<a href="/admin/announcements" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
    <span>📢</span> Announcements
</a>
            </nav>
        </div>
    </header>

    <main class="max-w-[1600px] mx-auto px-6 py-6 space-y-6">

        <div>
            <h2 class="text-xl font-bold text-slate-800">All Bookings</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5"><span id="totalCountLabel">{{ count($records) }}</span> total booking records</p>
        </div>

        @if(session('success'))
            <div class="bg-[#EAFBF3] border border-[#BFF3DB] text-[#10B981] text-xs font-semibold rounded-xl px-4 py-3 flex items-center gap-2 shadow-sm">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-slate-200/60 p-2 rounded-2xl shadow-sm flex items-center gap-3 px-4 focus-within:ring-2 focus-within:ring-purple-600/20 transition">
            <span class="text-slate-400 text-sm">🔍</span>
            <input type="text" id="ledgerSearchInput" onkeyup="liveSearchLedger()" placeholder="Search by booking ID, student ID, name, or room..." class="w-full bg-transparent border-none text-xs text-slate-700 outline-none placeholder-slate-400 py-2">
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/60 p-5 shadow-sm overflow-hidden">
            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                            <th class="p-3.5">Booking ID</th>
                            <th class="p-3.5">Student</th>
                            <th class="p-3.5">Room</th>
                            <th class="p-3.5">Type</th>
                            <th class="p-3.5">Date</th>
                            <th class="p-3.5">Status / Reason</th>
                            <th class="p-3.5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="ledgerTableBody" class="divide-y divide-slate-100 font-medium text-slate-600">
                        @forelse($records as $row)
                            <tr class="ledger-row hover:bg-slate-50/40 transition duration-75">
                                <td class="p-3.5 font-bold font-mono text-slate-900 tracking-wide">{{ $row->logID }}</td>
                                <td class="p-3.5">
                                    <div class="leading-tight">
                                        <p class="font-bold text-slate-800">{{ $row->userName }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $row->userID }}</p>
                                    </div>
                                </td>
                                <td class="p-3.5 text-slate-700 font-semibold">
                                    {{ $row->roomTargetID }} <span class="text-[10px] font-normal text-slate-400 block mt-0.5">{{ str_contains($row->roomTargetID, 'K') ? 'Kolej Kasa' : 'Kolej Sutera' }}</span>
                                </td>
                                <td class="p-3.5">
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg border flex items-center gap-1 w-max uppercase {{ $row->securedWordLog === 'SOLO' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-purple-50 text-purple-600 border-purple-100' }}">
                                        <span>{{ $row->securedWordLog === 'SOLO' ? '👥' : '👥👥' }}</span> {{ strtolower($row->securedWordLog) }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-slate-500 font-mono">{{ date('d M', strtotime($row->created_at)) }}</td>
                                <td class="p-3.5">
                                    <span class="text-[10px] uppercase font-extrabold border px-2 py-0.5 rounded-md shadow-sm {{ $row->bookingStatus === 'Confirmed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                                        {{ $row->bookingStatus }}
                                    </span>
                                </td>
                                <td class="p-3.5">
                                    <div class="flex justify-center">
                                        @if($row->bookingStatus === 'Confirmed')
                                            <form action="/admin/bookings/cancel" method="POST" onsubmit="return confirm('Are you sure you want to administratively cancel this student reservation pass?')">
                                                @csrf
                                                <input type="hidden" name="logID" value="{{ $row->logID }}">
                                                <input type="hidden" name="roomID" value="{{ $row->roomTargetID }}">
                                                <button type="submit" class="bg-white border border-rose-200 hover:bg-rose-50 text-rose-600 font-bold text-[10px] px-3 py-1.5 rounded-xl shadow-sm transition">
                                                    🗑️ Cancel
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-slate-300">—</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400 text-xs">🔒 No student booking transaction records present inside the allocation database registry.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        function liveSearchLedger() {
            const query = document.getElementById('ledgerSearchInput').value.toLowerCase().trim();
            const rows = document.querySelectorAll('.ledger-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('totalCountLabel').innerText = visibleCount;
        }
    </script>
</body>
</html>