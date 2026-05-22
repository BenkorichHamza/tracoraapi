<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CategoryResource::collection(
        Category::with(['createdBy', 'updatedBy', 'deletedBy'])->latest()->paginate(20)
    );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'name_ar' => $validated['name_ar'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // Upload image
        if ($request->hasFile('image')) {

            $category
                ->addMediaFromRequest('image')
                ->toMediaCollection('category');
        }

        return new CategoryResource(
            $category->load(['media', 'createdBy'])
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category->load(['createdBy', 'updatedBy', 'deletedBy']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $validated['updated_by'] = auth()->id();

        $category->update(collect($validated)->except('image')->toArray());
        // Upload image
        if ($request->hasFile('image')) {
            $category->clearMediaCollection('category');
            $category
                ->addMediaFromRequest('image')
                ->toMediaCollection('category');
        }

        return new CategoryResource($category->load(['createdBy', 'updatedBy', 'deletedBy']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $brand)
    {
        $brand->update([
            'deleted_by' => auth()->id()
        ]);

        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
