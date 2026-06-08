<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        // Pastikan hanya Admin atau Pimpinan yang bisa akses
        if (!auth()->user()->is_admin && !auth()->user()->is_pimpinan) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::orderBy('name')->get()->map(function ($user) {
            // Tentukan label role berdasarkan boolean
            $role = 'Staff';
            if ($user->is_pimpinan) $role = 'Pimpinan';
            if ($user->is_admin) $role = 'Admin';

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role,
                'is_admin' => $user->is_admin,
                'is_pimpinan' => $user->is_pimpinan,
            ];
        });

        return Inertia::render('UserManagement/Index', [
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string|in:admin,pimpinan,staff',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->role === 'admin',
            'is_pimpinan' => $request->role === 'pimpinan',
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|string|in:admin,pimpinan,staff',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_admin = $request->role === 'admin';
        $user->is_pimpinan = $request->role === 'pimpinan';

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Mencegah user menghapus dirinya sendiri
        if (auth()->id() === $user->id) {
            return redirect()->back()->withErrors(['message' => 'Anda tidak bisa menghapus akun Anda sendiri.']);
        }

        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}