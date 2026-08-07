<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
     use HasUuids;

    public $timestamps = false;

    protected $fillable = [

        'table_name',
        'row_id',

        'operation',

        'data',

        'user_id',
        'device_id',

        'created_at',
    ];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
    ];
}
