<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function home()
    {
        $title = 'Home';
        return view('website.home', compact('title'));
    }

    public function aboutUs()
    {
        $title = 'About Us';
        // dd($title);
        return view('website.about-us', compact('title'));

    }

    public function themes()
    {
        $title = 'Theme';
        return view('website.themes', compact('title'));
    }

    public function pricing()
    {
        $title = 'Pricing';
        return view('website.pricing', compact('title'));
    }


    public function contactUs()
    {
        $title = 'Contact Us';
        return view('website.contact-us', compact('title'));   
    }


    public function termsOfDealership()
    {
        $title = 'Terms of dealership';
        return view('website.terms-of-dealership', compact('title'));   
    }
    public function termsAndConditions()
    {
        $title = 'Terms and conditions';
        return view('website.terms-and-conditions', compact('title'));   
    }


    public function privacyPolicy()
    {
        $title = 'Privacy policy';
        return view('website.privacy-policy', compact('title'));   
    }

    public function termsOfUse()
    {
        $title = 'Terms of use';
        return view('website.terms-of-use', compact('title'));   
    }



    public function refundAndReturnPolicy()
    {
        $title = 'Refund and return policy';
        return view('website.refund-and-return-policy', compact('title'));   
    }


    public function siteMap()
    {
        $title = 'Site Map';
        return view('website.site-map', compact('title'));   
    }

    

    

}
