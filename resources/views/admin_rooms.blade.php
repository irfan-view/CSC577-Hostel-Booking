<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Room Management</title>
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
                <a href="/admin/rooms" class="text-white border-b-2 border-white pb-2 flex items-center gap-2 opacity-100">
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
        
        <div>
            <h2 class="text-xl font-bold text-slate-800">Room Management</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Manage maintenance and reservations for Kolej Kasa & Kolej Sutera</p>
        </div>

        <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2 text-purple-950 font-bold text-sm">
                    <span class="text-amber-500">🛡️</span> Reserve Rooms
                </div>
                <span class="text-[11px] font-medium text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg">Select floor → select rooms → apply</span>
            </div>

            @if(session('success'))
                <div class="bg-[#EAFBF3] border border-[#BFF3DB] text-[#10B981] text-xs font-semibold rounded-xl px-4 py-3 flex items-center gap-2 shadow-sm animate-in fade-in duration-200">
                    <span class="text-sm">✓</span> {{ session('success') }}
                </div>
            @endif
            
            <div class="text-xs space-y-3">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Step 1 — Choose Hostel & Floor</p>
                <div class="flex flex-wrap gap-6 items-center bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400 font-semibold">Hostel:</span>
                        <button type="button" onclick="setHostel('Kolej Kasa')" id="h-btn-kasa" class="bg-[#5B06B2] text-white font-bold px-3 py-1.5 rounded-xl transition shadow-sm">Kolej Kasa</button>
                        <button type="button" onclick="setHostel('Kolej Sutera')" id="h-btn-sutera" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold px-3 py-1.5 rounded-xl transition">Kolej Sutera</button>
                    </div>
                    <div class="flex items-center gap-2 border-l border-slate-200 pl-6">
                        <span class="text-slate-400 font-semibold">Floor:</span>
                        <div class="flex gap-1.5">
                            @for($f = 0; $f <= 3; $f++)
                                <button type="button" onclick="setFloor({{ $f }})" id="f-btn-{{ $f }}" class="floor-tab bg-white border border-slate-200 text-slate-600 font-bold px-3 py-1.5 rounded-xl transition shadow-sm">{{ $f === 0 ? 'Floor 0' : 'Floor '.$f }}</button>
                            @endfor
                        </div>
                    </div>
                    <button type="button" onclick="clearSelection()" class="ml-auto text-[11px] font-bold text-slate-400 hover:text-slate-600 bg-white border border-slate-200 px-3 py-1.5 rounded-xl transition shadow-sm">Clear</button>
                </div>
            </div>

            <form action="/admin/rooms/reserve" method="POST" id="batchActionForm" class="hidden space-y-6">
                @csrf
                <input type="hidden" name="hostel" id="hiddenHostel" value="Kolej Kasa">
                <input type="hidden" name="floor" id="hiddenFloor" value="">
                <input type="hidden" name="selected_rooms" id="hiddenRoomsInput" value="">
                <input type="hidden" name="action_type" id="hiddenActionType" value="reserve_selected">

                <div id="step2Container" class="text-xs space-y-4 border-t border-slate-100 pt-5">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                            Step 2 — Select Rooms (<span id="activeSelectionLabel" class="text-slate-600 font-bold"></span>)
                        </p>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-2 text-slate-600 font-bold cursor-pointer bg-slate-50 px-3 py-1.5 border border-slate-200/60 rounded-xl">
                                <input type="checkbox" id="selectAllFloorCheckbox" onchange="toggleSelectAllFloor(this)" class="w-3.5 h-3.5 rounded text-purple-600 border-slate-300 focus:ring-purple-500">
                                <span>Select all floor</span>
                            </label>
                            <span class="text-xs font-bold text-[#5B06B2] bg-purple-50 px-3 py-1.5 border border-purple-100 rounded-xl">
                                <span id="selectedCounter">0</span> selected
                            </span>
                        </div>
                    </div>

                    <div class="space-y-2 bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-bold text-blue-600 flex items-center gap-1.5">🔵 Wing A <span class="text-[10px] text-slate-400 font-normal">(rooms 01–23)</span></span>
                            <button type="button" onclick="toggleSelectWing('Wing A')" class="text-[11px] font-bold text-purple-700 hover:text-purple-900 bg-white border border-purple-100 px-2.5 py-1 rounded-lg shadow-sm transition">Select Entire Wing A</button>
                        </div>
                        <div id="wingA_Grid" class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-12 gap-2"></div>
                    </div>

                    <div class="space-y-2 bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-bold text-orange-600 flex items-center gap-1.5">🟠 Wing B <span class="text-[10px] text-slate-400 font-normal">(rooms 24–47)</span></span>
                            <button type="button" onclick="toggleSelectWing('Wing B')" class="text-[11px] font-bold text-purple-700 hover:text-purple-900 bg-white border border-purple-100 px-2.5 py-1 rounded-lg shadow-sm transition">Select Entire Wing B</button>
                        </div>
                        <div id="wingB_Grid" class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-12 gap-2"></div>
                    </div>

                    <div class="flex flex-wrap gap-4 text-[10px] font-bold text-slate-500 pt-1">
                        <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-[#5B06B2] rounded"></div> Selected</div>
                        <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-[#EAFBF3] border border-[#BFF3DB] rounded"></div> Available</div>
                        <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-[#FFF9E6] border border-[#FFEAA6] rounded"></div> Reserved</div>
                        <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-[#FFF0F0] border border-[#FFD1D1] rounded"></div> Occupied</div>
                        <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-[#F1F5F9] border border-[#E2E8F0] rounded"></div> Maintenance</div>
                    </div>
                </div>

                <div id="step3Container" class="border-t border-slate-100 pt-5 space-y-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Step 3 — Reason & Apply</p>
                    <input type="text" name="reason" id="reservationReason" value="PALAPES / Komander Kesatria" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-purple-600 transition">
                    
                    <div class="flex flex-wrap gap-3 pt-2">
                        <button type="button" id="btnReserveSelected" disabled onclick="submitFormAction('reserve_selected')" class="bg-amber-100 text-amber-400 font-bold text-xs px-4 py-3 rounded-xl cursor-not-allowed transition">🔒 Reserve Selected (0)</button>
                        <button type="button" onclick="submitFormAction('reserve_floor')" class="bg-[#A84600] hover:bg-[#8F3B00] text-white font-bold text-xs px-4 py-3 rounded-xl shadow-sm transition">🛡️ Reserve Entire Floor</button>
                        <button type="button" id="btnUnreserveSelected" disabled onclick="submitFormAction('unreserve_selected')" class="bg-slate-100 text-slate-400 font-bold text-xs px-4 py-3 rounded-xl cursor-not-allowed transition">🔓 Unreserve Selected</button>
                        <button type="button" onclick="submitFormAction('unreserve_floor')" class="bg-white border border-purple-200 text-purple-700 hover:bg-purple-50 font-bold text-xs px-4 py-3 rounded-xl shadow-sm transition">🔓 Unreserve Entire Floor</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/60 p-5 shadow-sm">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4">All Rooms Datatable Ledger</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                            <th class="p-3">Room ID</th>
                            <th class="p-3">Hostel</th>
                            <th class="p-3">Wing</th>
                            <th class="p-3">Occupancy</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                        @foreach($rooms as $room)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3 font-bold font-mono text-slate-900">{{ $room->roomID }}</td>
                                <td class="p-3">{{ $room->buildingName }}</td>
                                <td class="p-3">{{ $room->wing }}</td>
                                <td class="p-3">{{ $room->currentOccupancy }}/4 Beds</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold {{ $room->currentOccupancy >= 4 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                                        {{ $room->currentOccupancy >= 4 ? 'Full' : 'Available' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        const globalRoomsRegistry = @json($rooms);
        
        let targetHostel = 'Kolej Kasa';
        let targetFloor = null;
        let selectedRoomIDs = new Set();

        function setHostel(name) {
            targetHostel = name;
            document.getElementById('hiddenHostel').value = name;
            document.getElementById('h-btn-kasa').className = name === 'Kolej Kasa' ? "bg-[#5B06B2] text-white font-bold px-3 py-1.5 rounded-xl transition shadow-sm" : "bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold px-3 py-1.5 rounded-xl transition";
            document.getElementById('h-btn-sutera').className = name === 'Kolej Sutera' ? "bg-[#5B06B2] text-white font-bold px-3 py-1.5 rounded-xl transition shadow-sm" : "bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold px-3 py-1.5 rounded-xl transition";
            if(targetFloor !== null) renderInteractiveSection();
        }

        function setFloor(floorNum) {
            targetFloor = floorNum;
            document.getElementById('hiddenFloor').value = floorNum;
            document.querySelectorAll('.floor-tab').forEach(btn => btn.className = "floor-tab bg-white border border-slate-200 text-slate-600 font-bold px-3 py-1.5 rounded-xl transition shadow-sm");
            document.getElementById('f-btn-' + floorNum).className = "floor-tab bg-[#5B06B2] text-white font-bold px-3 py-1.5 rounded-xl transition shadow-sm";
            
            selectedRoomIDs.clear();
            document.getElementById('selectAllFloorCheckbox').checked = false;
            
            document.getElementById('batchActionForm').classList.remove('hidden');
            renderInteractiveSection();
        }

        function clearSelection() {
            targetFloor = null;
            selectedRoomIDs.clear();
            document.querySelectorAll('.floor-tab').forEach(btn => btn.className = "floor-tab bg-white border border-slate-200 text-slate-600 font-bold px-3 py-1.5 rounded-xl transition shadow-sm");
            document.getElementById('batchActionForm').classList.add('hidden');
        }

        function renderInteractiveSection() {
            if(targetFloor === null) return;

            document.getElementById('activeSelectionLabel').innerText = `${targetHostel} · Floor ${targetFloor} · 47 rooms`;

            const prefix = targetHostel === 'Kolej Kasa' ? 'K' : 'S';
            const activePool = globalRoomsRegistry.filter(r => r.buildingName === targetHostel && r.roomID.startsWith(prefix + targetFloor));

            const gridA = document.getElementById('wingA_Grid');
            const gridB = document.getElementById('wingB_Grid');
            gridA.innerHTML = '';
            gridB.innerHTML = '';

            activePool.forEach(room => {
                let bgClass = "bg-[#EAFBF3] border-[#BFF3DB] text-[#10B981]"; 
                if (room.currentOccupancy > 0 && room.currentOccupancy < 4) bgClass = "bg-[#FFF9E6] border-[#FFEAA6] text-[#F59E0B]";
                if (room.currentOccupancy >= 4) bgClass = "bg-[#FFF0F0] border-[#FFD1D1] text-[#EF4444]";

                if (selectedRoomIDs.has(room.roomID)) {
                    bgClass = "bg-[#5B06B2] border-[#5B06B2] text-white font-extrabold shadow-sm";
                }

                const chip = document.createElement('button');
                chip.type = "button";
                chip.className = `border text-[11px] font-bold py-2 rounded-xl transition text-center duration-100 ${bgClass}`;
                chip.innerText = room.roomID;
                chip.onclick = () => toggleRoomChipSelection(room.roomID);

                chip.setAttribute('data-id', room.roomID);
                chip.setAttribute('data-wing', room.wing);

                if (room.wing === 'Wing A') gridA.appendChild(chip);
                else gridB.appendChild(chip);
            });

            updateSelectionCounters();
        }

        function toggleRoomChipSelection(roomId) {
            if (selectedRoomIDs.has(roomId)) selectedRoomIDs.delete(roomId);
            else selectedRoomIDs.add(roomId);
            renderInteractiveSection();
        }

        function toggleSelectWing(wingName) {
            const prefix = targetHostel === 'Kolej Kasa' ? 'K' : 'S';
            const chips = globalRoomsRegistry.filter(r => r.buildingName === targetHostel && r.roomID.startsWith(prefix + targetFloor) && r.wing === wingName);
            
            let allSelected = true;
            chips.forEach(c => { if(!selectedRoomIDs.has(c.roomID)) allSelected = false; });

            chips.forEach(c => {
                if (allSelected) selectedRoomIDs.delete(c.roomID);
                else selectedRoomIDs.add(c.roomID);
            });
            renderInteractiveSection();
        }

        function toggleSelectAllFloor(checkbox) {
            const prefix = targetHostel === 'Kolej Kasa' ? 'K' : 'S';
            const activePool = globalRoomsRegistry.filter(r => r.buildingName === targetHostel && r.roomID.startsWith(prefix + targetFloor));
            if (checkbox.checked) {
                activePool.forEach(r => selectedRoomIDs.add(r.roomID));
            } else {
                selectedRoomIDs.clear();
            }
            renderInteractiveSection();
        }

        function updateSelectionCounters() {
            const total = selectedRoomIDs.size;
            document.getElementById('selectedCounter').innerText = total;
            document.getElementById('hiddenRoomsInput').value = Array.from(selectedRoomIDs).join(',');
            
            const btnReserve = document.getElementById('btnReserveSelected');
            const btnUnreserve = document.getElementById('btnUnreserveSelected');
            
            if(total > 0) {
                btnReserve.disabled = false;
                btnReserve.innerText = `🛡️ Reserve Selected (${total})`;
                btnReserve.className = "bg-[#D97706] hover:bg-[#B45309] text-white font-bold text-xs px-4 py-3 rounded-xl shadow-sm transition cursor-pointer";
                
                btnUnreserve.disabled = false;
                btnUnreserve.className = "bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-xs px-4 py-3 rounded-xl shadow-sm transition cursor-pointer";
            } else {
                btnReserve.disabled = true;
                btnReserve.innerText = `🔒 Reserve Selected (0)`;
                btnReserve.className = "bg-amber-100 text-amber-400 font-bold text-xs px-4 py-3 rounded-xl cursor-not-allowed transition";
                
                btnUnreserve.disabled = true;
                btnUnreserve.className = "bg-slate-50 border border-slate-200 text-slate-400 font-bold text-xs px-4 py-3 rounded-xl cursor-not-allowed transition";
            }
        }

        function submitFormAction(actionType) {
            document.getElementById('hiddenActionType').value = actionType;
            document.getElementById('batchActionForm').submit();
        }
    </script>
</body>
</html>