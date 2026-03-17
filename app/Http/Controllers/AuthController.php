<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Role;
use Illuminate\Support\Facades\URL;
use App\Notifications\VerifyEmailNotification;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string|in:Male,Female,Other,Prefer not to say',
            'dob' => 'nullable|date',
            'gym_location' => 'nullable|string|max:100',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        if ($request->hasFile('role_id')) {
            $role_id = $request->role_id;
        } else {
            $role = Role::where('name', 'User')->first();
            $role_id = $role->id;   
        }

        $role = Role::where('name', 'User')->first();

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->gender = $validated['gender'] ?? null;
        $user->dob = $validated['dob'] ?? null;
        $user->gym_location = $validated['gym_location'] ?? null;

        $user->role_id = $role ? $role->id : null;
        $user->password = bcrypt($validated['password']);
        $user->is_active = true; // Set to true for testing, change to false for email verification

        if ($request->hasFile('user_image')) {
            $filename = $request->file('user_image')->store('users', 'public');
        } else {
            $filename = null;   
        }
        $user->user_image = $filename;

        try {
            $user->save();
        //     $signedUrl = URL::temporarySignedRoute(
        //     'verification.verify',
        //     now()->addMinutes(60),
        //     ['id' => $user->id, 'hash' => sha1($user->email)]
        // );

        // $user->notify(new VerifyEmailNotification($signedUrl));
        // return response()->json(['message' => 'Verification email sent successfully'], 200);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['user' => $user, 'message' => 'User registered successfully', 'access_token' => $token, 'token_type' => 'Bearer'], 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'User registration failed', 'error' => $exception->getMessage()], 500);
        }
    }

    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        // if (!$user || !Hash::check($validated['password'], $user->password)) {
        //     throw ValidationException::withMessages([ 'error' => 'Invalid credentials.'], 401);
        // }

        if (!$user->is_active) {
            return response()->json(['message' => 'Email not verified. Please verify your email before logging in.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token, 
            'token_type' => 'Bearer', 
            'message' => 'Login successful', 
            'user' => $user, 
            'abilities' => $user->abilities()], 200);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout successful'], 200);
    }

    // public function userInfo() {
    //     return response()->json(auth()->user());
    // }
}
