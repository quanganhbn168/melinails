<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\ConsultingRequest;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConsultingRequestNotification;

class ConsultingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('frontend.consulting.index', compact('setting'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|max:20',
            'email'     => 'nullable|email|max:255',
            'company'   => 'nullable|string|max:255',
            'address'   => 'nullable|string|max:255',
            'details'   => 'nullable|string',
            'budget'    => 'nullable|string|max:255',
            'file'      => 'nullable|file|max:10240|mimes:jpeg,png,pdf,doc,docx,zip,cad,dwg', // 10MB
        ]);

        // Handle File Upload
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('consulting_files', 'public');
            $data['file_path'] = $filePath;
        }
        unset($data['file']); // Remove file object from array

        // Create Record
        $consulting = ConsultingRequest::create($data);

        // Send Email to Admin
        try {
            $setting = Setting::first();
            if ($setting && $setting->email) {
                Mail::to($setting->email)->send(new ConsultingRequestNotification($consulting));
            }
        } catch (\Exception $e) {
            \Log::error("Consulting Email Error: " . $e->getMessage());
        }

        return redirect()->route('thank-you')->with('success', 'Yêu cầu tư vấn của bạn đã được gửi thành công!');
    }
}
