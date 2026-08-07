<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stransaction extends Model
{
     use HasUuids;
    use SoftDeletes;

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function employee()
    {
        return $this->belongsTo(User::class, 'employeeId');
    }

    public function user()
    {
        return $this->belongsTo(Contact::class, 'userId');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_transaction',
        'transaction_id',
        'product_id'
        )
            ->withPivot([
                'direction',
                'qte',
                'price',
                'tax'
            ]);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updatedBy');
    }

    public function deletedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deletedBy');
    }
}
