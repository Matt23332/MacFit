<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bundle;

class BundleController extends Controller
{
    public function createBundle(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|unique:bundles,name',
            'start_time' => 'required',
            'duration' => 'required',
            'description' => 'nullable|string',
            'value' => 'integer'
        ]);

        $bundle = new Bundle();
        $bundle->name = $validated['name'];
        $bundle->start_time = $validated['start_time'];
        $bundle->duration = $validated['duration'];
        $bundle->description = $validated['description'] ?? '';
        $bundle->value = $validated['value'] ?? 0;

        try {
            $bundle->save();
            return response()->json($bundle);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create bundle', 'message' => $e->getMessage()], 500);
        }
    }

    public function readAll() {
        try {
            // $bundles = Bundle::all();
            $bundles = Bundle::join('categories', 'bundles.category_id', '=', 'categories.category_id')
                ->select('bundles.*', 'categories.name as category_name')
                ->get();
            return response()->json($bundles);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve bundles', 'message' => $e->getMessage()], 500);
        }
    }

    public function readBundle($id) {
        try {
            $bundle = Bundle::join('categories', 'bundles.category_id', '=', 'categories.category_id')
                ->select('bundles.*', 'categories.name as category_name')
                ->where('bundles.id', $id)
                ->firstOrFail();
            return response()->json($bundle);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve bundle', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateBundle(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required|string|unique:bundles,name,' . $id,
            'description' => 'nullable|string',
            'start_time' => 'required',
            'duration' => 'required',
            'value' => 'integer'
        ]);

        try {
            $bundle = Bundle::findOrFail($id);
            $bundle->name = $validated['name'];
            $bundle->duration = $validated['duration'];
            $bundle->start_time = $validated['start_time'];
            $bundle->description = $validated['description'] ?? '';
            $bundle->value = $validated['value'] ?? 0;
            $bundle->category_id = $validated['category_id'] ?? $bundle->category_id;
            $bundle->save();
            return response()->json($bundle);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update bundle', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteBundle($id) {
        try {
            $bundle = Bundle::findOrFail($id);
            $bundle->delete();
            return response()->json(['bundle' => $bundle, 'message' => 'Bundle deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete bundle', 'message' => $e->getMessage()], 500);
        }
    }
}
