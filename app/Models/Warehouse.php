<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
     use HasUuids;
    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */



    public function outgoingTransactions()
    {
        return $this->hasMany(
            Stransaction::class,
            'from_warehouse'
        );
    }

    public function incomingTransactions()
    {
        return $this->hasMany(
            Stransaction::class,
            'to_warehouse'
        );
    }

     public function allTransactions()
{
    return $this->hasMany(Stransaction::class, 'from_warehouse') // Base relation on from_warehouse
                ->orWhere('to_warehouse', $this->id);           // Or match where it's to_warehouse
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

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'warehouse_id');
    }
}
