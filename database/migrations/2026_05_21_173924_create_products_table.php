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

            $table->string('nameAr')->nullable();

            $table->text('description')->nullable();

            $table->string('barcode')->nullable();

            $table->string('code')->nullable();

            $table->string('unity')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Product Settings
            |--------------------------------------------------------------------------
            */

            $table->boolean('isInteger')->default(false);

            $table->boolean('isOnline')->default(false);

            $table->boolean('inputPrice')->default(false);

            /*
            |--------------------------------------------------------------------------
            | Prices
            |--------------------------------------------------------------------------
            */

            $table->double('buyPrice')->default(0);

            $table->double('sellPrice')->default(0);

            $table->double('sellPrice1')->default(0);

            $table->double('sellPrice2')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Margins & TVA
            |--------------------------------------------------------------------------
            */

            $table->double('tva')->default(0);

            $table->double('marge')->default(0);

            $table->double('marge1')->default(0);

            $table->double('marge2')->default(0);

            $table->double('ttc')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Stock
            |--------------------------------------------------------------------------
            */

            $table->double('stock')->default(0);

            $table->double('stockValue')->default(0);

            $table->double('alert')->default(0);

            $table->integer('packaging')->default(1);

            $table->bigInteger('createdAt')->nullable();
            $table->bigInteger('updatedAt')->nullable();
            $table->bigInteger('deletedAt')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            $table->timestamp('fabDate')->nullable();

            $table->timestamp('perDate')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            $table->foreignUuidFor(Brand::class, 'brandId')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

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
