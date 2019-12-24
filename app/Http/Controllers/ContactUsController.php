<?php

namespace App\Http\Controllers;


use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class ContactUsController extends Controller
{
    // Show the contact form
    public function show()
    {
        return view('contact');
    }
    // Confirmation mail -> user + 'carbon copy'
    public function sendEmail(Request $request)
    {
        // Validate form
        // fx validate always comes first inside the parent fx
        // if fx validate ok -> rest of code is executed
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email',
            'contact' => 'required',
            'message' => 'required|min:10'
        ]);
        // Send email through the controller
        // new instance email of contactmail mailable class
        // Pass validated from data to the mailable
        $email = new ContactMail($request);
        // Recipient
        Mail::to($request) // or Mail::to($request->email, $request->name)
        // send fx() method -> $email = parameter
        ->send($email);
        // Flash filled-in form values to the session
        $request->flash();
        // Flash a success message to the session
        session()->flash('success', 'Thank you for your message.<br>We\'ll contact you as soon as possible.');
        // Redirect to the contact-us link ( NOT to view('contact')!!! )
        // redirected so no request helper fx
        return redirect('contact-us');
    }
}
