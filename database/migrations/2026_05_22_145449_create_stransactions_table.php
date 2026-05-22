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

            $table->foreignUuid('employee_id')
                ->nullable()
                ->constrained('contacts')
                ->nullOnDelete();

            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained('contacts')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Warehouses
            |--------------------------------------------------------------------------
            */

            $table->foreignUuid('from_warehouse_id')
                ->nullable()
                ->constrained('warehouses')
                ->nullOnDelete();

            $table->foreignUuid('to_warehouse_id')
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
