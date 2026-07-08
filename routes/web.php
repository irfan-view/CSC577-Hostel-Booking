<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes Configuration Registry
|--------------------------------------------------------------------------
*/

// --- ROOT LOGIN ROUTE RENDERING ---
Route::get('/', function () {
    if (Session::has('user_id')) {
        return Session::get('role') === 'admin' 
            ? redirect('/admin/dashboard') 
            : redirect('/student/dashboard');
    }
    return view('login'); 
});

// --- CORE SYSTEM LOGIN HANDLER ---
Route::post('/login', function (Request $request) {
    $userID = $request->input('userID') ?? $request->input('adminID') ?? $request->input('id');
    $password = $request->input('password') ?? $request->input('adminPassword') ?? $request->input('pass');

    $cleanID = $userID ? strtoupper(trim($userID)) : '';

    // 1. MASTER ADMINISTRATIVE OVERRIDE DECK
    if ($cleanID === 'ADMIN001' && $password === 'Admin@123') {
        Session::put('user_id', 'ADMIN001');
        Session::put('role', 'admin');
        return redirect('/admin/dashboard');
    }

    // 2. STANDARD DATABASE AUTHENTICATION FLOW
    $user = DB::table('hostel_users')->where('userID', $userID)->first();

    if ($user && isset($user->passwordHash) && Hash::check($password, $user->passwordHash)) {
        
        // Dynamically detect role based on the ID prefix structure
        $role = (str_starts_with(strtoupper($user->userID), 'ADMIN')) ? 'admin' : 'student';

        Session::forget(['user_id', 'role']);
        Session::put('user_id', $user->userID);
        Session::put('role', $role);
        Session::save();

        return ($role === 'admin') 
            ? redirect('/admin/dashboard') 
            : redirect('/student/dashboard');
    }

    return redirect('/')->withErrors(['error' => 'Invalid Identification credentials or password mismatch.']);
});

// --- SYSTEM LOGOUT INTERCEPTOR ---
Route::get('/logout', function () {
    Session::forget(['user_id', 'role']);
    return redirect('/');
});

