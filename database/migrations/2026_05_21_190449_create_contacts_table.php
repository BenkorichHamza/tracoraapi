<?php

use App\Models\User;
use App\Models\Warehouse;
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
        Schema::create('contacts', function (Blueprint $table) {
            $table->uuid("id")->primary();

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            $table->string('type')->nullable();

            $table->string('firstName')->nullable();

            $table->string('lastName')->nullable();

            $table->string('companyName')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Extra Data
            |--------------------------------------------------------------------------
            */

            $table->json('data')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */

            $table->text('address')->nullable();

            $table->string('phone')->nullable();

            $table->string('email')->nullable();

            $table->string('fax')->nullable();

            $table->string('fix')->nullable();

            $table->string('code')->nullable();

            $table->integer('createdAt')->nullable();
            $table->integer('updatedAt')->nullable();
            $table->integer('deletedAt')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Financial
            |--------------------------------------------------------------------------
            */

            $table->double('due')->default(0);

            $table->double('payment')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Algerian Company Identifiers
            |--------------------------------------------------------------------------
            */

            $table->string('nif')->nullable();

            $table->string('nis')->nullable();

            $table->string('nin')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $table->foreignUuidFor(User::class, 'createdBy')
                ->nullable();

            $table->foreignUuidFor(User::class, 'updatedBy')
                ->nullable();

            $table->foreignUuidFor(User::class, 'deletedBy')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Soft Delete
            |--------------------------------------------------------------------------
            */

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Laravel Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
