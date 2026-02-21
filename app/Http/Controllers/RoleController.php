<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function createRole(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'description' => 'nullable|string'
        ]);

        $role = new Role();
        $role->name = $validated['name'];
        $role->description = $validated['description'] ?? '';

        try {
            $role->save();
            return response()->json($role);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create role', 'message' => $e->getMessage()], 500);
        }
    }

    public function readAll() {
        try {
            $roles = Role::all();
            return response()->json($roles);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve roles', 'message' => $e->getMessage()], 500);
        }
    }

    public function readRole($id) {
        try {
            $role = Role::findOrFail($id);
            return response()->json($role);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve role', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateRole(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'description' => 'nullable|string'
        ]);

        try {
            $existingRole = Role::findOrFail($id);
            $existingRole->name = $validated['name'];
            $existingRole->description = $validated['description'] ?? '';
            $existingRole->save();
            return response()->json($existingRole);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update role', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteRole($id) {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return response()->json(['message' => 'Role deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete role', 'message' => $e->getMessage()], 500);
        }
    }
}
