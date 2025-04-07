<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::with('roles')->paginate(10);
            return view('users.index', compact('users'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to retrieve users: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $roles = Role::all();
            return view('users.create', compact('roles'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load roles: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:8',
                'role' => 'required',
                'position'=>'required',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            ]);

            $photo = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('images', 'public');
                $photo = basename($photoPath);
            }

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'position'=>$request->position,
                'photo' => $photo,  
            ]);

            $user->assignRole($request->role);

            return redirect()->route('users.index')->with('success', 'User created!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        try {
            $roles = Role::all();
            return view('users.edit', compact('user', 'roles'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load user or roles: ' . $e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'position' => 'required|string|max:255',
                'role' => 'required',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Update user data
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'position' => $request->position,
            ]);

            if ($request->hasFile('photo')) {
                if ($user->photo) {
                    Storage::disk('public')->delete('images/' . $user->photo);
                }
        
                $photoPath = $request->file('photo')->store('images', 'public');
                $user->photo = basename($photoPath);
                $user->save();
            }

            // Sync roles
            $user->syncRoles([$request->role]);

            return redirect()->route('users.index')->with('success', 'User updated!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User deleted!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::find($request->user_id);

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['success' => true]);
    }

    public function checkEmail(Request $request)
    {
        $query = User::where('email', $request->email);

        if ($request->user_id) {
            $query->where('id', '!=', $request->user_id);
        }

        return response()->json(!$query->exists());
    }
}
