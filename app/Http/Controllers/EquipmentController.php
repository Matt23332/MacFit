<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipment;

class EquipmentController extends Controller
{
    public function createEquipment(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|unique:equipment,name',
            'status' => 'required|string',
            'usage' => 'required|string',
            'model_no' => 'required|integer',
            'value' => 'required|integer'
        ]);

        $equipment = new Equipment();
        $equipment->name = $validated['name'];
        $equipment->status = $validated['status'];
        $equipment->usage = $validated['usage'];
        $equipment->model_no = $validated['model_no'];
        $equipment->value = $validated['value'];
        $equipment->save();

        try {
            $equipment->save();
            return response()->json($equipment);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create equipment', 'message' => $e->getMessage()], 500);
        }
    }

    public function readAll() {
        try {
            $equipment = Equipment::all();
            return response()->json($equipment);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve equipment', 'message' => $e->getMessage()], 500);
        }
    }

    public function readEquipment($id) {
        try {
            $equipment = Equipment::findOrFail($id);
            return response()->json($equipment);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve equipment', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateEquipment(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required|string|unique:equipment,name,' . $id,
            'status' => 'nullable|string',
            'usage' => 'nullable|string',
            'model_no' => 'nullable|integer',
            'value' => 'nullable|integer'
        ]);

        try {
            $equipment = Equipment::findOrFail($id);
            $equipment->name = $validated['name'];
            if (isset($validated['status'])) {
                $equipment->status = $validated['status'];
            }
            if (isset($validated['usage'])) {
                $equipment->usage = $validated['usage'];
            }
            if (isset($validated['model_no'])) {
                $equipment->model_no = $validated['model_no'];
            }
            if (isset($validated['value'])) {
                $equipment->value = $validated['value'];
            }
            $equipment->save();
            return response()->json($equipment);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update equipment', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteEquipment($id) {
        try {
            $equipment = Equipment::findOrFail($id);
            $equipment->delete();
            return response()->json(['message' => 'Equipment deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete equipment', 'message' => $e->getMessage()], 500);
        }
    }
}
