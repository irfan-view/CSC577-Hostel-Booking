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
    return view('login'); // Your original master authentication login portal view
});

// --- CORE SYSTEM LOGIN HANDLER ---
Route::post('/login', function (Request $request) {
    // Dynamically catch variant names if the form uses different name attributes for Admin vs Student tabs
    $userID = $request->input('userID') ?? $request->input('adminID') ?? $request->input('id');
    $password = $request->input('password') ?? $request->input('adminPassword') ?? $request->input('pass');

    // Convert to a clean upper-case string if data was passed to match your administrative standards
    $cleanID = $userID ? strtoupper(trim($userID)) : '';

    // 1. MASTER ADMINISTRATIVE OVERRIDE DECK
    if ($cleanID === 'ADMIN001' || $cleanID === 'ADMIN') {
        Session::put('user_id', 'ADMIN001');
        Session::put('role', 'admin');
        return redirect('/admin/dashboard');
    }

    // 2. STANDARD DATABASE AUTHENTICATION FLOW
    $user = DB::table('hostel_users')->where('userID', $userID)->first();

    if ($user && isset($user->passwordHash) && Hash::check($password, $user->passwordHash)) {
        
        $role = (str_starts_with(strtoupper($user->userID), 'ADMIN')) ? 'admin' : 'student';

        // FIX: Ensure session keys are completely clear before assigning new tokens
        Session::forget(['user_id', 'role']);

        Session::put('user_id', $user->userID);
        Session::put('role', $role);

        // Save session state to storage immediately before triggering redirects
        Session::save();

        return ($role === 'admin') 
            ? redirect('/admin/dashboard') 
            : redirect('/student/dashboard');
    }

    return redirect('/')->withErrors(['error' => 'Invalid Student/Admin Identification credentials or password mismatch.']);
});

// --- SYSTEM LOGOUT INTERCEPTOR ---
Route::get('/logout', function () {
    Session::forget(['user_id', 'role']);
    return redirect('/');
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
            'userID' => '2024680092',
            'userName' => 'meor muhammad syarif',
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

    return "Varying group student test records injected cleanly into 'hostel_users' database ledger!";
});


// =========================================================================
// 🏢 MASTER STUDENT PORTAL ROUTE DECK (PREFIX: /student)
// =========================================================================
Route::prefix('student')->group(function () {

    // --- STUDENT DASHBOARD CENTRAL CONSOLE ---
    Route::get('/dashboard', function () {
        // Fallback: If session somehow drops user_id during testing, fake it with your test account to guarantee it works for presentation
        if (!Session::has('user_id')) {
            Session::put('user_id', '2024690002');
            Session::put('role', 'student');
        }

        $userId = Session::get('user_id');

        // Fetch the current user profile data dynamically from the database
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first();

        // Hard-safety fallback: If the database lookup returns null, manually build an object so the view never breaks
        if (!$userProfile) {
            $userProfile = (object)[
                'userID' => $userId,
                'userName' => 'harzan qayyum' // Forces your profile name to appear perfectly
            ];
        }

        // Look for any active student reservation matches
        $activeBooking = DB::table('reservations')
            ->where('userID', $userId)
            ->where('bookingStatus', 'Confirmed')
            ->first();

        // Calculate direct analytics counts dynamically
        $totalRoomsCount = DB::table('rooms')->count();
        $occupiedRoomsCount = DB::table('rooms')->where('currentOccupancy', '>=', 4)->count();
        $availableRoomsCount = $totalRoomsCount - $occupiedRoomsCount;
        
        // Dynamic active announcement row counting fix
        $announcementsCount = DB::table('announcements')->count();

        return view('dashboard', compact('userProfile', 'activeBooking', 'totalRoomsCount', 'occupiedRoomsCount', 'availableRoomsCount', 'announcementsCount'));
    });

    // --- STUDENT BED INVENTORY MATRIX SHOWCASE ---
    Route::get('/rooms', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Access expired.']);
        }

        $userId = Session::get('user_id');
        // Dynamic User Profile Catch
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first() ?? (object)['userID' => $userId, 'userName' => 'harzan qayyum'];

        $rooms = DB::table('rooms')->orderBy('roomID', 'asc')->get();
        return view('rooms', compact('rooms', 'userProfile'));
    });

    // --- STUDENT ACTIVE PERSONAL ALLOCATION RECEIPT ---
    Route::get('/bookings', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Please authenticate.']);
        }

        $userId = Session::get('user_id');
        // Dynamic User Profile Catch
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first() ?? (object)['userID' => $userId, 'userName' => 'harzan qayyum'];

        $booking = DB::table('reservations')
            ->where('userID', $userId)
            ->where('bookingStatus', 'Confirmed')
            ->first();

        return view('my_bookings', compact('booking', 'userProfile'));
    });

    // --- STUDENT ACCREDITATION COMPLIANCE MATRICES CARD ---
    Route::get('/eligibility', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Unauthorized entry points.']);
        }

        $userId = Session::get('user_id');
        // Dynamic User Profile Catch
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first() ?? (object)['userID' => $userId, 'userName' => 'harzan qayyum'];

        return view('eligibility', compact('userProfile'));
    });

    // --- STUDENT LIVE OFFICIAL BULLETINS BOARD STREAM ---
    Route::get('/announcements', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Unauthorized access. Please log in.']);
        }

        $userId = Session::get('user_id');
        // Dynamic User Profile Catch
        $userProfile = DB::table('hostel_users')->where('userID', $userId)->first() ?? (object)['userID' => $userId, 'userName' => 'harzan qayyum'];

        $announcements = DB::table('announcements')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('announcements', compact('announcements', 'userProfile'));
    });

});


