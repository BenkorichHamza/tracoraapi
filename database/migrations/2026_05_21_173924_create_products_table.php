<?php

use App\Models\Brand;
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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            $table->string('name_ar')->nullable();

            $table->text('description')->nullable();

            $table->string('barcode')->nullable();

            $table->string('code')->nullable();

            $table->string('unity')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Product Settings
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_integer')->default(false);

            $table->boolean('is_online')->default(false);

            $table->boolean('input_price')->default(false);

            /*
            |--------------------------------------------------------------------------
            | Prices
            |--------------------------------------------------------------------------
            */

            $table->double('buy_price')->default(0);

            $table->double('sell_price')->default(0);

            $table->double('sell_price_1')->default(0);

            $table->double('sell_price_2')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Margins & TVA
            |--------------------------------------------------------------------------
            */

            $table->double('tva')->default(0);

            $table->double('marge')->default(0);

            $table->double('marge_1')->default(0);

            $table->double('marge_2')->default(0);

            $table->double('ttc')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Stock
            |--------------------------------------------------------------------------
            */

            $table->double('stock')->default(0);

            $table->double('stock_value')->default(0);

            $table->double('alert')->default(0);

            $table->integer('packaging')->default(1);

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            $table->timestamp('fab_date')->nullable();

            $table->timestamp('per_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            $table->foreignUuidFor(Brand::class, 'brand_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

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
            | Extra Data
            |--------------------------------------------------------------------------
            */

            $table->json('data')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Laravel Timestamps
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
        Schema::dropIfExists('products');
    }
};
