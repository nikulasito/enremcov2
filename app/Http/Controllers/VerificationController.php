<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid or expired verification link.');
        }

        // Activate the account
        $user->email_verification_token = null;
        $user->status = 'Active'; // Change status to active
        $user->save();

        return redirect()->route('login')->with('success', 'Your account has been verified. You can now log in.');
    }

}
