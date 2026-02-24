<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // For single company mode, we simply take the first company.
        $company = Company::firstOrFail();
        return view('admin.settings.index', compact('company'));
    }

    public function update(Request $request)
    {
        $company = Company::firstOrFail();

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_description' => 'nullable|string',
            'company_logo' => 'nullable|image|max:2048', // 2MB Max
            'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:1024', // 1MB Max
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'company_profile_url' => 'nullable|url|max:255',
            'hero_title' => 'nullable|string|max:255',
            'hero_description' => 'nullable|string',
            'hero_image' => 'nullable|image|max:2048',
            'hero_cta_text' => 'nullable|string|max:50',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|integer|min:1',
            'company_website' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('hero_image')) {
            if ($company->hero_image) {
                Storage::disk('public')->delete($company->hero_image);
            }
            $validated['hero_image'] = $request->file('hero_image')->store('hero', 'public');
        }

        if ($request->hasFile('company_logo')) {
            // Delete old logo if it exists
            if ($company->company_logo) {
                Storage::disk('public')->delete($company->company_logo);
            }
            $validated['company_logo'] = $request->file('company_logo')->store('company-logos', 'public');
        }

        if ($request->hasFile('favicon')) {
            // Delete old favicon if it exists
            if ($company->favicon) {
                Storage::disk('public')->delete($company->favicon);
            }
            $validated['favicon'] = $request->file('favicon')->store('company-favicons', 'public');
        }

        $company->update($validated);

        return redirect()->back()->with('success', 'Company settings updated successfully.');
    }
}
