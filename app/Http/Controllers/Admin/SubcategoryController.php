<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subcategories')->where('category_id', $category->id)
            ],
        ]);

        $slug = $this->generateUniqueSlug($request->name, $category->id);

        $category->subcategories()->create([
            'name' => $validated['name'],
            'slug' => $slug,
        ]);

        return redirect()->back()->with('success', 'Subcategory added successfully.');
    }

    /**
     * Generate a unique slug for a subcategory within a category.
     */
    private function generateUniqueSlug($name, $categoryId): string
    {
        // Create the base slug
        $slug = Str::slug($name);

        // Check if the slug exists
        $existingSlugCount = Subcategory::where('slug', 'LIKE', "{$slug}%")->count();

        // Append a number if duplicates exist
        return $existingSlugCount > 0 ? "{$slug}-{$existingSlugCount}" : $slug;
    }

    public function destroy(Category $category, Subcategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Subcategory deleted successfully.');
    }

    public function checkSlug(Request $request)
    {
        $name = $request->input('name');

        // Create base slug
        $slug = Str::slug($name);

        // Check if the slug exists
        $existingSlugCount = Subcategory::where('slug', 'LIKE', "{$slug}%")->count();

        return response()->json([
            'exists' => $existingSlugCount > 0,
            'count' => $existingSlugCount,
        ]);
    }
}
