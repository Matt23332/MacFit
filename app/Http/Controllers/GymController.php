<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gym;

class GymController extends Controller
{
    public function createGym(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|unique:gyms,name',
            'description' => 'nullable|string',
            'longitude' => 'required|string',
            'latitude' => 'required|string'
        ]);

        $gym = new Gym();
        $gym->name = $validated['name'];
        $gym->description = $validated['description'] ?? '';
        $gym->longitude = $validated['longitude'];
        $gym->latitude = $validated['latitude'];
        $gym->save();

        try {
            $gym->save();
            return response()->json($gym);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create gym', 'message' => $e->getMessage()], 500);
        }
    }

    public function readAll() {
        try {
            $gyms = Gym::all();
            return response()->json($gyms);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve gyms', 'message' => $e->getMessage()], 500);
        }
    }

    public function readGym($id) {
        try {
            $gym = Gym::findOrFail($id);
            return response()->json($gym);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve gym', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateGym(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required|string|unique:gyms,name,' . $id,
            'description' => 'nullable|string',
            'longitude' => 'nullable|string',
            'latitude' => 'nullable|string'
        ]);

        try {
            $gym = Gym::findOrFail($id);
            $gym->name = $validated['name'];
            $gym->description = $validated['description'] ?? '';
            $gym->longitude = $validated['longitude'] ?? $gym->longitude;
            $gym->latitude = $validated['latitude'] ?? $gym->latitude;
            $gym->save();
            return response()->json($gym);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update gym', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteGym($id) {
        try {
            $gym = Gym::findOrFail($id);
            $gym->delete();
            return response()->json(['gym' => $gym, 'message' => 'Gym deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete gym', 'message' => $e->getMessage()], 500);
        }
    }
}
