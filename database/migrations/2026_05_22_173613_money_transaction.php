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
        Schema::create('money_transactions', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->uuid('id')->primary();

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            $table->foreignUuidFor(User::class, 'user_id')
                ->nullable();

            $table->foreignUuidFor(User::class, 'employee_id')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Transaction Data
            |--------------------------------------------------------------------------
            */

            $table->integer('status')->default(0);

            $table->double('credit')->default(0);

            $table->double('amount')->default(0);

            $table->string('type')->nullable();

            $table->text('description')->nullable();

            $table->timestamp('datetime')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Extra (optional mission link from offline system)
            |--------------------------------------------------------------------------
            */

            $table->string('mission_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $table->foreignUuidFor(User::class, 'created_by')->nullable();

            $table->foreignUuidFor(User::class, 'updated_by')->nullable();

            $table->foreignUuidFor(User::class, 'deleted_by')->nullable();

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
                Schema::dropIfExists('money_transactions');

    }
};
