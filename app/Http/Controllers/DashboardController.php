<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.index', [
        'title' => 'Dashboard',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
          return view('dashboard.show', [
        'title' => 'Detail User',
        'user' => Auth::user(),
    ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
          return view('dashboard.edit', [
        'title' => 'Edit User',
        'user' => Auth::user(),
    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
      $user = Auth::user();  
    $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'password_confirmation' => 'nullable|same:password',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            'password.min' => 'Password minimal 8 karakter.',

            'password_confirmation.same' => 'Konfirmasi password tidak sesuai dengan password.',

            'avatar.image' => 'File avatar harus berupa gambar.',
            'avatar.mimes' => 'Avatar harus berformat JPG, JPEG, atau PNG.',
            'avatar.max' => 'Ukuran avatar maksimal 1 MB.',

        ]);

        try {

            DB::beginTransaction();

            if ($request->hasFile('avatar')) {

                $validated['avatar'] = $request->file('avatar')->store('avatar', 'public');

                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }

            if ($request->filled('password')) {
                $validated['password'] = Hash::make($request->password);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            DB::commit();

            return to_route('dashboard.show')->with('success', 'Data berhasil diubah');

        } catch (\Exception $e) {

            DB::rollBack();

            return to_route('dashboard.edit')->with('error', 'Data gagal diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