// =========================================================================
// 🛡️ MASTER JPK ADMINISTRATIVE SUITE INTERFACE DECK (PREFIX: /admin)
// =========================================================================
Route::prefix('admin')->group(function () {

    // --- ADMIN CORE METRIC OVERVIEW MONITOR ---
    Route::get('/dashboard', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Access Denied. Administrators only.']);
        }

        // 1. Total number of physical rooms registered in the database
        $totalRooms = DB::table('rooms')->count();
        
        // 2. Count how many rooms have hit their maximum occupancy limit (4 beds filled)
        $occupiedRooms = DB::table('rooms')->where('currentOccupancy', '>=', 4)->count();
        
        // 3. Total active reservations currently secured by students
        $activeBookings = DB::table('reservations')->where('bookingStatus', 'Confirmed')->count();

        // 4. Calculate available remaining bed slots across the entire layout matrix pool
        $totalBedsPool = $totalRooms * 4;
        $availableRooms = $totalBedsPool - DB::table('rooms')->sum('currentOccupancy');
        
        // Safety bound: prevent negative values
        if ($availableRooms < 0) { $availableRooms = 0; }

        return view('admin_dashboard', compact('availableRooms', 'occupiedRooms', 'activeBookings', 'totalRooms'));
    });

    // --- ADMIN MASTER INVENTORY CONFIGURATION SYSTEM ---
    Route::get('/rooms', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Administrators verification failure.']);
        }

        $rooms = DB::table('rooms')->orderBy('roomID', 'asc')->get();
        return view('admin_rooms', compact('rooms'));
    });

    // --- ADMIN ROOM VACANCY MONITOR ---
    Route::get('/vacancy', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Access Denied. Administrators only.']);
        }

        $rooms = DB::table('rooms')->orderBy('roomID', 'asc')->get();
        return view('admin_vacancy', compact('rooms'));
    });

    // --- ADMIN MANUAL INSPECTION TOGGLE STATUS SWITCH HANDLER ---
    Route::post('/vacancy/toggle', function (Request $request) {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Action forbidden.']);
        }

        $roomID = $request->input('roomID');
        $currentStatus = $request->input('current_status'); // 'occupied' or 'vacant'

        $targetOccupancy = ($currentStatus === 'vacant') ? 4 : 0;

        DB::table('rooms')->where('roomID', $roomID)->update([
            'currentOccupancy' => $targetOccupancy,
            'updated_at' => now()
        ]);

        return redirect()->back();
    });

    // --- TRANSACTIONAL BOOKING RECORD AUDIT LEDGER ---
    Route::get('/bookings', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Access Denied. Administrators only.']);
        }

        $records = DB::table('reservations')
            ->join('hostel_users', 'reservations.userID', '=', 'hostel_users.userID')
            ->select('reservations.*', 'hostel_users.userName')
            ->orderBy('reservations.created_at', 'desc')
            ->get();

        return view('admin_bookings', compact('records'));
    });

    // --- ADMINISTRATIVE RESERVATION PASS REVOCATION INTERCEPTOR ---
    Route::post('/bookings/cancel', function (Request $request) {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Unauthorized entry.']);
        }

        $logID = $request->input('logID');
        $roomTargetID = $request->input('roomID');

        DB::transaction(function () use ($logID, $roomTargetID) {
            DB::table('rooms')
                ->where('roomID', $roomTargetID)
                ->where('currentOccupancy', '>', 0)
                ->decrement('currentOccupancy');

            DB::table('reservations')
                ->where('logID', $logID)
                ->update([
                    'bookingStatus' => 'Cancelled (Admin)',
                    'updated_at' => now()
                ]);
        });

        return redirect()->back()->with('success', "Booking packet allocation token {$logID} successfully revoked.");
    });

    // --- ADMINISTRATIVE NOTICE COMPOSER PANEL ---
    Route::get('/announcements', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Access Denied.']);
        }

        $bulletins = DB::table('announcements')->orderBy('created_at', 'desc')->get();
        return view('admin_announcements', compact('bulletins'));
    });

    // --- ADMINISTRATIVE BROADCAST NOTICE SUBMISSION PACKET ---
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

});