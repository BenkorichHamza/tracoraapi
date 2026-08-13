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
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password',"contact_id"])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasUuids, HasRoles;


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
    return $this->hasMany(Brand::class, 'createdBy');
}

public function updatedBrands(): HasMany
{
    return $this->hasMany(Brand::class, 'updatedBy');
}

public function deletedBrands(): HasMany
{
    return $this->hasMany(Brand::class, 'deletedBy');
}

public function createdCategories(): HasMany
{
    return $this->hasMany(Category::class, 'createdBy');
}

public function updatedCategories(): HasMany
{
    return $this->hasMany(Category::class, 'updatedBy');
}

public function deletedCategories(): HasMany
{
    return $this->hasMany(Category::class, 'deletedBy');
}

public function contact(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(Contact::class, 'contact_id');
}

public function createdProducts(): HasMany
{
    return $this->hasMany(Product::class, 'createdBy');
}

public function updatedProducts(): HasMany
{
    return $this->hasMany(Product::class, 'updatedBy');
}

public function deletedProducts(): HasMany
{
    return $this->hasMany(Product::class, 'deletedBy');
}

public function createdContacts(): HasMany
{
    return $this->hasMany(Contact::class, 'createdBy');
}

public function updatedContacts(): HasMany
{
    return $this->hasMany(Contact::class, 'updatedBy');
}

public function deletedContacts(): HasMany
{
    return $this->hasMany(Contact::class, 'deletedBy');
}
}
