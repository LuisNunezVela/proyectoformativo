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
            'birthdate' => 'nullable|date',
            'phone' => 'nullable|string',
            'sex' => 'nullable|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);
        return response()->json($user, 201);
    }

    // PUT update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'lastname' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'birthday' => 'nullable|date',
            'phone' => 'nullable|string',
            'sex' => 'nullable|in:male,female,other',
            'description' => 'nullable|string',
            'photo' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);
        return response()->json($user, 200);
    }

    // DELETE remove user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (!auth()->attempt($credentials)) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $user = auth()->user();
    return response()->json($user);
}

}
