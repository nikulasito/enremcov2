<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display login page.
     *
     * @return Renderable
     */
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $user = User::findForLogin($credentials['username']);

        if ($user && Auth::attempt(['id' => $user->getAuthIdentifier(), 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->isExecAdmin()) {
                return redirect()->route('admin.exec-dashboard');
            }
            if ($user->isCreditOfficer()) {
                return redirect()->route('admin.credit-officer.dashboard');
            }
            if ($user->isRegularAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended('dashboard');
        }

        // Log the error and redirect back with a message
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');

    }

    protected function username()
    {
        return 'username';
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
