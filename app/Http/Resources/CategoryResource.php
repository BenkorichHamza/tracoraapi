<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // IDs (important for sync)
            'id' => $this->id,

            // Core data
            'name' => $this->name,
            'nameAr' => $this->name_ar,
            'image' => $this->getFirstMediaUrl('category'),


            // Audit (sync system)
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'deletedBy' => $this->deletedBy,

            // Timestamps (Flutter-friendly)
            'createdAt' => $this->createdAt,
'updatedAt' => $this->updatedAt,
'deletedAt' => $this->deletedAt,

           
        ];
    }
}
