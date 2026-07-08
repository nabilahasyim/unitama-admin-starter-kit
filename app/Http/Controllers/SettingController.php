<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;



class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(Setting::first());
        return view('setting.index', [
        'title' => 'Setting',
        'setting' => Setting::first(),
    ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
    $validated = $request->validate([
    'app_name' => 'required|max:255',
    'copyright' => 'required|max:255',
    'login_title' => 'required|max:255',
    'keywords' => 'nullable|max:255',
    'description' => 'nullable|max:255',
    'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
], [
    'app_name.required' => 'Nama aplikasi wajib diisi.',
    'app_name.max' => 'Nama aplikasi maksimal 255 karakter.',

    'copyright.required' => 'Copyright wajib diisi.',
    'copyright.max' => 'Copyright maksimal 255 karakter.',

    'login_title.required' => 'Judul login wajib diisi.',
    'login_title.max' => 'Judul login maksimal 255 karakter.',

    'keywords.max' => 'Keywords maksimal 255 karakter.',

    'description.max' => 'Deskripsi maksimal 255 karakter.',

    'logo.image' => 'File logo harus berupa gambar.',
    'logo.mimes' => 'Logo harus berformat JPG, JPEG, atau PNG.',
    'logo.max' => 'Ukuran logo maksimal 1 MB.',
]);


        try {

            DB::beginTransaction();

            if ($request->hasFile('logo')) {

                $validated['logo'] = $request->file('logo')->store('logo', 'public');

                if ($setting->logo) {
                    Storage::disk('public')->delete($setting->logo);
                }
            }

            
            $setting->update($validated);

            DB::commit();

            return to_route('setting.index')->with('success', 'Data berhasil disimpan');

        } catch (\Exception $e) {

            DB::rollBack();

            return to_route('setting.index')->with('error', 'Data gagal disimpan');
        }
    }

}
