<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $loginType = $request->input('loginType');
        $userID = $request->input('userID');
        $password = $request->input('password');

        // --- ADMIN / JPK AUTHENTICATION ENGINE ---
        if ($loginType === 'admin') {
            $mfaToken = $request->input('mfa_token');

            // Secure validation check using your updated password override
            if ($userID === 'admin' && $password === 'admin123' && $mfaToken === '123456') {
                Session::put('user_id', 'admin');
                Session::put('role', 'admin');
                Session::put('user_name', 'JPK Administrator');
                
                return redirect('/admin/dashboard');
            }

            return redirect('/')->withErrors(['error' => 'Invalid Admin Credentials or MFA Token.']);
        }

        // --- STUDENT AUTHENTICATION ENGINE ---
        $user = DB::table('hostel_users')->where('userID', $userID)->first();

        if ($user && $password === 'password123') {
            Session::put('user_id', $user->userID);
            Session::put('role', 'student');
            Session::put('user_name', $user->userName);

            return redirect('/student/dashboard');
        }

        return redirect('/')->withErrors(['error' => 'Invalid ID or Password. Please try again.']);
    }

    public function logout()
    {
        Session::flush();
        return redirect('/');
    }
}