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
        Schema::create('contacts', function (Blueprint $table) {
            $table->uuid("id")->primary();

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            $table->string('type')->nullable();

            $table->string('first_name')->nullable();

            $table->string('last_name')->nullable();

            $table->string('company_name')->nullable();

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

            $table->string('fax')->nullable();

            $table->string('fix')->nullable();

            $table->string('code')->nullable();

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

            $table->foreignUuidFor(User::class, 'created_by')
                ->nullable();

            $table->foreignUuidFor(User::class, 'updated_by')
                ->nullable();

            $table->foreignUuidFor(User::class, 'deleted_by')
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
