<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Carbon\Carbon;
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
'id'=>['uuid','unique:warehouses,id'],

            'name' => ['required', 'string', 'max:255'],

            'nameAr' => ['nullable', 'string', 'max:255'],

            'location' => ['nullable', 'string'],

            'description' => ['nullable', 'string'],

            'type' => ['nullable', 'string'],

            'deletedAt' => ['nullable', 'numeric'],

        ]);

        $validated['createdBy'] = auth()->id();

        $warehouse = Warehouse::create($validated);

        return new WarehouseResource(
            $warehouse
        );
    }

    public function sync(Request $request)
{
    // Validate and cast timestamp safely
$lastSyncMs = $request->input('last_sync_date');
$lastSyncDate = is_numeric($lastSyncMs) && $lastSyncMs > 0
    ? Carbon::createFromTimestampMs((int) $lastSyncMs)
    : null;

$warehouses = Warehouse::query()
    ->with([
        'createdByUser',
        'updatedByUser',
        'deletedByUser',
    ])
    ->when($lastSyncDate, function ($query, $lastSyncDate) {
        $query->where('updated_at', '>=', $lastSyncDate);
            //   ->where(function ($subQuery) {
            //       // Handles both NULL updatedBy and non-current user updates
            //       $subQuery->whereNull('updatedBy')
            //                ->orWhere('updatedBy', '<>', auth()->id());
            //   });
    }, function ($query) {
        // Fallback baseline when no lastSyncDate is provided
        $query->where('updated_at', '>', Carbon::parse('2026-01-01'));
    })
    ->latest('updated_at')
    ->get();

return WarehouseResource::collection($warehouses);
}

    public function show(Warehouse $warehouse)
    {
        return new WarehouseResource(
            $warehouse
        );
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
'id'=>['uuid'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],

            'nameAr' => ['nullable', 'string', 'max:255'],

            'location' => ['nullable', 'string'],

            'description' => ['nullable', 'string'],

            'type' => ['nullable', 'string'],

            'deletedAt' => ['nullable', 'numeric'],

        ]);

        $validated['updatedBy'] = auth()->id();

        $warehouse->update($validated);

        return new WarehouseResource(
            $warehouse
        );
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->update([
            'deletedBy' => auth()->id(),
        ]);

        $warehouse->delete();

        return response()->json([
            'success' => true,
            'message' => 'Warehouse deleted successfully',
        ]);
    }
}
