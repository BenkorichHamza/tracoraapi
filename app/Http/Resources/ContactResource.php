<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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

            'type' => $this->type,

            'firstName' => $this->first_name,

            'lastName' => $this->last_name,

            'companyName' => $this->company_name,
            'image' => $this->getFirstMediaUrl('contact'),

            /*
            |--------------------------------------------------------------------------
            | Contact Information
            |--------------------------------------------------------------------------
            */

            'address' => $this->address,

            'phone' => $this->phone,

            'fax' => $this->fax,

            'fix' => $this->fix,

            'code' => $this->code,

            /*
            |--------------------------------------------------------------------------
            | Financial
            |--------------------------------------------------------------------------
            */

            'due' => (double) $this->due,

            'payment' => (double) $this->payment,

            /*
            |--------------------------------------------------------------------------
            | Algerian Identifiers
            |--------------------------------------------------------------------------
            */

            'nif' => $this->nif,

            'nis' => $this->nis,

            'nin' => $this->nin,

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

            'user' => $this->whenLoaded('user'),
        ];
    }
}
