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

            'firstName' => $this->firstName,

            'lastName' => $this->lastName,

            'email' => $this->email,

            'companyName' => $this->companyName,
            'warehouse' => $this->warehouse,
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
'deletedAt' => $this->deletedAt,

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */



            'user' => $this->whenLoaded('user'),
        ];
    }
}
