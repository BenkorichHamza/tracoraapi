<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StransactionResource;
use App\Models\Stransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StransactionController extends Controller
{
    public function index()
    {
        return Stransaction::with([
            'employee',
            'user',
            'fromWarehouse',
            'toWarehouse',
            'products'
        ])->latest()->paginate(20);
    }

    public function sync(Request $request)
{
    // Validate and cast timestamp safely
$lastSyncMs = $request->input('last_sync_date');
$lastSyncDate = is_numeric($lastSyncMs) && $lastSyncMs > 0
    ? Carbon::createFromTimestampMs((int) $lastSyncMs)
    : null;

$stransactions = Stransaction::query()
    ->with([
        'employee',
        'user',
        'fromWarehouse',
        'toWarehouse',
        'products',
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

    return StransactionResource::collection($stransactions);
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id'    =>['uuid'],

            'status' => ['nullable', 'integer'],

            'employeeId' => ['nullable', 'uuid', 'exists:contacts,id'],

            'userId' => ['nullable', 'uuid', 'exists:contacts,id'],

            'from_warehouse' => ['nullable', 'uuid', 'exists:warehouses,id'],

            'to_warehouse' => ['nullable', 'uuid', 'exists:warehouses,id'],

            'type' => ['nullable', 'string'],

            'description' => ['nullable', 'string'],

            'topay' => ['nullable', 'numeric'],

            'total' => ['nullable', 'numeric'],

            'credit' => ['nullable', 'numeric'],

            'payment' => ['nullable', 'numeric'],

            'tax' => ['nullable', 'numeric'],

            'datetime' => ['nullable', 'numeric'],

            'products' => ['required', 'array'],

            'products.*.productId' => ['required', 'uuid', 'exists:products,id'],

            'products.*.qte' => ['required', 'numeric'],

            'products.*.price' => ['required', 'numeric'],

            'products.*.tax' => ['nullable', 'numeric'],

            'products.*.direction' => ['nullable', 'integer'],

            'deletedAt' => ['nullable', 'numeric'],

        ]);

$validated['datetime'] = Carbon::createFromTimestampMs($validated['datetime'] ?? now()->getTimestampMs())->setTimezone(config('app.timezone'));
        $transaction = Stransaction::create([
            ...collect($validated)->except('products')->toArray(),
            'createdBy' => auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Attach Products Pivot
        |--------------------------------------------------------------------------
        */

        foreach ($validated['products'] as $product) {

            $transaction->products()->attach($product['productId'], [
                'qte' => $product['qte'],
                'price' => $product['price'],
                'tax' => $product['tax'] ?? 0,
                'direction' => $product['direction'] ?? 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction->load('products')
        ]);
    }

    public function show(Stransaction $stransaction)
    {
        return $stransaction->load([
            'employee',
            'user',
            'fromWarehouse',
            'toWarehouse',
            'products'
        ]);
    }

    public function update(Request $request, Stransaction $stransaction)
{
    $validated = $request->validate([
'id'=>['uuid'],
        'status' => ['nullable', 'integer'],

        'employeeId' => ['nullable', 'uuid', 'exists:users,id'],

        'userId' => ['nullable', 'uuid', 'exists:contacts,id'],

        'from_warehouse' => ['nullable', 'uuid', 'exists:warehouses,id'],

        'to_warehouse' => ['nullable', 'uuid', 'exists:warehouses,id'],

        'type' => ['nullable', 'string'],

        'description' => ['nullable', 'string'],

        'topay' => ['nullable', 'numeric'],

        'total' => ['nullable', 'numeric'],

        'credit' => ['nullable', 'numeric'],

        'payment' => ['nullable', 'numeric'],

        'tax' => ['nullable', 'numeric'],

        'datetime' => ['nullable', 'numeric'],

        'products' => ['nullable', 'array'],

        'products.*.productId' => ['required_with:products', 'uuid', 'exists:products,id'],

        'products.*.qte' => ['required_with:products', 'numeric'],

        'products.*.price' => ['required_with:products', 'numeric'],

        'products.*.tax' => ['nullable', 'numeric'],

        'products.*.direction' => ['nullable', 'integer'],

        'deletedAt' => ['nullable', 'numeric'],

    ]);

    /*
    |--------------------------------------------------------------------------
    | Update main transaction
    |--------------------------------------------------------------------------
    */

$validated['datetime'] = Carbon::createFromTimestampMs($validated['datetime'] ?? now()->getTimestampMs())->setTimezone(config('app.timezone'));

    $stransaction->update([
        ...collect($validated)->except('products')->toArray(),
        'updatedBy' => auth()->id(),
    ]);

    /*
    |--------------------------------------------------------------------------
    | Update products pivot
    |--------------------------------------------------------------------------
    */

    if (isset($validated['products'])) {

        // IMPORTANT: replace old items completely
        $syncData = [];

        foreach ($validated['products'] as $product) {

            $syncData[$product['productId']] = [
                'qte' => $product['qte'],
                'price' => $product['price'],
                'tax' => $product['tax'] ?? 0,
                'direction' => $product['direction'] ?? 1,
            ];
        }

        $stransaction->products()->sync($syncData);
    }

    return response()->json([
        'success' => true,
        'data' => $stransaction->load('products')
    ]);
}

    public function destroy(Stransaction $stransaction)
    {
        $stransaction->update([
            'deletedBy' => auth()->id()
        ]);

        $stransaction->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
