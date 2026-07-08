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

// --- UPDATED SEEDER ROUTE: SYNCHRONIZED MASTER PASSWORDS FOR ALL ADMINS ---
Route::get('/insert-test-admins', function () {
    $admins = [
        [
            'userID' => 'ADMIN002',
            'userName' => 'mohamad zul amri',
            'passwordHash' => Hash::make('Admin@123'),
            'accountStatus' => 'Active',
            'strikeCount' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'userID' => 'ADMIN003',
            'userName' => 'muhammad nabil danish',
            'passwordHash' => Hash::make('Admin@123'),
            'accountStatus' => 'Active',
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
                'accountStatus' => $admin['accountStatus'],
                'strikeCount' => $admin['strikeCount'],
                'created_at' => $admin['created_at'],
                'updated_at' => $admin['updated_at']
            ]
        );
    }

    return "Database administrative accounts successfully updated! All admins (ADMIN001, ADMIN002, ADMIN003) now share the exact same password: Admin@123";
});

// --- TEMPORARY AUTOMATED DATA INJECTION SEEDER ---
Route::get('/insert-test-students', function () {
    $students = [
        [
            'userID' => '2024236368',
            'userName' => 'mohamad izzrul emir',
            'passwordHash' => Hash::make('Student@123'),
            'accountStatus' => 'Active',
            'strikeCount' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'userID' => '2024690002',
            'userName' => 'harzan qayyum',
            'passwordHash' => Hash::make('Student@123'),
            'accountStatus' => 'Active',
            'strikeCount' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'userID' => '2024881234',
            'userName' => 'siti nurhaliza binti rasheed',
            'passwordHash' => Hash::make('Student@123'),
            'accountStatus' => 'Active',
            'strikeCount' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'userID' => '2024114567',
            'userName' => 'nur aisha amira binti zaidi',
            'passwordHash' => Hash::make('Student@123'),
            'accountStatus' => 'Active',
            'strikeCount' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];

    foreach ($students as $student) {
        if (!DB::table('hostel_users')->where('userID', $student['userID'])->exists()) {
            DB::table('hostel_users')->insert($student);
        }
    }

    $myId = '2024669856';
    $hashedPassword = Hash::make('Student@123');
    $exists = DB::table('hostel_users')->where('userID', $myId)->exists();

    if (!$exists) {
        DB::table('hostel_users')->insert([
            'userID' => $myId,
            'userName' => 'Ahmad Irfan',
            'passwordHash' => $hashedPassword,
            'accountStatus' => 'Active',
            'strikeCount' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    } else {
        DB::table('hostel_users')->where('userID', $myId)->update([
            'userName' => 'Ahmad Irfan',
            'passwordHash' => $hashedPassword,
            'updated_at' => now()
        ]);
    }

    return "Database synchronized! Your personal account (2024669856) and team test records are fully updated with password: Student@123";
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
            $userProfile = (object)['userID' => $userId, 'userName' => 'Ahmad Irfan'];
        }

        $activeBooking = DB::table('reservations')
            ->where('userID', $userId)
            ->where('bookingStatus', 'Confirmed')
            ->first();

        $totalRoomsCount = DB::table('rooms')->count();
        $occupiedRoomsCount = DB::table('rooms')->where('currentOccupancy', '>=', 4)->count();
        $availableRoomsCount = $totalRoomsCount - $occupiedRoomsCount;
        $announcementsCount = DB::table('announcements')->count();

        return view('dashboard', compact('userProfile', 'activeBooking', 'totalRoomsCount', 'occupiedRoomsCount', 'availableRoomsCount', 'announcementsCount'));
    });

    Route::get('/rooms', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Access expired.']);
        }

        $userId = Session::get('user_id');
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first() ?? (object)['userID' => $userId, 'userName' => 'Ahmad Irfan'];

        $rooms = DB::table('rooms')->orderBy('roomID', 'asc')->get();
        return view('rooms', compact('rooms', 'userProfile'));
    });

    Route::post('/book', function (Request $request) {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Access Denied.']);
        }

        $userId = Session::get('user_id');
        $roomID = $request->input('roomID');
        $bookingType = $request->input('bookingType', 'solo');

        $existing = DB::table('reservations')
            ->where('userID', $userId)
            ->where('bookingStatus', 'Confirmed')
            ->first();

        if ($existing) {
            return redirect('/student/dashboard')->withErrors(['error' => 'You already possess an active structural allocation receipt this semester.']);
        }

        DB::transaction(function () use ($userId, $roomID, $bookingType, $request) {
            if ($bookingType === 'group') {
                $logID = 'BK' . rand(1000, 9999);
                
                DB::table('reservations')->insert([
                    'logID' => $logID,
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
                            'userID' => $peerID,
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
                $logID = 'BK' . rand(1000, 9999);

                DB::table('reservations')->insert([
                    'logID' => $logID,
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

        return redirect('/student/dashboard')->with('success', 'Your hostel bedroom reservation pass has compiled successfully!');
    });

    Route::get('/bookings', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Please authenticate.']);
        }

        $userId = Session::get('user_id');
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first() ?? (object)['userID' => $userId, 'userName' => 'Ahmad Irfan'];

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
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first() ?? (object)['userID' => $userId, 'userName' => 'Ahmad Irfan'];

        return view('eligibility', compact('userProfile'));
    });

    Route::get('/announcements', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Unauthorized access. Please log in.']);
        }

        $userId = Session::get('user_id');
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first() ?? (object)['userID' => $userId, 'userName' => 'Ahmad Irfan'];

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

        $totalRooms = DB::table('rooms')->count();
        $occupiedRooms = DB::table('rooms')->where('currentOccupancy', '>=', 4)->count();
        $activeBookings = DB::table('reservations')->where('bookingStatus', 'Confirmed')->count();

        $totalBedsPool = $totalRooms * 4;
        $availableRooms = $totalBedsPool - DB::table('rooms')->sum('currentOccupancy');
        
        if ($availableRooms < 0) { $availableRooms = 0; }

        $adminId = Session::get('user_id');
        $adminProfile = DB::table('hostel_users')->where('userID', $adminId)->first();

        return view('admin_dashboard', compact('availableRooms', 'occupiedRooms', 'activeBookings', 'totalRooms', 'adminProfile'));
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

    // --- ADMIN ROOM BULK RESERVATION OVERRIDE HANDLER ---
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

    // --- ADMIN ROOM UNRESERVE / RELEASE HANDLER ---
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

    // --- ADMINISTRATIVE RESERVATION PASS REVOCATION INTERCEPTOR (WITH REASON STRINGS) ---
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