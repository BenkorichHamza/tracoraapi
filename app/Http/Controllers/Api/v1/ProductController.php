<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use DateTime;
 use Illuminate\Support\Carbon;
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
                'createdByUser',
                'updatedByUser',
            ])

            ->latest()

            ->paginate(20);

        return ProductResource::collection($products);
    }



public function syncProduct(Request $request)
{
    // Validate and cast timestamp safely
$lastSyncMs = $request->input('last_sync_date');
$lastSyncDate = is_numeric($lastSyncMs) && $lastSyncMs > 0
    ? Carbon::createFromTimestampMs((int) $lastSyncMs)
    : null;

$products = Product::query()
    ->with([
        'media',
        'brand',
        'categories',
        'createdByUser',
        'updatedByUser',
        'deletedByUser',
    ])
    ->when($lastSyncDate, function ($query, $lastSyncDate) {
        $query->where('updated_at', '>=', $lastSyncDate)
              ->where(function ($subQuery) {
                  // Handles both NULL updatedBy and non-current user updates
                  $subQuery->whereNull('updatedBy')
                           ->orWhere('updatedBy', '<>', auth()->id());
              });
    }, function ($query) {
        // Fallback baseline when no lastSyncDate is provided
        $query->where('updated_at', '>', Carbon::parse('2026-01-01'));
    })
    ->latest('updated_at')
    ->get();

return ProductResource::collection($products);
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->image === '') {
        $request->merge([
            'image' => null,
        ]);
    }
        $validated = $request->validate([

            'id'=>['uuid'],
            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'name' => ['required', 'string', 'max:255'],

            'nameAr' => ['nullable', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'barcode' => ['nullable', 'string', 'max:255'],

            'code' => ['nullable', 'string', 'max:255'],

            'unity' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | Product Settings
            |--------------------------------------------------------------------------
            */

            'isInteger' => ['nullable', 'boolean'],

            'isOnline' => ['nullable', 'boolean'],

            'inputPrice' => ['nullable', 'boolean'],

            /*
            |--------------------------------------------------------------------------
            | Prices
            |--------------------------------------------------------------------------
            */

            'buyPrice' => ['nullable', 'numeric'],

            'sellPrice' => ['nullable', 'numeric'],

            'sellPrice1' => ['nullable', 'numeric'],

            'sellPrice2' => ['nullable', 'numeric'],

            /*
            |--------------------------------------------------------------------------
            | Margins & TVA
            |--------------------------------------------------------------------------
            */

            'tva' => ['nullable', 'numeric'],

            'marge' => ['nullable', 'numeric'],

            'marge1' => ['nullable', 'numeric'],

            'marge2' => ['nullable', 'numeric'],

            'ttc' => ['nullable', 'numeric'],

            /*
            |--------------------------------------------------------------------------
            | Stock
            |--------------------------------------------------------------------------
            */

            'stock' => ['nullable', 'numeric'],

            'stockValue' => ['nullable', 'numeric'],

            'alert' => ['nullable', 'numeric'],

            'packaging' => ['nullable', 'integer'],

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'fabDate' => ['nullable', 'date'],

            'perDate' => ['nullable', 'date'],

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            'brandId' => [
                'nullable',
                'uuid',
                'exists:brands,id'
            ],

            'deletedAt' => ['nullable', 'numeric'],


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

        $validated['createdBy'] = auth()->id();

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
                'categories'
            ])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {


        $validated = $request->validate([

            'id' =>['uuid'],

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'name' => ['sometimes', 'required', 'string', 'max:255'],

            'nameAr' => ['nullable', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'barcode' => ['nullable', 'string', 'max:255'],

            'code' => ['nullable', 'string', 'max:255'],

            'unity' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | Product Settings
            |--------------------------------------------------------------------------
            */

            'isInteger' => ['nullable', 'boolean'],

            'isOnline' => ['nullable', 'boolean'],

            'inputPrice' => ['nullable', 'boolean'],

            /*
            |--------------------------------------------------------------------------
            | Prices
            |--------------------------------------------------------------------------
            */

            'buyPrice' => ['nullable', 'numeric'],

            'sellPrice' => ['nullable', 'numeric'],

            'sellPrice1' => ['nullable', 'numeric'],

            'sellPrice2' => ['nullable', 'numeric'],

            'deletedAt' => ['nullable', 'numeric'],

            /*
            |--------------------------------------------------------------------------
            | Margins & TVA
            |--------------------------------------------------------------------------
            */

            'tva' => ['nullable', 'numeric'],

            'marge' => ['nullable', 'numeric'],

            'marge1' => ['nullable', 'numeric'],

            'marge2' => ['nullable', 'numeric'],

            'ttc' => ['nullable', 'numeric'],

            /*
            |--------------------------------------------------------------------------
            | Stock
            |--------------------------------------------------------------------------
            */

            'stock' => ['nullable', 'numeric'],

            'stockValue' => ['nullable', 'numeric'],

            'alert' => ['nullable', 'numeric'],

            'packaging' => ['nullable', 'integer'],

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'fabDate' => ['nullable', 'date'],

            'perDate' => ['nullable', 'date'],

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            'brandId' => [
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

        $validated['updatedBy'] = auth()->id();

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
                'categories'
            ])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
         $product->update([
            'deletedBy' => auth()->id(),
        ]);

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ]);
    }
}
