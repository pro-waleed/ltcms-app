<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderByDesc('id')->paginate(25);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'is_active' => ['nullable'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user = User::create([
            'username' => $data['username'],
            'full_name' => $data['full_name'],
            'email' => $data['email'] ?? null,
            'password' => Hash::make($data['password']),
            'is_active' => $request->boolean('is_active'),
        ]);

        $user->roles()->sync($data['roles'] ?? []);

        return redirect()->route('users.index')->with('status', 'تمت إضافة المستخدم بنجاح.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $user->id],
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'is_active' => ['nullable'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $payload = [
            'username' => $data['username'],
            'full_name' => $data['full_name'],
            'email' => $data['email'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);
        $user->roles()->sync($data['roles'] ?? []);

        return redirect()->route('users.index')->with('status', 'تم تحديث المستخدم بنجاح.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('status', 'لا يمكن حذف المستخدم الحالي أثناء تسجيل دخوله.');
        }

        $user->roles()->detach();
        $user->delete();

        return redirect()->route('users.index')->with('status', 'تم حذف المستخدم.');
    }
}
