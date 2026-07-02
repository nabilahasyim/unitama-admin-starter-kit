<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.index', [
            'title' => 'User',
            'users' => User::latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create', [
            'title' => 'Tambah User',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'role' => 'required|in:Superadmin,Admin',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',

            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.same' => 'Konfirmasi password tidak sesuai dengan password.',

            'avatar.image' => 'File avatar harus berupa gambar.',
            'avatar.mimes' => 'Avatar harus berformat JPG, JPEG, atau PNG.',
            'avatar.max' => 'Ukuran avatar maksimal 1 MB.',

            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
        ]);

        try {

            DB::beginTransaction();

            if ($request->hasFile('avatar')) {
                $validated['avatar'] = $request->file('avatar')->store('avatar', 'public');
            }

            $validated['password'] = Hash::make($request->password);
            $validated['email_verified_at'] = now();

            User::create($validated);

            DB::commit();

            return to_route('user.index')->with('success', 'Data berhasil ditambahkan');

        } catch (\Exception $e) {

            DB::rollBack();

            return to_route('user.create')->with('error', 'Data gagal ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('user.show', [
        'title' => 'Detail User',
        'user' => $user,
    ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('user.edit', [
            'title' => 'Edit User',
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'password_confirmation' => 'nullable|same:password',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'role' => 'required|in:Superadmin,Admin',
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

            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
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

            return to_route('user.index')->with('success', 'Data berhasil diubah');

        } catch (\Exception $e) {

            DB::rollBack();

            return to_route('user.edit', $user)->with('error', 'Data gagal diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {

            DB::beginTransaction();

            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->delete();

            DB::commit();

            return to_route('user.index')->with('success', 'Data berhasil dihapus');

        } catch (\Exception $e) {

            DB::rollBack();

            return to_route('user.index')->with('error', 'Data gagal dihapus');
        }
    }
}