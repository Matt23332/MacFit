<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function createSubscription(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'bundle_id' => 'required|integer|exists:bundles,id',
        ]);

        $userId = auth()->user()->id;

        // Create a new subscription
        $subscription = new Subscription();
        $subscription->user_id = $userId;
        $subscription->bundle_id = $validatedData['bundle_id'];
        $subscription->save();

        try {
            $subscription->save();
            return response()->json([
                'message' => 'Subscription saved successfully'
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error'=>'Failed to save subscription',
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    public function readAll() {
        try {
            // $subscriptions = Subscription::all();
            $subscriptions = Subscription::join('users', 'subscriptions.user_id', '=', 'users.id')
                ->join('bundles', 'subscriptions.bundle_id', '=', 'bundles.id')
                ->select('subscriptions.*', 'users.name as user_name', 'bundles.name as bundle_name', 'bundles.value as bundle_value')
                ->get();
            return response()->json($subscriptions);
        } catch (\Exception $exception) {
            return response()->json([
                'error'=>'Failed to retrieve subscriptions',
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    public function readSubscription($id) {
        try {
            $subscription = Subscription::find($id);
            if ($subscription) {
            return response()->json($subscription);
            }  else {
                return response()->json(['message' => 'Subscription not found'], 404);
            }
            } catch (\Exception $exception) {
                return response()->json([
                'error'=>'Failed to retrieve subscription',
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    public function updateSubscription(Request $request, $id) {
        $validated = $request->validate([
            'user_id' => 'integer|required|exists:users,id',
            'bundle_id' => 'integer|required|exists:bundles,id',
        ]);

        $subscription = Subscription::findOrFail($id);
        $subscription->user_id = $validated['user_id'];
        $subscription->bundle_id = $validated['bundle_id'];
        $subscription->save();
        return response()->json($subscription);

        try {
            $subscription->save();
            return response()->json([
                'message' => 'Subscription updated successfully'
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to update subscription',
                'message' => $exception->getMessage(),
            ], 200);
        }
    }

    public function deleteSubscription($id) {
        try {
            $subscription = Subscription::findOrFail($id);
            $subscription->delete();
            return response()->json([
                'message' => 'Subscription deleted successfully'
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to delete subscription',
                'message' => $exception->getMessage(),
            ], 200);
        }
    }

    public function getUserCharges() {
        $user = auth()->user();
        $userId = $user->id;

        $charges = Subscription::where('user_id', $userId)
            ->join('users', 'subscriptions.user_id', '=', 'users.id')
            ->join('bundles', 'subscriptions.bundle_id', '=', 'bundles.id')
            ->sum('bundles.value');
        return response()->json([
             'user' => $user->name,
             'total_charges' => $charges,
        ], 200);
    }
}
