<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-settings');
        
        $users = User::orderBy('name')->paginate(15);
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        Gate::authorize('manage-settings');
        
        return view('users.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-settings');
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,secretario,locutor',
        ]);
        
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
        
        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function edit(User $user)
    {
        Gate::authorize('manage-settings');
        
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('manage-settings');
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,secretario,locutor',
        ];
        
        if ($request->filled('password')) {
            $rules['password'] = 'required|min:8|confirmed';
        }
        
        $data = $request->validate($rules);
        
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];
        
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($data['password']);
        }
        
        $user->update($updateData);
        
        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        Gate::authorize('manage-settings');
        
        // Evitar que o próprio admin logado se exclua acidentalmente
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Você não pode excluir a si mesmo!');
        }
        
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso!');
    }
}
