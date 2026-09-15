<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function index()
    {
        $settings = SiteSettings::all()->pluck('value', 'key')->toArray();
        return view('pages.contact', compact('settings'));
    }

    public function submit(Request $request)
    {
        // Honeypot spam protection
        if ($request->filled('website_hp')) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Շնորհակալություն։ Ձեր հաղորդագրությունն ուղարկված է։']);
            }
            return back()->with('success', 'Շնորհակալություն։ Ձեր հաղորդագրությունն ուղարկված է։');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'project_type' => ['nullable', 'string', 'max:255'],
            'budget' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $lead = Lead::create([
            'id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'company' => $validated['company'] ?? null,
            'project_type' => $validated['project_type'] ?: 'Web Development',
            'budget' => $validated['budget'] ?: 'Not Specified',
            'message' => $validated['message'],
            'source' => 'Website Form',
            'status' => 'NEW',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Շնորհակալություն հարցման համար։ eLab Agency-ի մեր մասնագետը կկապվի Ձեզ հետ շատ կարճ ժամանակում։',
            ]);
        }

        return back()->with('success', 'Շնորհակալություն հարցման համար։ eLab Agency-ի մեր մասնագետը կկապվի Ձեզ հետ շատ կարճ ժամանակում։');
    }
}
