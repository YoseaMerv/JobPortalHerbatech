<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan.
     */
    public function index()
    {
        // Ambil data pertama atau buat jika belum ada
        $company = Company::first();

        if (!$company) {
            $company = Company::create([
                'company_name' => 'HerbaTech Job Portal',
                'industry' => 'Healthcare & Technology',
                'is_active' => true
            ]);
        }

        return view('admin.settings.index', compact('company'));
    }

    /**
     * Proses pembaruan data pengaturan.
     */
    public function update(Request $request)
    {
        $company = Company::firstOrFail();

        // 1. Validasi Input
        $validated = $request->validate([
            'company_name'        => 'required|string|max:255',
            'company_description' => 'nullable|string',
            'company_logo'        => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'favicon'             => 'nullable|image|mimes:ico,png,jpg,jpeg|max:1024',
            'facebook'            => 'nullable|url|max:255',
            'twitter'             => 'nullable|url|max:255',
            'linkedin'            => 'nullable|url|max:255',
            'instagram'           => 'nullable|url|max:255',
            'company_profile_url' => 'nullable|url|max:255',
            'hero_title'          => 'nullable|string|max:255',
            'hero_description'    => 'nullable|string',
            'hero_image'          => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'hero_cta_text'       => 'nullable|string|max:50',
            'industry'            => 'nullable|string|max:255',
            'company_size'        => 'nullable|integer|min:1',
            'company_website'     => 'nullable|url|max:255',
        ]);

        // 2. Handle Upload Logo Perusahaan
        if ($request->hasFile('company_logo')) {
            // Hapus logo lama jika ada
            if ($company->company_logo) {
                Storage::disk('public')->delete($company->company_logo);
            }
            // Simpan yang baru dan update array $validated
            $validated['company_logo'] = $request->file('company_logo')->store('company/logos', 'public');
        }

        // 3. Handle Upload Favicon
        if ($request->hasFile('favicon')) {
            if ($company->favicon) {
                Storage::disk('public')->delete($company->favicon);
            }
            $validated['favicon'] = $request->file('favicon')->store('company/favicons', 'public');
        }

        // 4. Handle Upload Hero Image
        if ($request->hasFile('hero_image')) {
            if ($company->hero_image) {
                Storage::disk('public')->delete($company->hero_image);
            }
            $validated['hero_image'] = $request->file('hero_image')->store('company/hero', 'public');
        }

        // 5. Update data ke Database
        // Baris ini akan mengambil semua nilai dari array $validated yang sudah kita proses di atas
        $company->update($validated);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan identitas portal berhasil diperbarui.');
    }
}