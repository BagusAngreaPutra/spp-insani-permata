<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\LogAktivitas;
use App\Support\AdminPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        $admin = Admin::with('permissions')->get();
        return view('admin.index', compact('admin'));
    }

    public function create()
    {
        return view('admin.create', [
            'permissionGroups' => AdminPermission::groups(),
        ]);
    }

    public function store(Request $request)
    {
        $canManagePermissions = Auth::user()->hasPermission('admin.permissions.manage');

        $request->validate([
            'nama_admin' => 'required',
            'username'   => 'required|unique:admin,username',
            'password'   => 'required|min:6',
            'role'       => ['required', Rule::in(['super_admin', 'admin'])],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [Rule::in(AdminPermission::keys())],
        ]);

        $adminBaru = Admin::create([
            'nama_admin' => $request->nama_admin,
            'username'   => $request->username,
            'password'   => Hash::make($request->password),
            'role'       => $canManagePermissions ? $request->role : 'admin',
        ]);

        if ($canManagePermissions) {
            $adminBaru->syncPermissions($request->input('permissions', []));
        }

        // ✅ Catat log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::id(), // guard web (admin)
            'aktivitas'  => 'Menambahkan admin baru: ' . $adminBaru->nama_admin . ' (ID: ' . $adminBaru->id . ')',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(Admin $admin)
    {
        $admin->load('permissions');

        return view('admin.edit', [
            'admin' => $admin,
            'permissionGroups' => AdminPermission::groups(),
            'selectedPermissions' => $admin->permissionKeys(),
        ]);
    }

    public function update(Request $request, Admin $admin)
    {
        $canManagePermissions = Auth::user()->hasPermission('admin.permissions.manage');

        $request->validate([
            'nama_admin' => 'required',
            'username'   => 'required|unique:admin,username,' . $admin->id,
            'password'   => 'nullable|min:6',
            'role'       => ['required', Rule::in(['super_admin', 'admin'])],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [Rule::in(AdminPermission::keys())],
        ]);

        // ✨ Simpan data lama untuk perbandingan
        $dataLama = $admin->only(['nama_admin', 'username', 'role']);

        $dataBaru = [
            'nama_admin' => $request->nama_admin,
            'username'   => $request->username,
        ];

        if ($canManagePermissions) {
            $dataBaru['role'] = $request->role;
        }

        if ($request->filled('password')) {
            $dataBaru['password'] = Hash::make($request->password);
        }

        $admin->update($dataBaru);

        if ($canManagePermissions) {
            $admin->syncPermissions($request->input('permissions', []));
        }

        // ✨ Bandingkan perubahan
        $perubahan = [];
        foreach ($dataBaru as $field => $value) {
            // skip password dari detail perbandingan
            if ($field === 'password') {
                if ($request->filled('password')) {
                    $perubahan[] = "password diubah";
                }
                continue;
            }
            if (isset($dataLama[$field]) && $dataLama[$field] != $value) {
                $perubahan[] = "$field: '{$dataLama[$field]}' → '{$value}'";
            }
        }

        // ✨ Susun pesan aktivitas
        $detailPerubahan = count($perubahan) > 0 
            ? implode(', ', $perubahan) 
            : 'tidak ada perubahan data';

        // ✅ Catat log aktivitas dengan detail perubahan
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::id(),
            'aktivitas'  => "Memperbarui data admin (ID: {$admin->id}): {$detailPerubahan}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin berhasil diperbarui.');
    }


    public function destroy(Admin $admin, Request $request)
    {
        $namaAdmin = $admin->nama_admin;
        $idAdmin   = $admin->id;
        $admin->delete();

        // ✅ Catat log aktivitas
        LogAktivitas::create([
            'aktor_type' => 'admin',
            'aktor_id'   => Auth::id(),
            'aktivitas'  => 'Menghapus admin: ' . $namaAdmin . ' (ID: ' . $idAdmin . ')',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus.');
    }
}
