<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class ContactUsController extends Controller
{
    public function contactUs()
    {
        return view('frontend.pages.contact-us');
    }
}
