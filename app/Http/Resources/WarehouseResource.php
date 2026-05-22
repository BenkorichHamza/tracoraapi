<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ['id' => $this->id,

            'name' => $this->name,

            'nameAr' => $this->name_ar,

            'location' => $this->location,

            'description' => $this->description,

            'type' => $this->type,

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
            | Relations
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

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            'createdAt' => $this->created_at?->timestamp,

            'updatedAt' => $this->updated_at?->timestamp,

            'deletedAt' => $this->deleted_at?->timestamp,
        ];
    }
}