// --- SEEDER ROUTE: ADDITIONAL ADMINISTRATORS WITH SYNCHRONIZED MASTER PASSWORDS ---
Route::get('/insert-test-admins', function () {
    $admins = [
        [
            'userID' => 'ADMIN002',
            'userName' => 'mohamad zul amri',
            'passwordHash' => Hash::make('Admin@123'),
            'strikeCount' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'userID' => 'ADMIN003',
            'userName' => 'muhammad nabil danish',
            'passwordHash' => Hash::make('Admin@123'),
            'strikeCount' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];

    foreach ($admins as $admin) {
        DB::table('hostel_users')->updateOrInsert(
            ['userID' => $admin['userID']],
            [
                'userName' => $admin['userName'],
                'passwordHash' => $admin['passwordHash'],
                'strikeCount' => $admin['strikeCount'],
                'created_at' => $admin['created_at'],
                'updated_at' => $admin['updated_at']
            ]
        );
    }

    return "Database administrative accounts successfully updated! All admins share password: Admin@123";
});

// --- SEEDER ROUTE: GENERATES 20 DIVERSE STUDENT RECORDS ---
Route::get('/insert-test-students', function () {
    // 1. Define core team records explicitly to preserve presentation parameters
    $coreTeam = [
        [
            'userID' => '2024669856',
            'userName' => 'Ahmad Irfan',
            'gender' => 'Male',
            'program' => 'CS270',
            'semester' => 4,
            'passwordHash' => Hash::make('Student@123'),
            'strikeCount' => 0,
        ],
        [
            'userID' => '2024236368',
            'userName' => 'mohamad izzrul emir',
            'gender' => 'Male',
            'program' => 'CS270',
            'semester' => 4,
            'passwordHash' => Hash::make('Student@123'),
            'strikeCount' => 0,
        ],
        [
            'userID' => '2024690002',
            'userName' => 'harzan qayyum',
            'gender' => 'Male',
            'program' => 'CS270',
            'semester' => 4,
            'passwordHash' => Hash::make('Student@123'),
            'strikeCount' => 0,
        ]
    ];

    foreach ($coreTeam as $member) {
        DB::table('hostel_users')->updateOrInsert(
            ['userID' => $member['userID']],
            array_merge($member, ['created_at' => now(), 'updated_at' => now()])
        );
    }

    // 2. Programmatically generate the remaining 17 students to reach a total of 20
    $maleNames = ['Ahmad Syazwan', 'Muhammad Farhan', 'Khairul Anuar', 'Mohamad Zulhilmi', 'Muhammad Nabil', 'Luqman Hakim', 'Amirul Ashraf', 'Aiman Haziq', 'Wan Muhammad'];
    $femaleNames = ['Siti Nurhaliza', 'Nur Aisha Amira', 'Farah Nabilah', 'Anis Syazwani', 'Puteri Balqis', 'Nurul Izzah', 'Fatin Hamimah', 'Alya Maisarah'];
    
    $programs = ['CS230', 'CS264', 'CS267', 'CS270'];

    for ($i = 1; $i <= 17; $i++) {
        $gender = ($i % 2 === 0) ? 'Female' : 'Male';
        $userName = $gender === 'Male' ? $maleNames[($i % count($maleNames))] . ' bin Ramli' : $femaleNames[($i % count($femaleNames))] . ' binti Roslan';
        
        $userID = '2024' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        $program = $programs[array_rand($programs)];
        $semester = rand(1, 5);
        
        if ($program === 'CS270' && $semester === 4) {
            $semester = 3; 
        }

        $randStrike = rand(1, 100);
        $strikeCount = 0;
        if ($randStrike > 85) { $strikeCount = 3; }      
        elseif ($randStrike > 70) { $strikeCount = 2; }  
        elseif ($randStrike > 50) { $strikeCount = 1; }  

        DB::table('hostel_users')->updateOrInsert(
            ['userID' => $userID],
            [
                'userName' => strtolower($userName),
                'gender' => $gender,
                'program' => $program,
                'semester' => $semester,
                'passwordHash' => Hash::make('Student@123'),
                'strikeCount' => $strikeCount,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }

    return "Database dataset successfully populated! 20 unique student accounts initialized under 'Student@123'.";
});


// =========================================================================
// 🏢 MASTER STUDENT PORTAL ROUTE DECK (PREFIX: /student)
// =========================================================================
Route::prefix('student')->group(function () {

    Route::get('/dashboard', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Unauthorized entry points.']);
        }

        $userId = Session::get('user_id');
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first();

        if (!$userProfile) {
            $userProfile = (object)['userID' => $userId, 'userName' => 'Ahmad Irfan', 'gender' => 'Male', 'strikeCount' => 0];
        }

        $activeBooking = DB::table('reservations')
            ->where('userID', $userId)
            ->where('bookingStatus', 'Confirmed')
            ->first();

        $totalRoomsCount = DB::table('rooms')->count();
        $occupiedRoomsCount = DB::table('rooms')->where('currentOccupancy', '>=', 4)->count();
        $availableRoomsCount = $totalRoomsCount - $occupiedRoomsCount;
        $announcementsCount = DB::table('announcements')->count();

        $announcements = DB::table('announcements')
            ->orderBy('is_urgent', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('dashboard', compact('userProfile', 'activeBooking', 'totalRoomsCount', 'occupiedRoomsCount', 'availableRoomsCount', 'announcementsCount', 'announcements'));
    });

    Route::get('/rooms', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Access expired.']);
        }

        $userId = Session::get('user_id');
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first() ?? (object)['userID' => $userId, 'userName' => 'Ahmad Irfan', 'gender' => 'Male', 'strikeCount' => 0];

        if (($userProfile->strikeCount ?? 0) >= 3) {
            return redirect('/student/eligibility')->withErrors(['error' => 'Your booking privileges have been locked due to excessive strikes.']);
        }

        $prefix = (strcasecmp($userProfile->gender, 'Female') === 0) ? 'S' : 'K';

        $rooms = DB::table('rooms')
            ->where('roomID', 'LIKE', $prefix . '%')
            ->orderBy('roomID', 'asc')
            ->get();

        return view('rooms', compact('rooms', 'userProfile'));
    });

    // --- SECURE BOOK TRANSACTION ENDPOINT POST GUARD ---
    Route::post('/book', function (Request $request) {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Access Denied.']);
        }

        $userId = Session::get('user_id');
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first();

        // 1. BACKEND STRIKE POST BLOCKER FOR PRIMARY BOOKER
        if ($userProfile && ($userProfile->strikeCount ?? 0) >= 3) {
            return redirect('/student/eligibility')->withErrors(['error' => 'Action forbidden. Your account is frozen due to excessive strikes.']);
        }

        $roomID = $request->input('roomID');
        $bookingType = $request->input('bookingType', 'solo');

        $existing = DB::table('reservations')
            ->where('userID', $userId)
            ->where('bookingStatus', 'Confirmed')
            ->first();

        if ($existing) {
            return redirect('/student/dashboard')->withErrors(['error' => 'You already possess an active structural allocation receipt this semester.']);
        }

        // 2. DEFENSIVE PROTECTION DECK FOR GROUP INPUT VALIDATION
        if ($bookingType === 'group') {
            $peers = $request->input('peers', []);
            $validPeersCount = 0;

            foreach ($peers as $peerID) {
                if (!empty($peerID)) {
                    $peerID = trim($peerID);
                    $validPeersCount++;

                    // A. Check existence in ledger
                    $peerProfile = DB::table('hostel_users')->where('userID', $peerID)->first();
                    if (!$peerProfile) {
                        return redirect()->back()->withInput()->withErrors(['error' => "Validation Failure: Student ID '{$peerID}' does not exist in the system registry."]);
                    }

                    // B. Enforce Gender Alignment
                    if (strcasecmp($peerProfile->gender, $userProfile->gender) !== 0) {
                        return redirect()->back()->withInput()->withErrors(['error' => "Validation Failure: Student '{$peerProfile->userName}' ({$peerID}) cannot be added to this room due to a gender mismatch restriction."]);
                    }

                    // C. Enforce Disciplinary Sanctions Guard
                    if (($peerProfile->strikeCount ?? 0) >= 3) {
                        return redirect()->back()->withInput()->withErrors(['error' => "Validation Failure: Student '{$peerProfile->userName}' ({$peerID}) is barred from booking due to excessive policy violation strikes."]);
                    }

                    // D. Ensure the peer doesn't already hold another active booking slot
                    $peerExistingBooking = DB::table('reservations')
                        ->where('userID', $peerID)
                        ->where('bookingStatus', 'Confirmed')
                        ->exists();
                    if ($peerExistingBooking) {
                        return redirect()->back()->withInput()->withErrors(['error' => "Validation Failure: Student '{$peerProfile->userName}' ({$peerID}) has already secured a room reservation pass for this semester."]);
                    }
                }
            }

            if ($validPeersCount !== 3) {
                return redirect()->back()->withInput()->withErrors(['error' => 'Group booking requires exactly 4 members. Please fill out all 3 peer ID input boxes.']);
            }
        }

        // 3. EXECUTE SAFE ATOMIC DATABASE TRANSACTION WRITE
        DB::transaction(function () use ($userId, $roomID, $bookingType, $request) {
            if ($bookingType === 'group') {
                DB::table('reservations')->insert([
                    'logID' => 'BK' . rand(1000, 9999),
                    'userID' => $userId,
                    'roomTargetID' => $roomID,
                    'securedWordLog' => 'GROUP',
                    'bookingStatus' => 'Confirmed',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                DB::table('rooms')->where('roomID', $roomID)->increment('currentOccupancy');

                $peers = $request->input('peers', []);
                foreach ($peers as $peerID) {
                    if (!empty($peerID)) {
                        DB::table('reservations')->insert([
                            'logID' => 'BK' . rand(1000, 9999),
                            'userID' => trim($peerID),
                            'roomTargetID' => $roomID,
                            'securedWordLog' => 'GROUP',
                            'bookingStatus' => 'Confirmed',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        DB::table('rooms')->where('roomID', $roomID)->increment('currentOccupancy');
                    }
                }
            } else {
                DB::table('reservations')->insert([
                    'logID' => 'BK' . rand(1000, 9999),
                    'userID' => $userId,
                    'roomTargetID' => $roomID,
                    'securedWordLog' => 'SOLO',
                    'bookingStatus' => 'Confirmed',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                DB::table('rooms')->where('roomID', $roomID)->increment('currentOccupancy');
            }
        });

        return redirect('/student/dashboard')->with('success', 'Your group hostel bedroom reservation pass has compiled and verified successfully!');
    });

    Route::get('/bookings', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Please authenticate.']);
        }

        $userId = Session::get('user_id');
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first() ?? (object)['userID' => $userId, 'userName' => 'Ahmad Irfan', 'gender' => 'Male', 'strikeCount' => 0];

        $booking = DB::table('reservations')
            ->where('userID', $userId)
            ->where('bookingStatus', 'Confirmed')
            ->first();

        return view('my_bookings', compact('booking', 'userProfile'));
    });

    Route::post('/cancel-booking', function (Request $request) {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Action forbidden.']);
        }

        $bookingID = $request->input('bookingID');
        $record = DB::table('reservations')->where('logID', $bookingID)->first();

        if ($record) {
            DB::transaction(function () use ($record, $bookingID) {
                DB::table('rooms')->where('roomID', $record->roomTargetID)->decrement('currentOccupancy');
                DB::table('reservations')->where('logID', $bookingID)->update([
                    'bookingStatus' => 'Cancelled',
                    'updated_at' => now()
                ]);
            });
        }

        return redirect()->back()->with('success', 'Your bedroom reservation has been cancelled and bed spaces freed up.');
    });

    Route::get('/eligibility', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Unauthorized entry points.']);
        }

        $userId = Session::get('user_id');
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first() ?? (object)['userID' => $userId, 'userName' => 'Ahmad Irfan', 'gender' => 'Male', 'strikeCount' => 0];

        return view('eligibility', compact('userProfile'));
    });

    Route::get('/announcements', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Unauthorized access. Please log in.']);
        }

        $userId = Session::get('user_id');
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first() ?? (object)['userID' => $userId, 'userName' => 'Ahmad Irfan', 'gender' => 'Male', 'strikeCount' => 0];

        $announcements = DB::table('announcements')->orderBy('created_at', 'desc')->get();
        return view('announcements', compact('announcements', 'userProfile'));
    });

});


