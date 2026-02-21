<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function createCategory(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name',
            'description' => 'nullable|string'
        ]);

        $category = new Category();
        $category->name = $validated['name'];
        $category->description = $validated['description'] ?? '';

        try {
            $category->save();
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create category', 'message' => $e->getMessage()], 500);
        }
    }

    public function readAll() {
        try {
            $categories = Category::all();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve categories', 'message' => $e->getMessage()], 500);
        }
    }

    public function readCategory($id) {
        try {
            $category = Category::findOrFail($id);
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve category', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateCategory(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required|string'. $id,
            'description' => 'nullable|string'
        ]);

        try {
            $existingCategory = Category::findOrFail($id);
            $existingCategory->name = $validated['name'];
            $existingCategory->description = $validated['description'] ?? '';
            $existingCategory->save();
            return response()->json($existingCategory);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update category', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteCategory($id) {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['category' => $category, 'message' => 'Category deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete category', 'message' => $e->getMessage()], 500);
        }
    }
}
