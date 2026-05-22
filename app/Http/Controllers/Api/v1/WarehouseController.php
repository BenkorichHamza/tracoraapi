<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $warehouses = Warehouse::query()
            ->with(['createdBy', 'updatedBy'])
            ->latest()
            ->paginate(20);

        return WarehouseResource::collection($warehouses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => ['required', 'string', 'max:255'],

            'name_ar' => ['nullable', 'string', 'max:255'],

            'location' => ['nullable', 'string'],

            'description' => ['nullable', 'string'],

            'type' => ['nullable', 'string'],
        ]);

        $validated['created_by'] = auth()->id();

        $warehouse = Warehouse::create($validated);

        return new WarehouseResource(
            $warehouse->load(['createdBy'])
        );
    }

    public function show(Warehouse $warehouse)
    {
        return new WarehouseResource(
            $warehouse->load(['createdBy', 'updatedBy'])
        );
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([

            'name' => ['sometimes', 'required', 'string', 'max:255'],

            'name_ar' => ['nullable', 'string', 'max:255'],

            'location' => ['nullable', 'string'],

            'description' => ['nullable', 'string'],

            'type' => ['nullable', 'string'],
        ]);

        $validated['updated_by'] = auth()->id();

        $warehouse->update($validated);

        return new WarehouseResource(
            $warehouse->load(['updatedBy'])
        );
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->update([
            'deleted_by' => auth()->id(),
        ]);

        $warehouse->delete();

        return response()->json([
            'success' => true,
            'message' => 'Warehouse deleted successfully',
        ]);
    }
}
