<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Tampilkan daftar semua pengguna
     */
    public function index()
    {
        $users = User::latest()->paginate(15);
        $totalUsers = User::count();
        $customerCount = User::where('role', 'customer')->orWhereNull('role')->count();
        $adminCount = User::where('role', 'admin')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'customerCount', 'adminCount'));
    }

    /**
     * Ubah role pengguna (Admin / Customer)
     */
    public function toggleRole(User $user)
    {
        $newRole = ($user->role === 'admin') ? 'customer' : 'admin';
        $user->update(['role' => $newRole]);

        return redirect()->route('admin.users.index')
            ->with('success', "Role pengguna {$user->name} berhasil diubah menjadi " . ucfirst($newRole) . "!");
    }

    /**
     * Hapus akun pengguna
     */
    public function destroy(User $user)
    {
        // Cegah admin menghapus akunnya sendiri
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dihapus!');
    }
}
