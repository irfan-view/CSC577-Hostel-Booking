<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Bookings - Admin Suite</title>
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
                    <!-- Replace with this dynamic session rendering block -->
<div class="text-right">
    <p class="text-xs font-bold text-white tracking-wide capitalize">{{ $adminProfile->userName ?? 'JPK Kolej Kasa & Sutera' }}</p>
    <p class="text-[10px] text-purple-200 font-mono tracking-wider uppercase">{{ Session::get('user_id', 'ADMIN001') }}</p>
</div>
                    <a href="/logout" class="text-purple-200 hover:text-white transition p-1">➔</a>
                </div>
            </div>

            <nav class="flex gap-6 text-xs font-semibold pt-3 pb-1">
                <a href="/admin/dashboard" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-1.5 opacity-80">📋 Overview</a>
                <a href="/admin/rooms" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-1.5 opacity-80">🔧 Room Management</a>
                <a href="/admin/vacancy" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-1.5 opacity-80">👁️ Room Vacancy</a>
                <a href="/admin/bookings" class="text-white border-b-2 border-white pb-2 flex items-center gap-1.5 opacity-100 font-bold">🎫 All Bookings</a>
                <a href="/admin/announcements" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-1.5 opacity-80">📢 Announcements</a>
            </nav>
        </div>
    </header>

    <main class="max-w-[1600px] mx-auto px-6 py-6 space-y-6">

        <div>
            <h2 class="text-xl font-bold text-slate-800">All Bookings</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">{{ count($records) }} total booking records</p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-semibold rounded-xl px-4 py-3 shadow-sm animate-in fade-in duration-200">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 shadow-sm flex items-center gap-3">
            <span class="text-slate-400 text-xs pl-1">🔍</span>
            <input type="text" id="bookingSearchInput" onkeyup="filterBookingsTable()" placeholder="Search by booking ID, student ID, name, or room..." class="w-full bg-transparent outline-none text-xs font-medium text-slate-700 placeholder-slate-400">
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/60 p-5 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse" id="adminBookingsLedgerTable">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                            <th class="p-3">Booking ID</th>
                            <th class="p-3">Student</th>
                            <th class="p-3">Room</th>
                            <th class="p-3">Type</th>
                            <th class="p-3">Date</th>
                            <th class="p-3">Status / Reason</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                        @forelse($records as $row)
                            <tr class="hover:bg-slate-50/50 transition row-record-node">
                                <td class="p-3 font-bold font-mono text-slate-900 tracking-wide target-search-id">{{ $row->logID }}</td>
                                <td class="p-3">
                                    <div class="font-bold text-slate-800 target-search-name capitalize">{{ $row->userName }}</div>
                                    <div class="text-[10px] font-mono text-slate-400 mt-0.5 target-search-uid">{{ $row->userID }}</div>
                                </td>
                                <td class="p-3">
                                    <div class="font-bold text-slate-700 font-mono target-search-room">{{ $row->roomTargetID }}</div>
                                    <div class="text-[10px] text-slate-400">Kolej Kasa</div>
                                </td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded-md font-bold text-[10px] uppercase {{ $row->securedWordLog === 'GROUP' ? 'bg-purple-50 text-[#5B06B2] border border-purple-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                                        👥 {{ $row->securedWordLog }}
                                    </span>
                                </td>
                                <td class="p-3 font-mono text-slate-400">{{ date('d M', strtotime($row->created_at)) }}</td>
                                <td class="p-3">
                                    @if(str_contains(strtolower($row->bookingStatus), 'cancelled'))
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-rose-50 text-rose-600 border border-rose-100 block w-max max-w-xs truncate" title="{{ $row->bookingStatus }}">
                                            {{ $row->bookingStatus }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            ✓ Confirmed
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    @if(!str_contains(strtolower($row->bookingStatus), 'cancelled'))
                                        <button type="button" onclick="openCancellationModal('{{ $row->logID }}', '{{ $row->roomTargetID }}', '{{ $row->userName }}')" class="border border-rose-200 bg-rose-50/30 hover:bg-rose-50 text-rose-600 font-bold text-[11px] px-3 py-1.5 rounded-xl transition">
                                            🗑️ Cancel
                                        </button>
                                    @else
                                        <span class="text-slate-300 font-mono text-sm pr-4">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400 font-medium">
                                    📭 No operational bedroom allocation logs present in the database ledger.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="cancelReasonModalBackdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full shadow-xl border border-slate-100 overflow-hidden animate-in zoom-in-95 duration-150">
            
            <div class="bg-rose-600 text-white p-4 px-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-rose-200">Revocation Suite</h3>
                <p class="text-sm font-bold mt-0.5">Revoke Allocation Pass: <span id="modalTargetBookingId" class="font-mono bg-rose-700 px-1.5 py-0.5 rounded"></span></p>
            </div>

            <form action="/admin/bookings/cancel" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="modalHiddenLogId" name="logID" value="">
                <input type="hidden" id="modalHiddenRoomId" name="roomID" value="">

                <div class="bg-slate-50 border border-slate-100 p-3 rounded-xl text-xs text-slate-500 font-medium leading-relaxed">
                    You are revoking the active bed space token assigned to <span id="modalTargetStudentName" class="font-bold text-slate-700 capitalize"></span> inside bedroom room layout index <span id="modalTargetRoomId" class="font-bold text-slate-700 font-mono"></span>.
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Reason for Cancellation</label>
                    <input type="text" name="cancellation_reason" id="modalInputReason" required placeholder="e.g., Student withdrew / Violated hostel code strike" class="w-full bg-slate-50 border border-slate-200 focus:border-rose-400 rounded-xl px-4 py-2.5 text-xs outline-none transition font-medium text-slate-700">
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeCancellationModal()" class="border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs font-bold py-3 rounded-2xl transition">Dismiss</button>
                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold py-3 rounded-2xl shadow-sm transition">Revoke Booking</button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function openCancellationModal(logId, roomId, studentName) {
            document.getElementById('modalHiddenLogId').value = logId;
            document.getElementById('modalHiddenRoomId').value = roomId;
            
            document.getElementById('modalTargetBookingId').innerText = logId;
            document.getElementById('modalTargetStudentName').innerText = studentName;
            document.getElementById('modalTargetRoomId').innerText = roomId;
            
            document.getElementById('modalInputReason').value = ''; // Reset input field
            
            const backdrop = document.getElementById('cancelReasonModalBackdrop');
            backdrop.classList.remove('hidden');
            backdrop.classList.add('flex');
        }

        function closeCancellationModal() {
            const backdrop = document.getElementById('cancelReasonModalBackdrop');
            backdrop.classList.add('hidden');
            backdrop.classList.remove('flex');
        }

        function filterBookingsTable() {
            let filter = document.getElementById('bookingSearchInput').value.toLowerCase().trim();
            let rows = document.querySelectorAll('.row-record-node');

            rows.forEach(row => {
                let id = row.querySelector('.target-search-id').innerText.toLowerCase();
                let name = row.querySelector('.target-search-name').innerText.toLowerCase();
                let uid = row.querySelector('.target-search-uid').innerText.toLowerCase();
                let room = row.querySelector('.target-search-room').innerText.toLowerCase();

                if (id.includes(filter) || name.includes(filter) || uid.includes(filter) || room.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
</body>
</html>