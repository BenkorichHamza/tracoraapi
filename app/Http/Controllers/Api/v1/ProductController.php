<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $products = Product::query()

            ->with([
                'media',
                'brand',
                'categories',
                'createdBy',
                'updatedBy',
            ])

            ->latest()

            ->paginate(20);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'name' => ['required', 'string', 'max:255'],

            'name_ar' => ['nullable', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'barcode' => ['nullable', 'string', 'max:255'],

            'code' => ['nullable', 'string', 'max:255'],

            'unity' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | Product Settings
            |--------------------------------------------------------------------------
            */

            'is_integer' => ['nullable', 'boolean'],

            'is_online' => ['nullable', 'boolean'],

            'input_price' => ['nullable', 'boolean'],

            /*
            |--------------------------------------------------------------------------
            | Prices
            |--------------------------------------------------------------------------
            */

            'buy_price' => ['nullable', 'numeric'],

            'sell_price' => ['nullable', 'numeric'],

            'sell_price_1' => ['nullable', 'numeric'],

            'sell_price_2' => ['nullable', 'numeric'],

            /*
            |--------------------------------------------------------------------------
            | Margins & TVA
            |--------------------------------------------------------------------------
            */

            'tva' => ['nullable', 'numeric'],

            'marge' => ['nullable', 'numeric'],

            'marge_1' => ['nullable', 'numeric'],

            'marge_2' => ['nullable', 'numeric'],

            'ttc' => ['nullable', 'numeric'],

            /*
            |--------------------------------------------------------------------------
            | Stock
            |--------------------------------------------------------------------------
            */

            'stock' => ['nullable', 'numeric'],

            'stock_value' => ['nullable', 'numeric'],

            'alert' => ['nullable', 'numeric'],

            'packaging' => ['nullable', 'integer'],

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'fab_date' => ['nullable', 'date'],

            'per_date' => ['nullable', 'date'],

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            'brand_id' => [
                'nullable',
                'uuid',
                'exists:brands,id'
            ],

            'categories' => ['nullable', 'array'],

            'categories.*' => [
                'uuid',
                'exists:categories,id'
            ],

            /*
            |--------------------------------------------------------------------------
            | Media
            |--------------------------------------------------------------------------
            */

            'image' => ['nullable', 'image', 'max:5120'],

            /*
            |--------------------------------------------------------------------------
            | Extra Data
            |--------------------------------------------------------------------------
            */

            'data' => ['nullable', 'array'],
        ]);

        $validated['created_by'] = auth()->id();

        $product = Product::create(

            collect($validated)
                ->except([
                    'image',
                    'categories',
                ])
                ->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        if (isset($validated['categories'])) {

            $product->categories()->sync(
                $validated['categories']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Media
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $product
                ->addMediaFromRequest('image')
                ->toMediaCollection('product', 'public');
        }

        return new ProductResource(
            $product->load([
                'media',
                'brand',
                'categories',
                'createdBy',
            ])
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource(
            $product->load([
                'media',
                'brand',
                'categories',
                'createdBy',
                'updatedBy',
            ])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'name' => ['sometimes', 'required', 'string', 'max:255'],

            'name_ar' => ['nullable', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'barcode' => ['nullable', 'string', 'max:255'],

            'code' => ['nullable', 'string', 'max:255'],

            'unity' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | Product Settings
            |--------------------------------------------------------------------------
            */

            'is_integer' => ['nullable', 'boolean'],

            'is_online' => ['nullable', 'boolean'],

            'input_price' => ['nullable', 'boolean'],

            /*
            |--------------------------------------------------------------------------
            | Prices
            |--------------------------------------------------------------------------
            */

            'buy_price' => ['nullable', 'numeric'],

            'sell_price' => ['nullable', 'numeric'],

            'sell_price_1' => ['nullable', 'numeric'],

            'sell_price_2' => ['nullable', 'numeric'],

            /*
            |--------------------------------------------------------------------------
            | Margins & TVA
            |--------------------------------------------------------------------------
            */

            'tva' => ['nullable', 'numeric'],

            'marge' => ['nullable', 'numeric'],

            'marge_1' => ['nullable', 'numeric'],

            'marge_2' => ['nullable', 'numeric'],

            'ttc' => ['nullable', 'numeric'],

            /*
            |--------------------------------------------------------------------------
            | Stock
            |--------------------------------------------------------------------------
            */

            'stock' => ['nullable', 'numeric'],

            'stock_value' => ['nullable', 'numeric'],

            'alert' => ['nullable', 'numeric'],

            'packaging' => ['nullable', 'integer'],

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'fab_date' => ['nullable', 'date'],

            'per_date' => ['nullable', 'date'],

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            'brand_id' => [
                'nullable',
                'uuid',
                'exists:brands,id'
            ],

            'categories' => ['nullable', 'array'],

            'categories.*' => [
                'uuid',
                'exists:categories,id'
            ],

            /*
            |--------------------------------------------------------------------------
            | Media
            |--------------------------------------------------------------------------
            */

            'image' => ['nullable', 'image', 'max:5120'],

            /*
            |--------------------------------------------------------------------------
            | Extra Data
            |--------------------------------------------------------------------------
            */

            'data' => ['nullable', 'array'],
        ]);

        $validated['updated_by'] = auth()->id();

        $product->update(

            collect($validated)
                ->except([
                    'image',
                    'categories',
                ])
                ->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        if (isset($validated['categories'])) {

            $product->categories()->sync(
                $validated['categories']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Media
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $product->clearMediaCollection('product');

            $product
                ->addMediaFromRequest('image')
                ->toMediaCollection('product', 'public');
        }

        return new ProductResource(
            $product->load([
                'media',
                'brand',
                'categories',
                'createdBy',
                'updatedBy',
            ])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
         $product->update([
            'deleted_by' => auth()->id(),
        ]);

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ]);
    }
}
