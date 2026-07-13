<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('profile', 'roles')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->status ?? 'active',
        ]);

        $user->profile()->create([
            'dni' => $request->dni,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'blood_type' => $request->blood_type,
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function show(User $user)
    {
        $user->load('profile', 'roles', 'appointmentsAsDoctor', 'appointmentsAsPatient');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user->load('profile');
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->only(['name', 'email', 'status']));

        $profileData = $request->only([
            'dni', 'phone', 'date_of_birth', 'gender', 'address', 'blood_type',
        ]);

        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $user->profile()->create($profileData);
        }

        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
