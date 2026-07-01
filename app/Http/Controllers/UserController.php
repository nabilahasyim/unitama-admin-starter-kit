<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'name.required' => 'Nama tidak boleh kosong',
            'name.max' => 'Nama tidak boleh lebih dari :max karakter',

            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',

            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal :min karakter',

            'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong',
            'password_confirmation.same' => 'Konfirmasi password tidak sesuai',

            'avatar.image' => 'Avatar harus berupa gambar',
            'avatar.mimes' => 'Avatar harus berformat JPG, JPEG, atau PNG',
            'avatar.max' => 'Ukuran avatar maksimal 1 MB',

            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role yang dipilih tidak valid',
        ]);

        try {

            if ($request->file('avatar')) {
                $validated['avatar'] = $request->file('avatar')->store('avatar', 'public');
            }

            $validated['password'] = Hash::make($validated['password']);

            DB::beginTransaction();

            User::create($validated);

            DB::commit();

            return to_route('user.index')->withSuccess('Data berhasil ditambahkan');
        } catch (\Exception $e) {

            DB::rollBack();

            return to_route('user.create')->withError('Data gagal ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}