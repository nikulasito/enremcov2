<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function index(string $page)
    {
        if (view()->exists("pages.{$page}")) {
            return view("pages.{$page}");
        }

        return abort(404);
    }

    public function vr()
    {
        return view("pages.virtual-reality");
    }

    public function rtl()
    {
        return view("pages.rtl");
    }

    public function profile()
    {
        return view("pages.profile-static");
    }

    public function signin()
    {
        return view("pages.sign-in-static");
    }

    public function signup()
    {
        return view("pages.sign-up-static");
    }

    public function contact()
    {
        return view('contact');
    }

    public function submitContact(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $recipient = env('CONTACT_RECEIVER_EMAIL', env('MAIL_FROM_ADDRESS', 'support@enremco.com'));

        try {
            $body = "New contact message from ENREMCO website\n\n"
                . "Name: {$data['name']}\n"
                . "Email: {$data['email']}\n"
                . "Subject: {$data['subject']}\n\n"
                . "Message:\n{$data['message']}\n";

            Mail::raw($body, function ($mail) use ($data, $recipient) {
                $mail->to($recipient)
                    ->replyTo($data['email'], $data['name'])
                    ->subject('[Contact Form] ' . $data['subject']);
            });
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'contact' => 'Unable to send your message right now. Please try again later.',
                ]);
        }

        return back()->with('contact_success', 'Your message has been sent successfully.');
    }
}
