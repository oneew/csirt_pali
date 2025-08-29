<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Show the contact page
     */
    public function index()
    {
        return view('frontend.contact');
    }

    /**
     * Store a contact form submission
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'organization' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            // Store contact submission in database
            Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'organization' => $request->organization,
                'contact_type' => 'external', // Default type for frontend submissions
                'message' => $request->message,
                'status' => 'pending',
                'country' => 'Unknown', // Could be enhanced with IP geolocation
                'position' => null,
                'phone' => null,
            ]);

            return redirect()->route('contact')
                ->with('success', 'Thank you for your message! We will get back to you soon.');
                
        } catch (\Exception $e) {
            Log::error('Contact form submission failed: ' . $e->getMessage());
            
            return redirect()->route('contact')
                ->with('error', 'There was an error sending your message. Please try again later.')
                ->withInput();
        }
    }
}