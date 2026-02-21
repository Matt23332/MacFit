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
            'user_id' => 'required|integer',
            'bundle_id' => 'required|integer',
        ]);

        // Create a new subscription
        $subscription = Subscription::create($validatedData);

        return response()->json($subscription, 201);
    }

    public function readAll()
    {
        $subscriptions = Subscription::all();
        return response()->json($subscriptions);
    }

    public function readSubscription($id)
    {
        $subscription = Subscription::find($id);
        if ($subscription) {
            return response()->json($subscription);
        } else {
            return response()->json(['message' => 'Subscription not found'], 404);
        }
    }

    public function updateSubscription(Request $request, $id)
    {
        $subscription = Subscription::find($id);
        if ($subscription) {
            // Validate the request data
            $validatedData = $request->validate([
                'user_id' => 'integer',
                'bundle_id' => 'integer',
            ]);

            // Update the subscription with validated data
            $subscription->update($validatedData);

            return response()->json($subscription);
        } else {
            return response()->json(['message' => 'Subscription not found'], 404);
        }
    }

    public function deleteSubscription($id)
    {
        $subscription = Subscription::find($id);
        if ($subscription) {
            $subscription->delete();
            return response()->json(['message' => 'Subscription deleted']);
        } else {
            return response()->json(['message' => 'Subscription not found'], 404);
        }
    }
}
