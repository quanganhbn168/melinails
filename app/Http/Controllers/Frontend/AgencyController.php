<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\AgencyRequest;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use App\Mail\AgencyRequestNotification;

class AgencyController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('frontend.agency.index', compact('setting'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|max:20',
            'email'     => 'nullable|email|max:255',
            'shop_name' => 'nullable|string|max:255',
            'address'   => 'nullable|string|max:255',
            'area'      => 'nullable|string|max:255',
            'details'   => 'nullable|string',
        ]);

        // Create Record
        $agency = AgencyRequest::create($data);

        // Send Email to Admin
        try {
            $setting = Setting::first();
            if ($setting && $setting->email) {
                Mail::to($setting->email)->send(new AgencyRequestNotification($agency));
            }
        } catch (\Exception $e) {
            \Log::error("Agency Email Error: " . $e->getMessage());
        }

        return redirect()->route('thank-you')->with('success', 'Đăng ký đại lý thành công! Chúng tôi sẽ liên hệ sớm.');
    }
}
