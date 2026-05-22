<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BrandResource::collection(
        Brand::with(['createdBy', 'updatedBy', 'deletedBy'])->latest()->paginate(20)
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

        $validated['created_by'] = auth()->id();

        $brand = Brand::create(collect($validated)->except('image')->toArray());

        // Upload image
        if ($request->hasFile('image')) {
            $brand
                ->addMediaFromRequest('image')
                ->toMediaCollection('brand');
        }

        return new BrandResource($brand->load('createdBy', 'updatedBy', 'deletedBy', 'media'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return new BrandResource($brand->load(['createdBy', 'updatedBy', 'deletedBy', 'media']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $validated['updated_by'] = auth()->id();

        $brand->update(collect($validated)->except('image')->toArray());

        // Upload image
        if ($request->hasFile('image')) {
            $brand->clearMediaCollection('brand');
            $brand
                ->addMediaFromRequest('image')
                ->toMediaCollection('brand');
        }

        return new BrandResource($brand->load(['createdBy', 'updatedBy', 'deletedBy', 'media']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->update([
            'deleted_by' => auth()->id()
        ]);

        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully'
        ]);
    }
}
