<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\MoneyTransaction;
use Illuminate\Http\Request;

class MoneyTransactionController extends Controller
{
     public function index()
    {
        return MoneyTransaction::with([
            'user',
            'employee'
        ])->latest()->paginate(20);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'user_id' => ['nullable', 'uuid', 'exists:users,id'],

            'employee_id' => ['nullable', 'uuid', 'exists:users,id'],

            'status' => ['nullable', 'integer'],

            'credit' => ['nullable', 'numeric'],

            'amount' => ['nullable', 'numeric'],

            'type' => ['nullable', 'string'],

            'description' => ['nullable', 'string'],

            'datetime' => ['nullable', 'date'],

            'mission_id' => ['nullable', 'string'],
        ]);

        $transaction = MoneyTransaction::create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $transaction->load(['user', 'employee'])
        ]);
    }

    public function show(MoneyTransaction $moneyTransaction)
    {
        return $moneyTransaction->load([
            'user',
            'employee'
        ]);
    }

    public function update(Request $request, MoneyTransaction $moneyTransaction)
    {
        $validated = $request->validate([

            'user_id' => ['nullable', 'uuid', 'exists:users,id'],

            'employee_id' => ['nullable', 'uuid', 'exists:users,id'],

            'status' => ['nullable', 'integer'],

            'credit' => ['nullable', 'numeric'],

            'amount' => ['nullable', 'numeric'],

            'type' => ['nullable', 'string'],

            'description' => ['nullable', 'string'],

            'datetime' => ['nullable', 'date'],

            'mission_id' => ['nullable', 'string'],
        ]);

        $moneyTransaction->update([
            ...$validated,
            'updated_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $moneyTransaction->load(['user', 'employee'])
        ]);
    }

    public function destroy(MoneyTransaction $moneyTransaction)
    {
        $moneyTransaction->update([
            'deleted_by' => auth()->id()
        ]);

        $moneyTransaction->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
