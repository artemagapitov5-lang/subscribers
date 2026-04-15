<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index() {
        $users = User::all(); // сразу все пользователи
        return view('users.index', compact('users'));
    }
    
    public function store(Request $request) {
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:6|confirmed',
            'role'=>'required|in:admin,manager,user'
        ]);
    
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'role'=>$request->role
        ]);
    
        return response()->json(['success'=>true]);
    }
    
    public function update(Request $request, User $user)
    {
        $rules = [];

        if ($request->has('name')) {
            $rules['name'] = 'string|max:255';
        }

        if ($request->has('email')) {
            $rules['email'] = 'email|unique:users,email,' . $user->id;
        }

        if ($request->has('role')) {
            $rules['role'] = 'in:admin,manager,user';
        }

        if ($request->has('password') && $request->password != '') {
            $rules['password'] = 'string|min:6';
        }

        $request->validate($rules);

        $data = $request->only('name','email','role');

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return response()->json(['success'=>true]);
    }
    
    public function destroy(User $user) {
        $user->delete();
        return response()->json(['success'=>true]);
    }
}