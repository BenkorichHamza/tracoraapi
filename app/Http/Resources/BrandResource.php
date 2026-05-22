<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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

            // Audit (sync system)
            'createdBy' => $this->created_by,
            'updatedBy' => $this->updated_by,
            'deletedBy' => $this->deleted_by,

            // Timestamps (Flutter-friendly)
            'createdAt' => $this->created_at?->timestamp,
            'updatedAt' => $this->updated_at?->timestamp,
            'deletedAt' => $this->deleted_at?->timestamp,

            // Optional relations (avoid heavy loading by default)
            'createdByUser' => $this->whenLoaded('createdBy', function () {
                return [
                    'id' => $this->createdBy->id,
                    'name' => $this->createdBy->name,
                    'email' => $this->createdBy->email,
                ];
            }),

            'updatedByUser' => $this->whenLoaded('updatedBy', function () {
                return [
                    'id' => $this->updatedBy->id,
                    'name' => $this->updatedBy->name,
                    'email' => $this->updatedBy->email,
                ];
            }),
        ];
    }
}
