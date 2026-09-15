<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use App\Models\SiteSettings;

class PageController extends Controller
{
    public function about()
    {
        $settings = SiteSettings::all()->pluck('value', 'key')->toArray();
        return view('pages.about', compact('settings'));
    }

    public function legal($slug)
    {
        $page = LegalPage::where('slug', $slug)->firstOrFail();
        $settings = SiteSettings::all()->pluck('value', 'key')->toArray();

        return view('pages.legal', compact('page', 'settings'));
    }
}