// =========================================================================
// 🛡️ MASTER JPK ADMINISTRATIVE SUITE INTERFACE DECK (PREFIX: /admin)
// =========================================================================
Route::prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Access Denied. Administrators only.']);
        }

        $adminId = Session::get('user_id');
        $adminProfile = DB::table('hostel_users')->where('userID', $adminId)->first();

        $totalRooms = DB::table('rooms')->count();
        $occupiedRooms = DB::table('rooms')->where('currentOccupancy', '>=', 4)->count();
        $activeBookings = DB::table('reservations')->where('bookingStatus', 'Confirmed')->count();
        
        $maintenanceRooms = DB::table('rooms')->where('currentOccupancy', 4)->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('reservations')
                  ->whereRaw('reservations.roomTargetID = rooms.roomID');
        })->count();

        $availableRooms = DB::table('rooms')->where('currentOccupancy', '<', 4)->count();

        $kasaTotal = DB::table('rooms')->where('roomID', 'LIKE', 'K%')->count() ?: 1;
        $kasaOccupied = DB::table('rooms')->where('roomID', 'LIKE', 'K%')->where('currentOccupancy', '>=', 4)->count();
        $kasaAvailable = DB::table('rooms')->where('roomID', 'LIKE', 'K%')->where('currentOccupancy', '<', 4)->count();
        $kasaRate = round(($kasaOccupied / $kasaTotal) * 100);

        $suteraTotal = DB::table('rooms')->where('roomID', 'LIKE', 'S%')->count() ?: 1;
        $suteraOccupied = DB::table('rooms')->where('roomID', 'LIKE', 'S%')->where('currentOccupancy', '>=', 4)->count();
        $suteraAvailable = DB::table('rooms')->where('roomID', 'LIKE', 'S%')->where('currentOccupancy', '<', 4)->count();
        $suteraRate = round(($suteraOccupied / $suteraTotal) * 100);

        $totalBedsAvailable = (DB::table('rooms')->count() * 4) - DB::table('rooms')->sum('currentOccupancy');
        $totalBedsOccupied = DB::table('rooms')->sum('currentOccupancy');

        $recentActivities = DB::table('reservations')
            ->join('hostel_users', 'reservations.userID', '=', 'hostel_users.userID')
            ->select('reservations.logID', 'reservations.roomTargetID', 'reservations.bookingStatus', 'reservations.updated_at', 'hostel_users.userName')
            ->orderBy('reservations.updated_at', 'desc')
            ->take(4)
            ->get();

        return view('admin_dashboard', compact(
            'adminProfile', 'availableRooms', 'occupiedRooms', 'activeBookings', 'maintenanceRooms',
            'kasaTotal', 'kasaOccupied', 'kasaAvailable', 'kasaRate',
            'suteraTotal', 'suteraOccupied', 'suteraAvailable', 'suteraRate',
            'totalBedsAvailable', 'totalBedsOccupied', 'recentActivities'
        ));
    });

    Route::get('/rooms', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Administrators verification failure.']);
        }

        $adminId = Session::get('user_id');
        $adminProfile = DB::table('hostel_users')->where('userID', $adminId)->first();

        $rooms = DB::table('rooms')->orderBy('roomID', 'asc')->get();
        return view('admin_rooms', compact('rooms', 'adminProfile'));
    });

    Route::post('/rooms/reserve', function (Request $request) {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Action forbidden.']);
        }

        $roomIDs = $request->input('selected_rooms'); 
        if (is_string($roomIDs) && !empty($roomIDs)) {
            $roomIDs = explode(',', $roomIDs);
        } else if (!is_array($roomIDs)) {
            $roomIDs = [];
        }
        
        $targetFloor = $request->input('floor');
        $actionType = strtolower(trim($request->input('action_type', '')));

        if (str_contains($actionType, 'floor') && isset($targetFloor)) {
            DB::table('rooms')
                ->where('roomID', 'LIKE', 'K' . $targetFloor . '%')
                ->update([
                    'currentOccupancy' => 4,
                    'updated_at' => now()
                ]);
            $msg = "Entire floor level successfully blocked!";
        } else {
            DB::table('rooms')
                ->whereIn('roomID', $roomIDs)
                ->update([
                    'currentOccupancy' => 4,
                    'updated_at' => now()
                ]);
            $msg = "Selected room footprints successfully blocked!";
        }

        return redirect()->back()->with('success', $msg);
    });

    Route::post('/rooms/unreserve', function (Request $request) {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Action forbidden.']);
        }

        $roomIDs = $request->input('selected_rooms');
        if (is_string($roomIDs) && !empty($roomIDs)) {
            $roomIDs = explode(',', $roomIDs);
        } else if (!is_array($roomIDs)) {
            $roomIDs = [];
        }
        
        $targetFloor = $request->input('floor');
        $actionType = strtolower(trim($request->input('action_type', '')));

        if (str_contains($actionType, 'floor') && isset($targetFloor)) {
            DB::table('rooms')
                ->where('roomID', 'LIKE', 'K' . $targetFloor . '%')
                ->update([
                    'currentOccupancy' => 0,
                    'updated_at' => now()
                ]);
            $msg = "Entire floor level successfully released back to available pools!";
        } else {
            DB::table('rooms')
                ->whereIn('roomID', $roomIDs)
                ->update([
                    'currentOccupancy' => 0,
                    'updated_at' => now()
                ]);
            $msg = "Selected room constraints cleanly released back to available pools.";
        }

        return redirect()->back()->with('success', $msg);
    });

    Route::get('/vacancy', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Access Denied. Administrators only.']);
        }

        $adminId = Session::get('user_id');
        $adminProfile = DB::table('hostel_users')->where('userID', $adminId)->first();

        $rooms = DB::table('rooms')->orderBy('roomID', 'asc')->get();
        return view('admin_vacancy', compact('rooms', 'adminProfile'));
    });

    Route::post('/vacancy/toggle', function (Request $request) {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Action forbidden.']);
        }

        $roomID = $request->input('roomID');
        $currentStatus = $request->input('current_status');

        $targetOccupancy = ($currentStatus === 'vacant') ? 4 : 0;

        DB::table('rooms')->where('roomID', $roomID)->update([
            'currentOccupancy' => $targetOccupancy,
            'updated_at' => now()
        ]);

        return redirect()->back();
    });

    Route::get('/bookings', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Access Denied. Administrators only.']);
        }

        $adminId = Session::get('user_id');
        $adminProfile = DB::table('hostel_users')->where('userID', $adminId)->first();

        $records = DB::table('reservations')
            ->join('hostel_users', 'reservations.userID', '=', 'hostel_users.userID')
            ->select('reservations.*', 'hostel_users.userName')
            ->orderBy('reservations.created_at', 'desc')
            ->get();

        return view('admin_bookings', compact('records', 'adminProfile'));
    });

    Route::post('/bookings/cancel', function (Request $request) {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Unauthorized entry.']);
        }

        $logID = $request->input('logID');
        $roomTargetID = $request->input('roomID');
        $reason = $request->input('cancellation_reason') ?? 'Admin Intervention';

        DB::transaction(function () use ($logID, $roomTargetID, $reason) {
            DB::table('rooms')
                ->where('roomID', $roomTargetID)
                ->where('currentOccupancy', '>', 0)
                ->decrement('currentOccupancy');

            DB::table('reservations')
                ->where('logID', $logID)
                ->update([
                    'bookingStatus' => 'Cancelled (Admin): ' . trim($reason),
                    'updated_at' => now()
                ]);
        });

        return redirect()->back()->with('success', "Booking assignment {$logID} successfully revoked with reason: {$reason}");
    });

    Route::get('/announcements', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Access Denied.']);
        }

        $adminId = Session::get('user_id');
        $adminProfile = DB::table('hostel_users')->where('userID', $adminId)->first();

        $bulletins = DB::table('announcements')->orderBy('created_at', 'desc')->get();
        return view('admin_announcements', compact('bulletins', 'adminProfile'));
    });

    Route::post('/announcements/publish', function (Request $request) {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Unauthorized entry.']);
        }

        DB::table('announcements')->insert([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'is_urgent' => $request->has('is_urgent'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'New official notification broadcasted successfully!');
    });

    Route::post('/announcements/delete', function (Request $request) {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Action forbidden.']);
        }

        DB::table('announcements')->where('id', $request->input('id'))->delete();

        return redirect()->back()->with('success', 'Official bulletin notice has been permanently expunged.');
    });

});