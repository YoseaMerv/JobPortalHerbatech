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
        // Load relasi company agar data selalu siap di view
        $company = $user->company; 
        
        return view('company.profile.edit', compact('user', 'company'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        // 1. Validasi Input (Pastikan name-nya sesuai dengan <input name="..."> di Blade)
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'company_description' => 'required|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'company_logo.max' => 'Ukuran logo maksimal adalah 2MB.',
            'company_logo.image' => 'File harus berupa gambar (JPG/PNG).',
        ]);

        // 2. Update Nama User (Pengelola/Kontak Utama)
        $user->update(['name' => $request->name]);

        // 3. Persiapkan Data Perusahaan
        $companyData = [
            'company_name' => $request->company_name,
            'company_email' => $request->company_email,
            'company_description' => $request->company_description,
        ];

        // 4. Logika Upload Logo
        if ($request->hasFile('company_logo')) {
            // Hapus logo lama jika ada (cek dulu apakah objek $company ada)
            if ($company && $company->company_logo && Storage::disk('public')->exists($company->company_logo)) {
                Storage::disk('public')->delete($company->company_logo);
            }
            
            // Simpan logo baru
            $path = $request->file('company_logo')->store('company-logos', 'public');
            $companyData['company_logo'] = $path;
        }

        // 5. Update atau Create data perusahaan (Anti-Error jika data belum ada)
        $user->company()->updateOrCreate(
            ['user_id' => $user->id],
            $companyData
        );

        return back()->with('success', 'Profil perusahaan Anda berhasil diperbarui!');
    }
}