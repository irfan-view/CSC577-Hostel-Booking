<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Room Vacancy Monitor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans min-h-screen">

    <!-- MASTER PURPLE TOPBAR HEADER -->
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

            <!-- Navigation Tabs Links -->
            <nav class="flex gap-6 text-xs font-semibold pt-3 pb-1">
                <a href="/admin/dashboard" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
                    <span>📋</span> Overview
                </a>
                <a href="/admin/rooms" class="text-purple-100/70 hover:text-white transition pb-2 flex items-center gap-2 opacity-80">
                    <span>🔧</span> Room Management
                </a>
                <a href="/admin/vacancy" class="text-white border-b-2 border-white pb-2 flex items-center gap-2 opacity-100">
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

    <!-- MAIN GRID CONTAINER CONTENT -->
    <main class="max-w-[1600px] mx-auto px-6 py-6 space-y-6">
        
        <div>
            <h2 class="text-xl font-bold text-slate-800">Room Vacancy Monitor</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Manually toggle each room's occupancy status after physical inspection</p>
        </div>

        <!-- EXPLANATORY NOTICE INFORMATION BOX -->
        <div class="bg-purple-50/50 border border-purple-100 text-purple-900 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
            <span class="text-purple-600 font-bold text-sm">ℹ️</span>
            <p class="text-[11px] font-semibold text-purple-800/90 leading-relaxed">
                This panel allows JPK and hostel staff to update room vacancy based on actual physical checks. Toggle a room's status between <span class="font-bold">Vacant</span> (available) and <span class="font-bold">Occupied</span>. Reserved and Maintenance rooms must be managed via Room Management.
            </p>
        </div>

        <!-- UPDATED FILTERS DECK WITH CONSOLIDATED SIDE-BY-SIDE CONTROLS -->
        <div class="bg-white border border-slate-200/60 p-4 rounded-2xl shadow-sm">
            <div class="flex flex-wrap gap-3 items-center text-[11px] font-semibold">
                
                <!-- Hostel Filter Segment -->
                <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-100 p-1 rounded-xl">
                    <span class="px-2 text-slate-400">Hostel:</span>
                    <button onclick="filterGridHostel('all')" id="vbtn-h-all" class="bg-[#5B06B2] text-white px-2.5 py-1 rounded-lg transition">All</button>
                    <button onclick="filterGridHostel('Kolej Kasa')" id="vbtn-h-kasa" class="text-slate-600 px-2.5 py-1 rounded-lg transition">Kolej Kasa</button>
                    <button onclick="filterGridHostel('Kolej Sutera')" id="vbtn-h-sutera" class="text-slate-600 px-2.5 py-1 rounded-lg transition">Kolej Sutera</button>
                </div>

                <!-- Floor Filter Segment -->
                <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-100 p-1 rounded-xl">
                    <span class="px-2 text-slate-400">Floor:</span>
                    <button onclick="filterGridFloor('all')" id="vbtn-f-all" class="bg-[#5B06B2] text-white px-2.5 py-1 rounded-lg transition">All Floors</button>
                    <button onclick="filterGridFloor('0')" id="vbtn-f-0" class="text-slate-600 px-2.5 py-1 rounded-lg transition">Floor 0</button>
                    <button onclick="filterGridFloor('1')" id="vbtn-f-1" class="text-slate-600 px-2.5 py-1 rounded-lg transition">Floor 1</button>
                    <button onclick="filterGridFloor('2')" id="vbtn-f-2" class="text-slate-600 px-2.5 py-1 rounded-lg transition">Floor 2</button>
                    <button onclick="filterGridFloor('3')" id="vbtn-f-3" class="text-slate-600 px-2.5 py-1 rounded-lg transition">Floor 3</button>
                </div>

                <!-- Wing Filter Segment (Grouped side-by-side cleanly) -->
                <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-100 p-1 rounded-xl">
                    <span class="px-2 text-slate-400">Wing:</span>
                    <button onclick="filterGridWing('all')" id="vbtn-w-all" class="bg-[#5B06B2] text-white px-2.5 py-1 rounded-lg transition">All Wings</button>
                    <button onclick="filterGridWing('Wing A')" id="vbtn-w-a" class="text-slate-600 px-2.5 py-1 rounded-lg transition">Wing A</button>
                    <button onclick="filterGridWing('Wing B')" id="vbtn-w-b" class="text-slate-600 px-2.5 py-1 rounded-lg transition">Wing B</button>
                </div>

            </div>
        </div>

        <!-- CORE VACANCY CARDS GRID WRAPPER -->
        <div id="vacancyCardsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($rooms as $room)
                @php
                    $floorLevel = substr($room->roomID, 1, 1);
                    $isOccupied = $room->currentOccupancy >= 4;
                @endphp
                <div class="vacancy-card bg-white rounded-3xl border border-slate-200/60 p-5 shadow-sm flex flex-col justify-between space-y-4"
                     data-hostel="{{ $room->buildingName }}"
                     data-floor="{{ $floorLevel }}"
                     data-wing="{{ $room->wing }}">
                    
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 font-mono tracking-wide">{{ $room->roomID }}</h4>
                            <p class="text-[10px] font-semibold text-slate-400 mt-0.5">{{ $room->buildingName }} · Floor {{ $floorLevel }}</p>
                            <span class="inline-block mt-1.5 text-[9px] font-bold text-blue-600 bg-blue-50/80 px-2 py-0.5 rounded-md border border-blue-100/40 uppercase">{{ $room->wing }}</span>
                        </div>
                        <span class="text-[10px] uppercase font-extrabold border px-2 py-0.5 rounded-md shadow-sm {{ $isOccupied ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }}">
                            {{ $isOccupied ? 'Occupied' : 'Vacant' }}
                        </span>
                    </div>

                    <div class="space-y-1">
                        <div class="flex gap-1.5 w-full">
                            @for($i = 1; $i <= 4; $i++)
                                <div class="h-1.5 flex-1 rounded-full {{ $i <= $room->currentOccupancy ? ($isOccupied ? 'bg-rose-500' : 'bg-emerald-500') : 'bg-slate-100' }}"></div>
                            @endfor
                        </div>
                    </div>

                    <form action="/admin/vacancy/toggle" method="POST" class="w-full pt-1">
                        @csrf
                        <input type="hidden" name="roomID" value="{{ $room->roomID }}">
                        <input type="hidden" name="current_status" value="{{ $isOccupied ? 'occupied' : 'vacant' }}">
                        
                        @if($isOccupied)
                            <button type="submit" class="w-full bg-white border border-emerald-200 hover:bg-emerald-50 text-emerald-600 font-bold text-[11px] py-2.5 rounded-2xl shadow-sm flex items-center justify-center gap-1.5 transition">
                                👁️ Mark as Vacant
                            </button>
                        @else
                            <button type="submit" class="w-full bg-white border border-rose-200 hover:bg-rose-50 text-rose-600 font-bold text-[11px] py-2.5 rounded-2xl shadow-sm flex items-center justify-center gap-1.5 transition">
                                👁️ Mark as Occupied
                            </button>
                        @endif
                    </form>
                </div>
            @endforeach
        </div>

    </main>

    <!-- EXPANDED JAVASCRIPT MULTI-FILTER SLICE ENGINE -->
    <script>
        let gridHostel = 'all';
        let gridFloor = 'all';
        let gridWing = 'all';

        function filterGridHostel(val) {
            gridHostel = val;
            ['all', 'kasa', 'sutera'].forEach(x => document.getElementById('vbtn-h-'+x).className = "text-slate-600 px-2.5 py-1 rounded-lg transition");
            document.getElementById('vbtn-h-' + (val==='all'?'all':(val==='Kolej Kasa'?'kasa':'sutera'))).className = "bg-[#5B06B2] text-white px-2.5 py-1 rounded-lg transition";
            applyCardGridFilters();
        }

        function filterGridFloor(val) {
            gridFloor = val;
            ['all', '0', '1', '2', '3'].forEach(x => document.getElementById('vbtn-f-'+x).className = "text-slate-600 px-2.5 py-1 rounded-lg transition");
            document.getElementById('vbtn-f-' + val).className = "bg-[#5B06B2] text-white px-2.5 py-1 rounded-lg transition";
            applyCardGridFilters();
        }

        function filterGridWing(val) {
            gridWing = val;
            ['all', 'a', 'b'].forEach(x => document.getElementById('vbtn-w-'+x).className = "text-slate-600 px-2.5 py-1 rounded-lg transition");
            document.getElementById('vbtn-w-' + (val==='all'?'all':(val==='Wing A'?'a':'b'))).className = "bg-[#5B06B2] text-white px-2.5 py-1 rounded-lg transition";
            applyCardGridFilters();
        }

        function applyCardGridFilters() {
            const cards = document.querySelectorAll('.vacancy-card');
            cards.forEach(card => {
                const hMatch = (gridHostel === 'all' || card.getAttribute('data-hostel') === gridHostel);
                const fMatch = (gridFloor === 'all' || card.getAttribute('data-floor') === gridFloor);
                const wMatch = (gridWing === 'all' || card.getAttribute('data-wing') === gridWing);
                
                if (hMatch && fMatch && wMatch) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>