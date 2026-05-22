<?php

use App\Models\Product;
use App\Models\Stransaction;
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
         Schema::create('product_transaction', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            $table->foreignUuidFor(Stransaction::class, 'transaction_id')
                ->cascadeOnDelete();

            $table->foreignUuidFor(Product::class, 'product_id')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Item Data
            |--------------------------------------------------------------------------
            */

            $table->integer('direction')->default(1);
            // 1 = in, -1 = out

            $table->double('qte')->default(0);

            $table->double('price')->default(0);

            $table->double('tax')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Constraints
            |--------------------------------------------------------------------------
            */

            $table->primary(['transaction_id', 'product_id']);});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
