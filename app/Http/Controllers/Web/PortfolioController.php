<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PortfolioCategory;
use App\Models\PortfolioProject;
use App\Models\SiteSettings;

class PortfolioController extends Controller
{
    public function index()
    {
        $categories = PortfolioCategory::orderBy('name', 'asc')->get();

        $projects = PortfolioProject::where('published', true)
            ->with(['categories', 'technologies'])
            ->orderBy('sort_order', 'asc')
            ->get();

        $settings = SiteSettings::all()->pluck('value', 'key')->toArray();

        return view('pages.portfolio.index', compact('categories', 'projects', 'settings'));
    }

    public function show($slug)
    {
        $project = PortfolioProject::where('slug', $slug)
            ->where('published', true)
            ->with(['categories', 'technologies'])
            ->firstOrFail();

        $relatedProjects = PortfolioProject::where('published', true)
            ->where('id', '!=', $project->id)
            ->with(['categories', 'technologies'])
            ->take(3)
            ->get();

        $settings = SiteSettings::all()->pluck('value', 'key')->toArray();

        return view('pages.portfolio.show', compact('project', 'relatedProjects', 'settings'));
    }
}
