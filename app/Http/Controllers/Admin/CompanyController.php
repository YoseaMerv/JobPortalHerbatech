<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::with('user')->latest()->paginate(10);
        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return view('admin.companies.show', compact('company'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        // Delete related user or just company profile? 
        // Usually deleting the user account cascades, but let's just delete company profile for now or user.
        // If company is a profile of a user, maybe we should delete the user?
        // Let's safe delete or just delete the company record.
        $company->delete();
        return redirect()->route('admin.companies.index')->with('success', 'Company deleted successfully.');
    }

    /**
     * Toggle company verification status.
     */
    public function toggleVerification(Company $company)
    {
        $company->update([
            'is_verified' => !$company->is_verified
        ]);

        $status = $company->is_verified ? 'verified' : 'unverified';
        return back()->with('success', "Company has been {$status}.");
    }
}
