<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DataUserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6',
            'role'        => 'required|integer|in:1,2,3,4',
            'is_approved' => 'required|in:0,1',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'is_approved' => $request->is_approved,
        ]);

        // Jika role vendor, otomatis buat record vendor
        if ($request->role == 4) {
            Vendor::firstOrCreate(['iduser' => $user->id], [
                'nama_vendor' => $request->nama_vendor ?? $request->name,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(int|string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, int|string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'role'        => 'required|integer|in:1,2,3,4',
            'is_approved' => 'required|in:0,1',
            'password'    => 'nullable|min:6',
        ]);

        $data = [
            'name'        => $request->name,
            'email'       => $request->email,
            'role'        => $request->role,
            'is_approved' => $request->is_approved,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Jika diganti jadi vendor, buat record vendor jika belum ada
        if ($request->role == 4) {
            Vendor::firstOrCreate(['iduser' => $user->id], [
                'nama_vendor' => $request->nama_vendor ?? $user->name,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy(int|string $id)
    {
        // Admin tidak bisa hapus dirinya sendiri
        if ((int)$id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatus(int|string $id)
    {
        $user = User::findOrFail($id);

        // Admin tidak bisa toggle status dirinya sendiri
        if ((int)$id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa mengubah status akun sendiri.'
            ], 403);
        }

        $user->is_approved = !$user->is_approved;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Status user berhasil diubah.',
            'status' => $user->is_approved,
            'status_text' => $user->is_approved ? 'Aktif' : 'Nonaktif',
            'badge_class' => $user->is_approved ? 'badge-success' : 'badge-danger'
        ]);
    }
}