<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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

            'nameAr' => $this->nameAr,

            'description' => $this->description,

            'barcode' => $this->barcode,

            'code' => $this->code,

            'unity' => $this->unity,

            /*
            |--------------------------------------------------------------------------
            | Product Settings
            |--------------------------------------------------------------------------
            */

            'isInteger' => (bool) $this->isInteger,

            'isOnline' => (bool) $this->isOnline,

            'inputPrice' => (bool) $this->inputPrice,

            /*
            |--------------------------------------------------------------------------
            | Prices
            |--------------------------------------------------------------------------
            */

            'buyPrice' => (double) $this->buyPrice,

            'sellPrice' => (double) $this->sellPrice,

            'sellPrice1' => (double) $this->sellPrice1,

            'sellPrice2' => (double) $this->sellPrice2,

            /*
            |--------------------------------------------------------------------------
            | Margins & TVA
            |--------------------------------------------------------------------------
            */

            'tva' => (double) $this->tva,

            'marge' => (double) $this->marge,

            'marge1' => (double) $this->marge1,

            'marge2' => (double) $this->marge2,

            'ttc' => (double) $this->ttc,

            /*
            |--------------------------------------------------------------------------
            | Stock
            |--------------------------------------------------------------------------
            */

            'stock' => (double) $this->stock,
            'rest' => (double) $this->rest,

            'stockValue' => (double) $this->stockValue,

            'alert' => (double) $this->alert,

            'packaging' => (int) $this->packaging,

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'fabDate' => $this->fabDate?->timestamp,

            'perDate' => $this->perDate?->timestamp,

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            'brandId' => $this->brandId,

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

            'createdBy' => $this->createdByUser?->id,

            'updatedBy' => $this->updatedByUser?->id    ,

            'deletedBy' => $this->deletedByUser?->id        ,

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */
            'deletedAt' =>$this->deletedAt,

            'createdAt' =>$this->created_at==null?null:Carbon::parse($this->created_at)->getTimestampMs(),
            'updatedAt' => $this->updated_at==null?null:Carbon::parse($this->updated_at)->getTimestampMs(),
            'deleted_at' => $this->deleted_at==null?null:Carbon::parse($this->deleted_at)->getTimestampMs(),


        ];
    }
}
