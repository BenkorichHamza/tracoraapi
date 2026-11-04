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
        Schema::create('stransactions', function (Blueprint $table) {
             /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->uuid('id')->primary();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->integer('status')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Users / Employees
            |--------------------------------------------------------------------------
            */

            $table->foreignUuid('employeeId')
                ->nullable()
                ->constrained('contacts')
                ->nullOnDelete();

            $table->foreignUuid('userId')
                ->nullable()
                ->constrained('contacts')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Warehouses
            |--------------------------------------------------------------------------
            */

            $table->foreignUuid('from_warehouse')
                ->nullable()
                ->constrained('warehouses')
                ->nullOnDelete();

            $table->foreignUuid('to_warehouse')
                ->nullable()
                ->constrained('warehouses')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Transaction Data
            |--------------------------------------------------------------------------
            */

            $table->string('type')->nullable();

            $table->text('description')->nullable();

            $table->double('topay')->default(0);

            $table->double('total')->default(0);

            $table->double('credit')->default(0);

            $table->double('payment')->default(0);

            $table->double('tax')->default(0);

            $table->timestamp('datetime')->nullable();


            $table->bigInteger('createdAt')->nullable();
            $table->bigInteger('updatedAt')->nullable();
            $table->bigInteger('deletedAt')->nullable();
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
            | Laravel timestamps
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
        Schema::dropIfExists('stransactions');
    }
};
