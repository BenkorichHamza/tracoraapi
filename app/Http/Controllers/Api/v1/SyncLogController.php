<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;

use App\Models\Stransaction;
use App\Models\SyncLog;
use DB;
use Illuminate\Http\Request;

class SyncLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([

        'table_name' => ['required', 'string'],

        'row_id' => ['required', 'uuid'],

        'operation' => ['required', 'in:INSERT,UPDATE,DELETE'],

        'data' => ['nullable', 'array'],

        'user_id' => ['nullable', 'uuid'],

        'device_id' => ['nullable', 'uuid'],
    ]);

    $log = SyncLog::create([
        ...$validated,
        'created_at' => now(),
    ]);

    return response()->json([
        'success' => true,
        'data' => $log
    ]);
}

public function push(Request $request)
{
    $validated = $request->validate([
        'operations' => ['required', 'array'],
        'operations.*.tableName' => ['required', 'string'],
        'operations.*.operation' => ['required', 'in:INSERT,UPDATE,DELETE'],
        'operations.*.rowId' => ['required'],
        'operations.*.data' => ['nullable', 'array'],
        'operations.*.id' => ['required'],
    ]);

    $success = [];
    $failed = [];
    $messages = [];

    DB::transaction(function () use ($validated, &$success, &$failed, &$messages) {

        foreach ($validated['operations'] as $op) {

            try {
                $productIds = collect($op['data']['products'] ?? [])
    ->pluck('product_id')
    ->unique()
    ->values();

    $existingIds = \App\Models\Product::whereIn('id', $productIds)
    ->pluck('id')
    ->toArray();

$missing = $productIds->diff($existingIds);

    if ($missing->isNotEmpty()) {
    throw new \Exception(
        "Invalid product IDs: " . $missing->implode(', ')
    );
}



                /*
                |--------------------------------------------------------------------------
                | 1. Resolve model dynamically
                |--------------------------------------------------------------------------
                */

                $model = $this->resolveModel($op['tableName']);
                if (!$model) {
                    $failed[] = $op['id'];
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | 2. Apply operation
                |--------------------------------------------------------------------------
                */

                $record = null;
                if ($op['operation'] === 'INSERT') {
                    $record = $model::find($op['rowId']);
                    if ($record) {
                        throw new \Exception('Record already exist');
                    }
                    if ($op['tableName'] === 'stransactions') {

                    DB::transaction(function () use ($op) {

                        /*
                        |--------------------------------------------------------------------------
                        | Create transaction
                        |--------------------------------------------------------------------------
                        */

                        $transaction = Stransaction::create(
                            collect($op['data'])
                                ->except('products')
                                ->toArray()
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | Attach products pivot
                        |--------------------------------------------------------------------------
                        */

                        foreach ($op['data']['products'] ?? [] as $product) {

                            $transaction->products()->attach(
                                $product['product_id'],
                                [
                                    'qte' => $product['qte'],
                                    'price' => $product['price'],
                                    'tax' => $product['tax'] ?? 0,
                                    'direction' => $product['direction'] ?? 1,
                                ]
                            );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Sync log
                        |--------------------------------------------------------------------------
                        */


                    });
                }

                else{
                    $record = $model::create($op['data']);
                }

                } elseif ($op['operation'] === 'UPDATE') {

                    $record = $model::find($op['rowId']);

                    if (!$record) {
                        throw new \Exception('Record not found');
                    }

                    $record->update($op['data']);

                } elseif ($op['operation'] === 'DELETE') {

                    $record = $model::find($op['rowId']);

                    if (!$record) {
                        throw new \Exception('Record not found');
                    }

                    $record->delete();
                }

                /*
                |--------------------------------------------------------------------------
                | 3. Write sync log
                |--------------------------------------------------------------------------
                */

                SyncLog::create([
                    'table_name' => $op['tableName'],
                    'row_id' => $op['rowId'],
                    'operation' => $op['operation'],
                    'data' => $op['data'],
                    'user_id' => auth()->id(),
                    'created_at' => now(),
                ]);

                $success[] = $op['id'];

            } catch (\Throwable $e) {
                $messages[] = $e->getMessage();
                $failed[] = $op['id'];
            }
        }
    });

    return response()->json([
        'success' => true,
        'synced' => $success,
        'failed' => $failed,
        'messages' => $messages
    ]);
}

private function resolveModel($tableName)
{
    return match ($tableName) {

        'products' => \App\Models\Product::class,

        'brands' => \App\Models\Brand::class,

        'categories' => \App\Models\Category::class,

        'warehouses' => \App\Models\Warehouse::class,

        'money_transactions' => \App\Models\MoneyTransaction::class,

        'stransactions' => \App\Models\Stransaction::class,

        'contacts' => \App\Models\Contact::class,
        'user' => \App\Models\Contact::class,

        default => null
    };
}

    /**
     * Display the specified resource.
     */
    public function show(SyncLog $syncLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SyncLog $syncLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SyncLog $syncLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SyncLog $syncLog)
    {
        //
    }
}
