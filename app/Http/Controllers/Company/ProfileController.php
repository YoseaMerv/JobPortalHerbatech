<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $company = $user->company;
        return view('company.profile.edit', compact('user', 'company'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'company_phone' => 'nullable|string|max:20',
            'company_website' => 'nullable|url|max:255',
            'company_address' => 'nullable|string',
            'company_description' => 'required|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $user->update(['name' => $request->name]);

        $companyData = $request->only([
            'company_name', 'company_email', 'company_phone', 
            'company_website', 'company_address', 'company_description'
        ]);

        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::delete($company->logo);
            }
            $companyData['logo'] = $request->file('logo')->store('company-logos', 'public');
        }

        $company->update($companyData);

        return back()->with('success', 'Profile updated successfully.');
    }
}
