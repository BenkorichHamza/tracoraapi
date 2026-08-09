<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MoneyTransactionResource;
use App\Models\MoneyTransaction;
use Carbon\Carbon;
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
            'id' => ['uuid'],


            'userId' => ['nullable', 'uuid', 'exists:contacts,id'],

            'employeeId' => ['nullable', 'uuid', 'exists:contacts,id'],

            'status' => ['nullable', 'integer'],

            'credit' => ['nullable', 'numeric'],

            'amount' => ['nullable', 'numeric'],

            'type' => ['nullable', 'string'],

            'description' => ['nullable', 'string'],

            'datetime' => ['nullable', 'numeric'],

            'deletedAt' => ['nullable', 'numeric'],

            'missionId' => ['nullable', 'string'],
        ]);

$validated['datetime'] = Carbon::createFromTimestampMs($validated['datetime'] ?? now()->getTimestampMs())->setTimezone(config('app.timezone'));

        $transaction = MoneyTransaction::create([
            ...$validated,
            'createdBy' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $transaction->load(['user', 'employee'])
        ]);
    }

    public function sync(Request $request)
{
    // Validate and cast timestamp safely
$lastSyncMs = $request->input('last_sync_date');

$lastSyncDate = is_numeric($lastSyncMs) && (int)$lastSyncMs > 0
    ? Carbon::createFromTimestampMs((int) $lastSyncMs)
    : null;

$moneyTransactions = MoneyTransaction::query()
    ->with([
        'user',
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

return MoneyTransactionResource::collection($moneyTransactions);
}

    public function show(MoneyTransaction $mtransaction)
    {
        return $mtransaction->load([
            'user',
            'employee'
        ]);
    }

    public function update(Request $request, MoneyTransaction $mtransaction)
    {
        $validated = $request->validate([
            'id' => ['uuid', 'exists:money_transactions,id'],

            'userId' => ['nullable', 'uuid', 'exists:contacts,id'],

            'employeeId' => ['nullable', 'uuid', 'exists:contacts,id'],

            'status' => ['nullable', 'integer'],

            'credit' => ['nullable', 'numeric'],

            'amount' => ['nullable', 'numeric'],

            'type' => ['nullable', 'string'],

            'description' => ['nullable', 'string'],

            'datetime' => ['nullable', 'numeric'],

            'deletedAt' => ['nullable', 'numeric'],


            'missionId' => ['nullable', 'string'],
        ]);
$validated['datetime'] = Carbon::createFromTimestampMs($validated['datetime'] ?? now()->getTimestampMs())->setTimezone(config('app.timezone'));
        $mtransaction->update([
            ...$validated,
            'updatedBy' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $mtransaction->load(['user', 'employee'])
        ]);
    }

    public function destroy(MoneyTransaction $moneyTransaction)
    {
        $moneyTransaction->update([
            'deletedBy' => auth()->id()
        ]);

        $moneyTransaction->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
