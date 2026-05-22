<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasUuids;


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];

    }


public function createdBrands(): HasMany
{
    return $this->hasMany(Brand::class, 'created_by');
}

public function updatedBrands(): HasMany
{
    return $this->hasMany(Brand::class, 'updated_by');
}

public function deletedBrands(): HasMany
{
    return $this->hasMany(Brand::class, 'deleted_by');
}

public function createdCategories(): HasMany
{
    return $this->hasMany(Category::class, 'created_by');
}

public function updatedCategories(): HasMany
{
    return $this->hasMany(Category::class, 'updated_by');
}

public function deletedCategories(): HasMany
{
    return $this->hasMany(Category::class, 'deleted_by');
}

public function contact(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(Contact::class, 'contact_id');
}

public function createdProducts(): HasMany
{
    return $this->hasMany(Product::class, 'created_by');
}

public function updatedProducts(): HasMany
{
    return $this->hasMany(Product::class, 'updated_by');
}

public function deletedProducts(): HasMany
{
    return $this->hasMany(Product::class, 'deleted_by');
}

public function createdContacts(): HasMany
{
    return $this->hasMany(Contact::class, 'created_by');
}

public function updatedContacts(): HasMany
{
    return $this->hasMany(Contact::class, 'updated_by');
}

public function deletedContacts(): HasMany
{
    return $this->hasMany(Contact::class, 'deleted_by');
}
}
