<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserOtp;
use App\Models\User;

class UserOtpController extends Controller
{
    public function verifyOtp(Request $request) {
        $request->validate([
            'email' => 'required|string',
            'otp' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $otpEntry = UserOtp::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->first();

        if (!$otpEntry || $otpEntry->isExpired()) {
            return response()->json([
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

        $otpEntry->delete();
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'OTP Verified successfully',
            'tokken' => $token,
            'user' => $user
        ], 201);
    }
}
