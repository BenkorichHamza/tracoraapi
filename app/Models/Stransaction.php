<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stransaction extends Model
{
     use HasUuids;
    use SoftDeletes;

    protected $fillable = [

        'status',

        'employee_id',
        'user_id',

        'from_warehouse_id',
        'to_warehouse_id',

        'type',
        'description',

        'topay',
        'total',
        'credit',
        'payment',
        'tax',

        'datetime',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_transaction',
        'product_id',
        'transaction_id')
            ->withPivot([
                'direction',
                'qte',
                'price',
                'tax'
            ]);
    }
}
