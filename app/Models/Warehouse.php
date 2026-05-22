<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
     use HasUuids;
    protected $fillable = [

        'name',
        'name_ar',
        'location',
        'description',
        'type',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function outgoingTransactions()
    {
        return $this->hasMany(
            Stransaction::class,
            'from_warehouse_id'
        );
    }

    public function incomingTransactions()
    {
        return $this->hasMany(
            Stransaction::class,
            'to_warehouse_id'
        );
    }

     public function allTransactions()
    {
        return Stransaction::query()
            ->where('from_warehouse_id', $this->id)
            ->orWhere('to_warehouse_id', $this->id);
    }
}
