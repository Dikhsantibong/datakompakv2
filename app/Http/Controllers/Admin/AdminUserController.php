<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class AdminUserController extends Controller
{
    /**
     * Available user roles in the system
     */
    private const AVAILABLE_ROLES = [
        'user' => 'User',
        'admin' => 'Admin',
        'super_admin' => 'Super Admin',
        'asman_unit' => 'ASMAN UNIT',
        'tl_ron' => 'TL RON',
        'tl_ep' => 'TL EP',
        'operator' => 'OPERATOR',
        'staf' => 'STAF',
        'tl_ron_upkd' => 'TL RON UPKD'
    ];

    public function index(Request $request)
    {
        $query = User::query();

        // Search berdasarkan nama atau email
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter berdasarkan role
        if ($role = $request->input('role')) {
            if (array_key_exists($role, self::AVAILABLE_ROLES)) {
                $query->where('role', $role);
            }
        }

        // Pagination
        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            return view('admin.users.index', compact('users'))->render();
        }

        return view('admin.users.index', [
            'users' => $users,
            'availableRoles' => self::AVAILABLE_ROLES
        ]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'availableRoles' => self::AVAILABLE_ROLES
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(array_keys(self::AVAILABLE_ROLES))],
        ]);

        try {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            Alert::success('Berhasil', 'Pengguna berhasil ditambahkan');
            return redirect()
                ->route('admin.users')
                ->with('success', 'Pengguna berhasil ditambahkan');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menambahkan pengguna: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan pengguna: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
            'availableRoles' => self::AVAILABLE_ROLES
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => ['required', Rule::in(array_keys(self::AVAILABLE_ROLES))],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            Alert::success('Berhasil', 'Pengguna berhasil diperbarui');
            return redirect()
                ->route('admin.users')
                ->with('success', 'Pengguna berhasil diperbarui');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat memperbarui pengguna: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui pengguna: ' . $e->getMessage());
        }
    }

    public function delete(User $user)
    {
        // Cek jika user mencoba menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
        }

        return view('admin.users.delete', compact('user'));
    }

    public function destroy(User $user)
    {
        try {
            // Cek jika user mencoba menghapus dirinya sendiri
            if ($user->id === auth()->id()) {
                return redirect()
                    ->route('admin.users')
                    ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
            }

            $user->delete();

            return redirect()
                ->route('admin.users')
                ->with('success', 'Pengguna berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users')
                ->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $query = User::query();

        // Search berdasarkan nama atau email
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter berdasarkan role
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // Ambil semua data yang sesuai dengan kriteria pencarian
        $users = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'users' => $users,
            'total' => User::count()
        ]);
    }
} 