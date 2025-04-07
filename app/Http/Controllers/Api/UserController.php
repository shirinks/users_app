<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    public function index()
    {
        $users = User::with('roles')->get(); 
        return response()->json($users); 
    }


    public function store(Request $request)
    {
       
        if (!auth()->user()->hasRole('System Admin')) {
            return response()->json(['message' => 'Unauthorized'], 403); 
        }
        
        $validator = \Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:System Admin,HR,User',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create the user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'position' => $request->position,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->assignRole($request->role);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $appName = config('app.name');
            return response()->json([
                'token' => $user->createToken($appName)->plainTextToken,
                'user' => $user
            ]);
        }
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function edit(Request $request,$user_id)
    {
        $user = User::findOrFail($user_id);

        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'nullable|in:System Admin,HR,User',
        ]);

        if ($request->filled('first_name')) {
            $user->first_name = $request->first_name;
        }
        if ($request->filled('last_name')) {
            $user->last_name = $request->last_name;
        }
        if ($request->filled('position')) {
            $user->position = $request->position;
        }
        if ($request->filled('email')) {
            $user->email = $request->email;
        }
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();
        if ($request->filled('role')) {
            $user->assignRole($request->role);
        }
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    // Delete user 
    public function delete(Request $request)
    {
        $user = Auth::user();

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
