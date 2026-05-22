<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
             /*
            |--------------------------------------------------------------------------
            | Identifiers
            |--------------------------------------------------------------------------
            */

            'id' => $this->id,

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'name' => $this->name,

            'nameAr' => $this->name_ar,

            'description' => $this->description,

            'barcode' => $this->barcode,

            'code' => $this->code,

            'unity' => $this->unity,

            /*
            |--------------------------------------------------------------------------
            | Product Settings
            |--------------------------------------------------------------------------
            */

            'isInteger' => (bool) $this->is_integer,

            'isOnline' => (bool) $this->is_online,

            'inputPrice' => (bool) $this->input_price,

            /*
            |--------------------------------------------------------------------------
            | Prices
            |--------------------------------------------------------------------------
            */

            'buyPrice' => (double) $this->buy_price,

            'sellPrice' => (double) $this->sell_price,

            'sellPrice1' => (double) $this->sell_price_1,

            'sellPrice2' => (double) $this->sell_price_2,

            /*
            |--------------------------------------------------------------------------
            | Margins & TVA
            |--------------------------------------------------------------------------
            */

            'tva' => (double) $this->tva,

            'marge' => (double) $this->marge,

            'marge1' => (double) $this->marge_1,

            'marge2' => (double) $this->marge_2,

            'ttc' => (double) $this->ttc,

            /*
            |--------------------------------------------------------------------------
            | Stock
            |--------------------------------------------------------------------------
            */

            'stock' => (double) $this->stock,

            'stockValue' => (double) $this->stock_value,

            'alert' => (double) $this->alert,

            'packaging' => (int) $this->packaging,

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'fabDate' => $this->fab_date?->timestamp,

            'perDate' => $this->per_date?->timestamp,

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            'brandId' => $this->brand_id,

            'brand' => $this->whenLoaded(
                'brand',
                fn () => [
                    'id' => $this->brand->id,
                    'name' => $this->brand->name,
                ]
            ),

            'categories' => $this->whenLoaded(
                'categories',
                fn () => $this->categories->map(function ($category) {

                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                    ];
                })
            ),

            /*
            |--------------------------------------------------------------------------
            | Media
            |--------------------------------------------------------------------------
            */

            'image' => $this->getFirstMediaUrl('product'),

            /*
            |--------------------------------------------------------------------------
            | Extra Data
            |--------------------------------------------------------------------------
            */

            'data' => $this->data,

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            'createdBy' => $this->created_by,

            'updatedBy' => $this->updated_by,

            'deletedBy' => $this->deleted_by,

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            'createdAt' => $this->created_at?->timestamp,

            'updatedAt' => $this->updated_at?->timestamp,

            'deletedAt' => $this->deleted_at?->timestamp,

            /*
            |--------------------------------------------------------------------------
            | Audit Relations
            |--------------------------------------------------------------------------
            */

            'createdByUser' => $this->whenLoaded(
                'createdBy',
                fn () => [
                    'id' => $this->createdBy->id,
                    'name' => $this->createdBy->name,
                ]
            ),

            'updatedByUser' => $this->whenLoaded(
                'updatedBy',
                fn () => [
                    'id' => $this->updatedBy->id,
                    'name' => $this->updatedBy->name,
                ]
            ),
        ];
    }
}
