<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    public function index(Category $category)
    {
        $subcategories = $category->subcategories()->paginate(10);
        return inertia('Admin/Subcategories/Index', compact('category', 'subcategories'));
    }

    public function store(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subcategories,name',
        ]);

        $category->subcategories()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Subcategory created successfully.');
    }

    public function destroy(Category $category, Subcategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Subcategory deleted successfully.');
    }
}
