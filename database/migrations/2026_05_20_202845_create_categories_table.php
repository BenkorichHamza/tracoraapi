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
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('nameAr')->nullable();
            $table->integer('createdAt')->nullable();
            $table->integer('updatedAt')->nullable();
            $table->integer('deletedAt')->nullable();
            $table->foreignUuidFor(User::class,'createdBy')->nullable();
            $table->foreignUuidFor(User::class,'updatedBy')->nullable();
            $table->foreignUuidFor(User::class,'deletedBy')->nullable();
            /*
            |--------------------------------------------------------------------------
            | Extra Data
            |--------------------------------------------------------------------------
            */

            $table->json('data')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
