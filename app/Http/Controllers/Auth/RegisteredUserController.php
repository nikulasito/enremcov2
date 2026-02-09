<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use App\Jobs\SendWelcomeEmail;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse | RedirectResponse
    {
        \Log::info("Received registration request: ", $request->all()); // Log request data

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'position' => 'required|string|max:255',
            // 'office' => 'required|in:Single,Married,Divorced,Widowed',
            'office' => 'required|in:Department of Environment and Natural Resources,Environmental Management Bureau,Mines and Geosciences Bureau,PENRO Bukidnon,PENRO Camiguin,PENRO Lanao Del Norte,PENRO Misamis Occidental,PENRO Misamis Oriental,CENRO Don Carlos,CENRO Manolo Fortich,CENRO Valencia,CENRO Talakag,CENRO Iligan,CENRO Kolambugan,CENRO Oroquieta,CENRO Ozamis,CENRO Gingoog,CENRO Initao',
            'address' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
            'sex' => 'required|in:Male,Female,Other',
            'marital_status' => 'required|in:Single,Married,Divorced,Widowed',
            'annual_income' => 'required|numeric|min:0',
            'contact_no' => 'required|digits:11', // Fix: Use digits validation
            'beneficiaries' => 'required|string|max:255',
            'birth_month' => 'required|numeric|min:1|max:12',
            'birth_day' => 'required|numeric|min:1|max:31',
            'birth_year' => 'required|numeric|min:1900|max:' . date('Y'),
            'education' => 'required|string|max:255',
            'employment_status' => 'required|in:Regular,Casual,Contract of Service',
            'shares' => 'required|numeric|min:0',
            'savings' => 'required|numeric|min:0',
            'username' => 'required|string|max:255|unique:users,username', // Fix: Unique username check
            'photo' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048', // Validate photo
        ]);

        // **Check validation result**
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }


        $birthdate = sprintf("%04d-%02d-%02d", $request->birth_year, $request->birth_month, $request->birth_day);

        // Force uppercase for fields
        $name = strtoupper($request->name);
        $position = strtoupper($request->position);
        $office = strtoupper($request->office);
        $address = strtoupper($request->address);
        $religion = strtoupper($request->religion);
        $sex = strtoupper($request->sex);
        $marital_status = strtoupper($request->marital_status);
        $beneficiaries = strtoupper($request->beneficiaries);
        $education = strtoupper($request->education);

        // Generate Employee ID (Format: DENR-XXX-XX-)
        $lastUser = User::latest('id')->first();
        $nextID = $lastUser ? $lastUser->id + 1 : 1;
        $employeeID = sprintf("ENREMCO-%03d-%03d", floor($nextID / 1000), $nextID % 1000);

        // Handle the file upload
        $photoPath = null; // Default to null if no photo is uploaded
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public'); // Save in storage/app/public/photos
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'position' => $request->position,
            'office' => $request->office,
            'address' => $request->address,
            'religion' => $request->religion,
            'sex' => $request->sex,
            'marital_status' => $request->marital_status,
            'annual_income' => $request->annual_income,
            'contact_no' => $request->contact_no,
            'beneficiaries' => $request->beneficiaries,
            'birthdate' => $birthdate,  // Store formatted birthdate
            'education' => $request->education,
            'employee_ID' => $employeeID,
            'shares' => $request->shares,
            'savings' => $request->savings,
            'username' => $request->username,
            'photo' => $photoPath,
        ]);

        // Create the Member
        $user->photo = $photoPath; // Save the photo path
        $user->save();

        event(new Registered($user));

        // Auth::login($user);
        Auth::logout();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'user_id' => $user->id,
                'message' => 'Registration successful.'
            ]);
        } else {
            return redirect()->route('register')->with('success', 'Registration successful.');
        }

        // return redirect()->route('register')->with('registered', true);
        return redirect()->route('verification.notice');

        // return redirect()->route('dashboard');
        
        // Redirect to preview page with the user's details
        //return redirect()->route('user.preview', ['id' => $user->id]);
        //return redirect()->route('login')->with('info', 'Your registration is pending approval.');
        // return redirect()->route('verification.notice');
    
    }

    public function preview($id): View
    {
        $user = User::findOrFail($id);

        return view('auth.preview', compact('user'));
    }

    public function generatePdf($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $html = View::make('emails.registration-details', ['member' => $user])->render();

        $pdf = PDF::loadHTML($html)
            ->setOption('encoding', 'utf-8')
            ->setPaper('A4', 'portrait');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="ENREMCO_Registration_' . $user->employee_ID . '.pdf"',
        ]);
    }
}
