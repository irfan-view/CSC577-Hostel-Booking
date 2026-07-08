<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Rooms - UiTM KT Hostel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans min-h-screen">

    <!-- HEADER BAR BRANDING -->
    <header class="bg-[#5B06B2] text-white shadow-sm sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-6">
            <div class="flex justify-between items-center py-4 border-b border-purple-500/30">
                <div class="flex items-center gap-3">
                    <div class="border-2 border-white/80 rounded-xl p-1.5 flex items-center justify-center">
                        <span class="text-sm font-semibold tracking-wider">🏢</span>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold tracking-wide">UiTM KT Hostel</h1>
                        <p id="systemHeaderHostelLabel" class="text-[10px] text-purple-200/80 font-medium">
                            {{ strcasecmp($userProfile->gender ?? 'Male', 'Female') === 0 ? 'Kolej Sutera (Female)' : 'Kolej Kasa (Male)' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-5">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full {{ ($userProfile->strikeCount ?? 0) >= 3 ? 'bg-rose-400' : 'bg-emerald-400' }} animate-pulse"></div>
                        <div class="text-right">
                            <span class="font-bold text-white text-xs block tracking-wide capitalize">{{ $userProfile->userName ?? 'Hostel Student' }}</span>
                            <span class="text-[10px] font-mono text-purple-200 tracking-wider block uppercase">{{ $userProfile->userID ?? 'N/A' }}</span>
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
                <a href="/student/rooms" class="text-white border-b-2 border-white pb-2 flex items-center gap-2 opacity-100 font-bold">
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

    <!-- MAIN INTERACTIVE HUB VIEWPORT -->
    <main class="max-w-[1600px] mx-auto px-6 py-6 space-y-6">

        <div>
            <h2 class="text-xl font-bold text-slate-800">Browse Rooms</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">
                {{ strcasecmp($userProfile->gender ?? 'Male', 'Female') === 0 ? 'Kolej Sutera (Female)' : 'Kolej Kasa (Male)' }} — Select a room to book
            </p>
        </div>

        <!-- Global Action Flash Responses -->
        @if(session('error'))
            <div class="bg-rose-50 border border-rose-100 text-rose-600 text-xs font-semibold rounded-xl px-4 py-3 shadow-sm">
                <span>⚠️</span> {{ session('error') }}
            </div>
        @endif

        <!-- FILTER AND LOOKUP CONFIGURATION BAR -->
        <div class="space-y-4">
            <div class="bg-white border border-slate-200/60 p-2 rounded-2xl shadow-sm flex items-center gap-3 px-4">
                <span class="text-slate-400 text-sm">🔍</span>
                <input type="text" id="roomNumberSearch" onkeyup="applyActiveMatrixFilters()" placeholder="Search by room number..." class="w-full bg-transparent border-none text-xs text-slate-700 outline-none placeholder-slate-400 py-2">
            </div>

            <div class="flex flex-wrap gap-4 items-center bg-white border border-slate-200/60 p-3 rounded-2xl shadow-sm text-[11px] font-semibold">
                <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-100 p-1 rounded-xl">
                    <span class="px-2 text-slate-400">Floor:</span>
                    <button onclick="changeSelectedFloor('all')" id="f-all" class="bg-[#5B06B2] text-white px-2.5 py-1 rounded-lg transition shadow-sm">All</button>
                    <button onclick="changeSelectedFloor('0')" id="f-0" class="text-slate-600 px-2.5 py-1 rounded-lg transition">Floor 0</button>
                    <button onclick="changeSelectedFloor('1')" id="f-1" class="text-slate-600 px-2.5 py-1 rounded-lg transition">Floor 1</button>
                    <button onclick="changeSelectedFloor('2')" id="f-2" class="text-slate-600 px-2.5 py-1 rounded-lg transition">Floor 2</button>
                </div>

                <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-100 p-1 rounded-xl">
                    <span class="px-2 text-slate-400">Wing:</span>
                    <button onclick="changeSelectedWing('all')" id="w-all" class="bg-[#5B06B2] text-white px-2.5 py-1 rounded-lg transition shadow-sm">All Wings</button>
                    <button onclick="changeSelectedWing('Wing A')" id="w-a" class="text-slate-600 px-2.5 py-1 rounded-lg transition">Wing A</button>
                    <button onclick="changeSelectedWing('Wing B')" id="w-b" class="text-slate-600 px-2.5 py-1 rounded-lg transition">Wing B</button>
                </div>
            </div>
        </div>

        <!-- RECYCLER ROOM MATRIX CONTAINER -->
        <div id="roomBrowserGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($rooms as $room)
                @php
                    $floor = substr($room->roomID, 1, 1);
                    $bedsLeft = 4 - $room->currentOccupancy;
                    
                    $status = 'Available';
                    $tagClass = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                    
                    if ($room->currentOccupancy >= 4) {
                        $status = 'Full';
                        $tagClass = 'bg-rose-50 text-rose-600 border-rose-100';
                    }
                @endphp

                <div class="room-card-unit bg-white rounded-3xl border border-slate-200/60 p-5 shadow-sm flex flex-col justify-between space-y-4"
                     data-roomid="{{ $room->roomID }}"
                     data-floor="{{ $floor }}"
                     data-wing="{{ $room->wing }}">
                    
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 font-mono tracking-wide">{{ $room->roomID }}</h4>
                            <p class="text-[10px] font-semibold text-slate-400 mt-0.5">{{ $room->buildingName }} · Floor {{ $floor }}</p>
                            <span class="inline-block mt-1.5 text-[9px] font-bold text-blue-600 bg-blue-50/80 px-2 py-0.5 rounded-md border border-blue-100/40 uppercase tracking-wide">{{ $room->wing }}</span>
                        </div>
                        <span class="text-[10px] uppercase font-extrabold border px-2.5 py-0.5 rounded-md shadow-sm {{ $tagClass }}">
                            {{ $status }}
                        </span>
                    </div>

                    <!-- Progress Micro-Bars Indicators -->
                    <div class="space-y-1">
                        <div class="flex gap-1.5 w-full">
                            @for($i = 1; $i <= 4; $i++)
                                <div class="h-1 rounded-full flex-1 {{ $i <= $room->currentOccupancy ? ($room->currentOccupancy >= 4 ? 'bg-rose-400' : 'bg-purple-400') : 'bg-slate-100' }}"></div>
                            @endfor
                        </div>
                    </div>

                    <!-- Footnote specs indicators (Price adjusted to RM 210) -->
                    <div class="flex justify-between items-center text-[11px] font-semibold text-slate-400 pt-1">
                        <span class="flex items-center gap-1">🛏️ {{ $bedsLeft }} beds left</span>
                        <span class="text-slate-800 font-bold">RM 210<span class="text-[10px] text-slate-400 font-medium">/sem</span></span>
                    </div>

                    <!-- Direct Booking Form Interceptors -->
                    @if($status === 'Available')
                        <button type="button" onclick="launchAllocationModal('{{ $room->roomID }}', '{{ $floor }}')" class="w-full bg-[#5B06B2] hover:bg-[#4A058F] text-white font-bold text-[11px] py-2.5 rounded-2xl shadow-sm flex items-center justify-center gap-1 transition">
                            Book Now ➔
                        </button>
                    @else
                        <button disabled type="button" class="w-full bg-slate-50 border border-slate-200 text-slate-400 font-bold text-[11px] py-2.5 rounded-2xl cursor-not-allowed flex items-center justify-center gap-1 transition">
                            Room Full
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </main>

    <!-- SYSTEM BOOKING TYPE MODAL CONTEXT DRAWER -->
    <div id="bookingModalBackdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full shadow-xl overflow-hidden border border-slate-100 animate-in zoom-in-95 duration-150">
            
            <!-- Modal Banner Section -->
            <div class="bg-[#5B06B2] text-white p-4 px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-purple-200">Confirm Booking</h3>
                    <p class="text-sm font-bold mt-0.5">
                        {{ strcasecmp($userProfile->gender ?? 'Male', 'Female') === 0 ? 'Kolej Sutera' : 'Kolej Kasa' }} · <span id="modalTargetRoomLabel" class="font-mono">K001</span>
                    </p>
                </div>
                <button onclick="closeAllocationModal()" class="text-purple-200 hover:text-white transition text-lg font-bold">✕</button>
            </div>

            <!-- Receipt Details Container Box (Price adjusted to RM 210) -->
            <div class="p-6 space-y-5">
                <div class="bg-purple-50/60 border border-purple-100/50 rounded-2xl p-4 text-xs font-semibold text-purple-900 space-y-2">
                    <p class="flex justify-between"><span>Hostel:</span> <span id="receiptSummaryLabel" class="text-slate-700 font-bold"></span></p>
                    <p class="flex justify-between"><span>Gender:</span> <span class="text-slate-700 font-bold capitalize">{{ $userProfile->gender ?? 'Male' }}</span></p>
                    <p class="flex justify-between"><span>Price:</span> <span class="text-slate-700 font-bold">RM 210 / semester</span></p>
                </div>

                <!-- MAIN FORM ELEMENT PASS SUBMITTER -->
                <form action="/student/book" method="POST" id="systemAllocationForm" class="space-y-4">
                    @csrf
                    <input type="hidden" name="roomID" id="formHiddenRoomID">
                    <input type="hidden" name="bookingType" id="formHiddenBookingType" value="solo">

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Booking Type</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" id="selectSoloModeBtn" onclick="switchBookingMode('solo')" class="border-2 border-[#5B06B2] bg-purple-50/30 text-[#5B06B2] text-xs font-bold py-3 rounded-2xl flex items-center justify-center gap-1.5 transition">
                                👤 Solo
                            </button>
                            <button type="button" id="selectGroupModeBtn" onclick="switchBookingMode('group')" class="border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold py-3 rounded-2xl flex items-center justify-center gap-1.5 transition">
                                👥 Group (4 pax)
                            </button>
                        </div>
                    </div>

                    <!-- COLLAPSIBLE GROUP MEMBER IDS PACKET INPUTS -->
                    <div id="groupMembersFields" class="hidden space-y-3 pt-1 border-t border-slate-100 animate-in fade-in duration-150">
                        <div class="bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-semibold rounded-xl p-3 flex items-start gap-2 leading-relaxed">
                            <span class="text-xs">⚠️</span>
                            <span>Group booking requires exactly 4 members. All must be registered UiTM students of the same gender hostel.</span>
                        </div>
                        <div class="space-y-2 text-xs">
                            <input type="text" name="peers[]" placeholder="Student ID of member 2" class="w-full bg-slate-50 border border-slate-200 focus:border-purple-400 rounded-xl px-4 py-2.5 font-mono text-xs outline-none tracking-wide transition">
                            <input type="text" name="peers[]" placeholder="Student ID of member 3" class="w-full bg-slate-50 border border-slate-200 focus:border-purple-400 rounded-xl px-4 py-2.5 font-mono text-xs outline-none tracking-wide transition">
                            <input type="text" name="peers[]" placeholder="Student ID of member 4" class="w-full bg-slate-50 border border-slate-200 focus:border-purple-400 rounded-xl px-4 py-2.5 font-mono text-xs outline-none tracking-wide transition">
                        </div>
                    </div>

                    <!-- FOOTER WORKFLOW CONTROL TRIGGER ACTIONS -->
                    <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-50">
                        <button type="button" onclick="closeAllocationModal()" class="border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs font-bold py-3 rounded-2xl transition">Cancel</button>
                        <button type="submit" class="bg-[#5B06B2] hover:bg-[#4A058F] text-white text-xs font-bold py-3 rounded-2xl shadow-sm transition">Confirm Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- FILTER AND CLIENT-SIDE LOGIC ENGINES -->
    <script>
        let selectedFloorFilter = 'all';
        let selectedWingFilter = 'all';

        function changeSelectedFloor(val) {
            selectedFloorFilter = val;
            ['all', '0', '1', '2'].forEach(x => document.getElementById('f-'+x).className = "text-slate-600 px-2.5 py-1 rounded-lg transition");
            document.getElementById('f-' + val).className = "bg-[#5B06B2] text-white px-2.5 py-1 rounded-lg transition shadow-sm";
            applyActiveMatrixFilters();
        }

        function changeSelectedWing(val) {
            selectedWingFilter = val;
            ['all', 'a', 'b'].forEach(x => document.getElementById('w-'+x).className = "text-slate-600 px-2.5 py-1 rounded-lg transition");
            document.getElementById('w-' + (val==='all'?'all':(val==='Wing A'?'a':'b'))).className = "bg-[#5B06B2] text-white px-2.5 py-1 rounded-lg transition shadow-sm";
            applyActiveMatrixFilters();
        }

        function applyActiveMatrixFilters() {
            const query = document.getElementById('roomNumberSearch').value.toLowerCase().trim();
            const cards = document.querySelectorAll('.room-card-unit');

            cards.forEach(card => {
                const id = card.getAttribute('data-roomid').toLowerCase();
                const floor = card.getAttribute('data-floor');
                const wing = card.getAttribute('data-wing');

                const matchesQuery = id.includes(query);
                const matchesFloor = (selectedFloorFilter === 'all' || floor === selectedFloorFilter);
                const matchesWing = (selectedWingFilter === 'all' || wing === selectedWingFilter);

                if (matchesQuery && matchesFloor && matchesWing) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // POP-UP DIALOG BOX INTERACTION MATRIX LAYOUT HANDLERS
        function launchAllocationModal(roomId, floorNum) {
            document.getElementById('formHiddenRoomID').value = roomId;
            document.getElementById('modalTargetRoomLabel').innerText = roomId;
            
            const rawLabelText = document.getElementById('systemHeaderHostelLabel').innerText.split('(')[0].trim();
            document.getElementById('receiptSummaryLabel').innerText = `${roomId} · ${rawLabelText} · Floor ${floorNum}`;
            
            switchBookingMode('solo');
            
            document.getElementById('bookingModalBackdrop').classList.remove('hidden');
            document.getElementById('bookingModalBackdrop').classList.add('flex');
        }

        function closeAllocationModal() {
            document.getElementById('bookingModalBackdrop').classList.add('hidden');
            document.getElementById('bookingModalBackdrop').classList.remove('flex');
        }

        function switchBookingMode(mode) {
            document.getElementById('formHiddenBookingType').value = mode;
            
            const btnSolo = document.getElementById('selectSoloModeBtn');
            const btnGroup = document.getElementById('selectGroupModeBtn');
            const groupFields = document.getElementById('groupMembersFields');

            if (mode === 'solo') {
                btnSolo.className = "border-2 border-[#5B06B2] bg-purple-50/30 text-[#5B06B2] text-xs font-bold py-3 rounded-2xl flex items-center justify-center gap-1.5 transition";
                btnGroup.className = "border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold py-3 rounded-2xl flex items-center justify-center gap-1.5 transition";
                groupFields.classList.add('hidden');
            } else {
                btnGroup.className = "border-2 border-[#5B06B2] bg-purple-50/30 text-[#5B06B2] text-xs font-bold py-3 rounded-2xl flex items-center justify-center gap-1.5 transition";
                btnSolo.className = "border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold py-3 rounded-2xl flex items-center justify-center gap-1.5 transition";
                groupFields.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>