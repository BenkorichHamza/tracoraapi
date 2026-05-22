<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Stransaction;
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

    public function store(Request $request)
    {
        $validated = $request->validate([

            'status' => ['nullable', 'integer'],

            'employee_id' => ['nullable', 'uuid', 'exists:users,id'],

            'user_id' => ['nullable', 'uuid', 'exists:contacts,id'],

            'from_warehouse_id' => ['nullable', 'uuid', 'exists:warehouses,id'],

            'to_warehouse_id' => ['nullable', 'uuid', 'exists:warehouses,id'],

            'type' => ['nullable', 'string'],

            'description' => ['nullable', 'string'],

            'topay' => ['nullable', 'numeric'],

            'total' => ['nullable', 'numeric'],

            'credit' => ['nullable', 'numeric'],

            'payment' => ['nullable', 'numeric'],

            'tax' => ['nullable', 'numeric'],

            'datetime' => ['nullable', 'date'],

            'products' => ['required', 'array'],

            'products.*.product_id' => ['required', 'uuid', 'exists:products,id'],

            'products.*.qte' => ['required', 'numeric'],

            'products.*.price' => ['required', 'numeric'],

            'products.*.tax' => ['nullable', 'numeric'],

            'products.*.direction' => ['nullable', 'integer'],
        ]);

        $transaction = Stransaction::create([
            ...collect($validated)->except('products')->toArray(),
            'created_by' => auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Attach Products Pivot
        |--------------------------------------------------------------------------
        */

        foreach ($validated['products'] as $product) {

            $transaction->products()->attach($product['product_id'], [
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

        'status' => ['nullable', 'integer'],

        'employee_id' => ['nullable', 'uuid', 'exists:users,id'],

        'user_id' => ['nullable', 'uuid', 'exists:contacts,id'],

        'from_warehouse_id' => ['nullable', 'uuid', 'exists:warehouses,id'],

        'to_warehouse_id' => ['nullable', 'uuid', 'exists:warehouses,id'],

        'type' => ['nullable', 'string'],

        'description' => ['nullable', 'string'],

        'topay' => ['nullable', 'numeric'],

        'total' => ['nullable', 'numeric'],

        'credit' => ['nullable', 'numeric'],

        'payment' => ['nullable', 'numeric'],

        'tax' => ['nullable', 'numeric'],

        'datetime' => ['nullable', 'date'],

        'products' => ['nullable', 'array'],

        'products.*.product_id' => ['required_with:products', 'uuid', 'exists:products,id'],

        'products.*.qte' => ['required_with:products', 'numeric'],

        'products.*.price' => ['required_with:products', 'numeric'],

        'products.*.tax' => ['nullable', 'numeric'],

        'products.*.direction' => ['nullable', 'integer'],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Update main transaction
    |--------------------------------------------------------------------------
    */

    $stransaction->update([
        ...collect($validated)->except('products')->toArray(),
        'updated_by' => auth()->id(),
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

            $syncData[$product['product_id']] = [
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
            'deleted_by' => auth()->id()
        ]);

        $stransaction->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
