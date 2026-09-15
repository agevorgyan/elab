<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\SiteSettings;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('published', true)
            ->with(['features' => function ($q) {
                $q->orderBy('sort_order', 'asc');
            }])
            ->orderBy('sort_order', 'asc')
            ->get();

        $settings = SiteSettings::all()->pluck('value', 'key')->toArray();

        return view('pages.services.index', compact('services', 'settings'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)
            ->where('published', true)
            ->with(['features' => function ($q) {
                $q->orderBy('sort_order', 'asc');
            }])
            ->firstOrFail();

        $otherServices = Service::where('published', true)
            ->where('id', '!=', $service->id)
            ->take(3)
            ->get();

        $faqs = FAQ::where('published', true)->take(4)->get();
        $settings = SiteSettings::all()->pluck('value', 'key')->toArray();

        return view('pages.services.show', compact('service', 'otherServices', 'faqs', 'settings'));
    }
}
