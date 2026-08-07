<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasUuids, InteractsWithMedia;

    protected $guarded = [];

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product');
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
