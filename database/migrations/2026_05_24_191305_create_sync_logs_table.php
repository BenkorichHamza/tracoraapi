<?php

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
       Schema::create('sync_logs', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->uuid('id')->primary();

            /*
            |--------------------------------------------------------------------------
            | Source Info
            |--------------------------------------------------------------------------
            */

            $table->string('table_name');
            $table->uuid('row_id');

            /*
            |--------------------------------------------------------------------------
            | Operation Type
            |--------------------------------------------------------------------------
            */

            $table->string('operation');
            // INSERT | UPDATE | DELETE

            /*
            |--------------------------------------------------------------------------
            | Data Snapshot
            |--------------------------------------------------------------------------
            */

            $table->json('data')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Sync Metadata
            |--------------------------------------------------------------------------
            */

            $table->uuid('user_id')->nullable();   // who made change
            $table->uuid('device_id')->nullable(); // optional multi-device support

            /*
            |--------------------------------------------------------------------------
            | Timestamp for sync pull
            |--------------------------------------------------------------------------
            */

            $table->timestamp('created_at')->useCurrent();

            /*
            |--------------------------------------------------------------------------
            | Indexes (VERY IMPORTANT for sync speed)
            |--------------------------------------------------------------------------
            */

            $table->index(['table_name', 'created_at']);
            $table->index('row_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_logs');
    }
};
