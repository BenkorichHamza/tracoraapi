<?php

use App\Models\Category;
use App\Models\Product;
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
       Schema::create('category_product', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            $table->foreignUuidFor(Product::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignUuidFor(Category::class)
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Constraints
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'product_id',
                'category_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_product');
    }
};
