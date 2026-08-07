<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
             /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->uuid('id')->primary();

            /*
            |--------------------------------------------------------------------------
            | Basic Info
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            $table->string('nameAr')->nullable();

            $table->text('location')->nullable();

            $table->text('description')->nullable();

            $table->string('type')->nullable();

            $table->integer('createdAt')->nullable();
            $table->integer('updatedAt')->nullable();
            $table->integer('deletedAt')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $table->foreignUuidFor(User::class, 'createdBy')->nullable();

            $table->foreignUuidFor(User::class, 'updatedBy')->nullable();

            $table->foreignUuidFor(User::class, 'deletedBy')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
