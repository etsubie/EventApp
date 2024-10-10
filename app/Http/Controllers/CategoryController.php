<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create a new category
        $category = Category::create([
            'name' => $request->name,
        ]);

        // Return a response
        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category,
        ], 201);
    }
}
