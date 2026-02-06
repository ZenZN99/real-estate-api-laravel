<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:40',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $isAdmin = $request->email === env('ADMIN_EMAIL') &&
            $request->password === env('ADMIN_PASSWORD');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $isAdmin ? 'Admin' : 'Customer',
            'avatar' => 'https://res.cloudinary.com/dgagbheuj/image/upload/v1763194734/avatar-default-image_yc4xy4.jpg',
            'cover' => 'https://res.cloudinary.com/dgagbheuj/image/upload/v1763194811/cover-default-image_uunwq6.jpg',
            'bio' => 'Welcome to Real-Estate',
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'success' => 'Account created successfully',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'success' => 'Logged in successfully',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }


public function updateProfile(Request $request)
{
    $user = $request->user();
    $data = [];

    if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
        $avatarPath = $request->file('avatar')
            ->store('users/avatars', 'public');

        $data['avatar'] = '/storage/' . $avatarPath;
    }

    if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
        $coverPath = $request->file('cover')
            ->store('users/covers', 'public');

        $data['cover'] = '/storage/' . $coverPath;
    }

    if ($request->filled('bio')) {
        $data['bio'] = $request->bio;
    }

    if (empty($data)) {
        return response()->json([
            'error' => 'No data provided for update'
        ], 422);
    }

    $user->update($data);

    return response()->json([
        'success' => 'Profile updated successfully',
        'user' => $user->fresh()
    ]);
}




    public function getAllUsers(Request $request)
    {
        $users = User::where('id', '!=', $request->user()->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json($users);
    }

    public function getUserById($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function deleteUserById(Request $request, $id)
    {
        $authUser = $request->user();
        if ($authUser->role !== 'Admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => 'User deleted successfully']);
    }

    public function updateUserRole(Request $request, $id)
    {
        $authUser = $request->user();

        if ($authUser->role !== 'Admin') {
            return response()->json(['error' => 'Forbidden. Admins only'], 403);
        }

        $user = User::findOrFail($id);

        $newRole = $request->role;

        if (!in_array($newRole, ['Admin', 'Agent', 'Customer'])) {
            return response()->json(['error' => 'Invalid role'], 422);
        }

        $user->role = $newRole;
        $user->save();

        return response()->json([
            'success' => 'User role updated successfully',
            'user' => $user
        ]);
    }
}
