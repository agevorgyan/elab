<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\PortfolioCategory;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\SiteSettings;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('published', true)
            ->with(['features' => function ($q) {
                $q->orderBy('sort_order', 'asc');
            }])
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get();

        $portfolioCategories = PortfolioCategory::orderBy('name', 'asc')->get();

        $portfolioProjects = PortfolioProject::where('published', true)
            ->with(['categories', 'technologies'])
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get();

        $testimonials = Testimonial::where('published', true)
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get();

        $faqs = FAQ::where('published', true)
            ->orderBy('sort_order', 'asc')
            ->take(8)
            ->get();

        $settings = SiteSettings::all()->pluck('value', 'key')->toArray();

        return view('pages.home', compact(
            'services',
            'portfolioCategories',
            'portfolioProjects',
            'testimonials',
            'faqs',
            'settings'
        ));
    }
}
