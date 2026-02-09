<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Mail\RegistrationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use App\Exports\ApprovedMembersExport;
use Maatwebsite\Excel\Facades\Excel;

class MemberController extends Controller
{
    public function create()
    {
        return view('member.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_no' => 'required|string|max:15',
            'email' => 'required|email|unique:members,email',
            'office' => 'required|string|max:255',
            'religion' => 'nullable|string|max:255',
            'sex' => 'required|in:Male,Female,Other',
            'marital_status' => 'required|in:Single,Married,Divorced,Widowed',
            'annual_income' => 'required|numeric|min:0|max:1000000000',
            'beneficiaries' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'education' => 'nullable|string|max:255',
            'employee_ID' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'photo' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Handle photo upload (store in storage/app/public/photos)
        $photoPath = $request->file('photo')->store('photos', 'public');
        $data['photo'] = $photoPath;

        // Create the member from validated data
        $member = Member::create($data);

        // If you want to trigger built-in email verification (Member must implement MustVerifyEmail)
        if (method_exists($member, 'sendEmailVerificationNotification')) {
            try {
                $member->sendEmailVerificationNotification();
                \Log::info('Email verification notification sent', ['user_id' => $member->id]);
            } catch (\Exception $e) {
                \Log::error('Unable to send verification notification', ['err' => $e->getMessage()]);
                // continue — we still want to send the registration PDF/email below
            }
        }

        // Generate PDF from Blade view (uses Dompdf)
        try {
            $options = new Options();
            $options->set('defaultFont', 'Courier');
            $dompdf = new Dompdf($options);

            // Pass the saved $member to the view
            $html = view('emails.registration-details', ['member' => $member])->render();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Binary PDF output
            $pdfOutput = $dompdf->output();

            // Optional: save PDF to storage (public disk)
            $pdfFilePath = 'registration-pdfs/RegistrationDetails_' . $member->id . '.pdf';
            \Storage::disk('public')->put($pdfFilePath, $pdfOutput);

            \Log::info('PDF generated for registration', ['user_id' => $member->id, 'pdf' => $pdfFilePath]);
        } catch (\Exception $e) {
            \Log::error('PDF generation failed', ['error' => $e->getMessage()]);
            $pdfOutput = null;
        }

        // Send email without PDF
        try {
            \Mail::mailer('brevo')
                ->to($member->email)
                ->send(new RegistrationEmail($member));

            \Log::info('Registration email sent', ['user_id' => $member->id, 'email' => $member->email]);
        } catch (\Exception $e) {
            \Log::error('Registration mail exception', ['error' => $e->getMessage(), 'user_id' => $member->id]);
        }

        // Redirect user to verification page or back with success message
        return redirect()->route('verification.notice')->with('success', 'Registration submitted successfully! Please verify your email.');
    }


    public function show()
    {
        $user = auth()->user(); // Get the authenticated user
        return view('member.profile', [
            // optionally compute and pass:
            // 'shareCapital' => $shareCapital,
            // 'nextRenewal' => 'January 2025',
            // 'membershipType' => 'Regular',
        ]);

    }


    public function downloadMembers()
    {

        $members = User::where('is_admin', '!=', 1)
            ->where('status', 'active')
            ->select('id', 'name', 'office', 'status', 'date_approved', 'date_inactive', 'date_reactive')
            ->get();

        return Excel::download(new ApprovedMembersExport($members), 'approved_members.xlsx');
    }
}
