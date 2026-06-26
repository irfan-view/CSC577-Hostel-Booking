<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
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

    if ($user && isset($user->passwordHash) && Illuminate\Support\Facades\Hash::check($password, $user->passwordHash)) {
        
        $role = (str_starts_with(strtoupper($user->userID), 'ADMIN')) ? 'admin' : 'student';

        Session::put('user_id', $user->userID);
        Session::put('role', $role);

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


// =========================================================================
// 🏢 MASTER STUDENT PORTAL ROUTE DECK (PREFIX: /student)
// =========================================================================
Route::prefix('student')->group(function () {

    // --- STUDENT DASHBOARD CENTRAL CONSOLE ---
    // --- STUDENT DASHBOARD CENTRAL CONSOLE ---
    Route::get('/dashboard', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Unauthorized entry points.']);
        }

        $userId = Session::get('user_id');

        // Look for any active student reservation matches
        $activeBooking = DB::table('reservations')
            ->where('userID', $userId)
            ->where('bookingStatus', 'Confirmed')
            ->first();

        // Calculate direct analytics counts dynamically
        $totalRoomsCount = DB::table('rooms')->count();
        $occupiedRoomsCount = DB::table('rooms')->where('currentOccupancy', '>=', 4)->count();
        $availableRoomsCount = $totalRoomsCount - $occupiedRoomsCount;

        // NEW FIX: Dynamic live counter to sync the dashboard card with your database rows
        $announcementsCount = DB::table('announcements')->count();

        // Pass $announcementsCount safely into your compact parameters array context
        return view('dashboard', compact('activeBooking', 'totalRoomsCount', 'availableRoomsCount', 'announcementsCount'));
    });

    // --- STUDENT BED INVENTORY MATRIX SHOWCASE ---
    Route::get('/rooms', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Access expired.']);
        }

        $rooms = DB::table('rooms')->orderBy('roomID', 'asc')->get();
        return view('rooms', compact('rooms'));
    });

    // --- TRANSACTIONAL ROOM PASS ALLOCATION SUBMISSION HANDLER ---
    Route::post('/book', function (Request $request) {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Access Denied.']);
        }

        $userId = Session::get('user_id');
        $roomID = $request->input('roomID');
        $bookingType = $request->input('bookingType', 'solo');

        // Enforcement validation rule constraints check
        $existing = DB::table('reservations')
            ->where('userID', $userId)
            ->where('bookingStatus', 'Confirmed')
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'You already possess an active structural allocation receipt this semester.');
        }

        DB::transaction(function () use ($userId, $roomID, $bookingType, $request) {
            if ($bookingType === 'group') {
                // Multi-bed group registration transaction packet mapping
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

                // Bind peer student identities down to the record row stack
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
                // Solo layout room reservation insertion entry points
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

        return redirect('/student/bookings')->with('success', 'Your hostel bedroom reservation pass has compiled successfully!');
    });

    // --- STUDENT ACTIVE PERSONAL ALLOCATION RECEIPT ---
    Route::get('/bookings', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Please authenticate.']);
        }

        $booking = DB::table('reservations')
            ->where('userID', Session::get('user_id'))
            ->where('bookingStatus', 'Confirmed')
            ->first();

        return view('my_bookings', compact('booking'));
    });

    // --- STUDENT PERSONAL CANCELLATION REQUEST DISPATCHER ---
    Route::post('/cancel-booking', function (Request $request) {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Action forbidden.']);
        }

        $bookingID = $request->input('bookingID');

        $record = DB::table('reservations')->where('logID', $bookingID)->first();

        if ($record) {
            DB::transaction(function () use ($record, $bookingID) {
                // Decrement room matrix capacity metrics configuration counters
                DB::table('rooms')->where('roomID', $record->roomTargetID)->decrement('currentOccupancy');
                
                // Set state status to Cancelled to maintain transaction historical archives safely
                DB::table('reservations')->where('logID', $bookingID)->update([
                    'bookingStatus' => 'Cancelled',
                    'updated_at' => now()
                ]);
            });
        }

        return redirect()->back()->with('success', 'Your bedroom reservation has been cancelled and bed spaces freed up.');
    });

    // --- STUDENT ACCREDITATION COMPLIANCE MATRICES CARD ---
    Route::get('/eligibility', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Unauthorized entry points.']);
        }
        return view('eligibility');
    });

    // --- STUDENT LIVE OFFICIAL BULLETINS BOARD STREAM ---
    Route::get('/announcements', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'student') {
            return redirect('/')->withErrors(['error' => 'Unauthorized access. Please log in.']);
        }

        // Pull dynamic alerts directly from database query stream to resolve undefined variable error
        $announcements = DB::table('announcements')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('announcements', compact('announcements'));
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
        
        // 3. Total active reservations currently secured by students (Expected as $activeBookings on line 99)
        $activeBookings = DB::table('reservations')->where('bookingStatus', 'Confirmed')->count();

        // 4. Calculate available remaining bed slots across the entire layout matrix pool
        $totalBedsPool = $totalRooms * 4;
        $availableRooms = $totalBedsPool - DB::table('rooms')->sum('currentOccupancy');
        
        // Safety bound: prevent negative values
        if ($availableRooms < 0) { $availableRooms = 0; }

        // Bind variables cleanly using matching key alignments
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

    // --- ADMIN ROOM VACANCY MONITOR (UNIFIED CARDS SLIDER FILTER VIEW) ---
    Route::get('/vacancy', function () {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Access Denied. Administrators only.']);
        }

        // Fetch complete room register list so clientside dynamic rows slide accurately
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

        // Compute inversion targets updates logic cleanly
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

        // Inner join query compilation connecting allocation records with descriptive username cards
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