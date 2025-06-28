<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // GET all users
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    // GET one user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    // POST create user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'birthday' => 'nullable|date',
            'phone' => 'nullable|string',
            'sex' => 'nullable|in:male,female,other',
            'description' => 'nullable|string',
            'photo' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);
        return response()->json($user, 201);
    }
}
