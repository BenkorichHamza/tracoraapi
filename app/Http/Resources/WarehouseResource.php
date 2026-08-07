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

            'createdBy' => $this->createdBy,

            'updatedBy' => $this->updatedBy,

            'deletedBy' => $this->deletedBy,



            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'created_at' =>$this->created_at==null?null:Carbon::parse($this->created_at)->getTimestampMs(),
            'updated_at' => $this->updated_at==null?null:Carbon::parse($this->updated_at)->getTimestampMs(),
            'deleted_at' => $this->deleted_at==null?null:Carbon::parse($this->deleted_at)->getTimestampMs(),

            'deletedAt' => $this->deletedAt,
        ];
    }
}
