<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {


        $user = auth()->user()->fresh();

        // Offices as array (you said you don't want an Office model)
        $offices = [
            "Department of Environment and Natural Resources",
            "Environmental Management Bureau",
            "Mines and Geosciences Bureau",
            "PENRO Bukidnon",
            "PENRO Camiguin",
            "PENRO Lanao Del Norte",
            "PENRO Misamis Occidental",
            "PENRO Misamis Oriental",
            "CENRO Don Carlos",
            "CENRO Manolo Fortich",
            "CENRO Valencia",
            "CENRO Talakag",
            "CENRO Iligan",
            "CENRO Kolambugan",
            "CENRO Oroquieta",
            "CENRO Ozamis",
            "CENRO Gingoog",
            "CENRO Initao",
        ];

        $sexes = ['Male', 'Female', 'Other'];
        $maritalStatuses = ['Single', 'Married', 'Divorced', 'Widowed'];

        return view('profile.edit', compact('user', 'offices', 'sexes', 'maritalStatuses'));
    }

    public function changepassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $user = Auth::user();
    
        // Verify the current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }
    
        // Update the password
        $user->update([
            'password' => Hash::make($request->password),
        ]);
    
        return back()->with('status', 'password-updated');
    }

    public function editPassword()
    {
        return view('profile.update-password-form');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'password' => 'nullable|string|confirmed|min:8',
            'position' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'sex' => 'nullable|in:Male,Female,Other',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'annual_income' => 'nullable|numeric|min:0',
            'contact_no' => 'nullable|numeric|min:11',
            'beneficiaries' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'education' => 'nullable|string|max:255',
            'employee_ID' => 'nullable|string|max:255',
            'shares' => 'nullable|numeric|min:0',
            'savings' => 'nullable|numeric|min:0',
            'username' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Validate photo
            // Add validation rules for other fields
        ]);

    // Handle the profile photo
    if ($request->hasFile('photo')) {
        // Delete old photo if exists
        if ($user->photo && \Storage::disk('public')->exists($user->photo)) {
            \Storage::disk('public')->delete($user->photo);
        }

        // Move new photo to public storage
        $photoPath = $request->file('photo')->store('photos', 'public');
        $validatedData['photo'] = $photoPath;
    }
    
       // Update user fields
        // $user->update($validatedData);
         $request->user()->update($validatedData);

        auth()->setUser(auth()->user()->fresh());

    return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Optionally validate the request (e.g., confirm password)
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        // Delete the authenticated user
        $user->delete();

        // Log the user out
        Auth::logout();

        // Redirect to the homepage or login page
        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}
