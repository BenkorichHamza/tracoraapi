<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "status" => $this->status,
            "employeeId" => $this->employeeId,
            "employee" => $this->employee,
            "userId" => $this->userId,
            "user" => $this->user,
            "from_warehouse" => $this->from_warehouse,
            "fromWarehouse" => $this->fromWarehouse,
            "to_warehouse" => $this->to_warehouse,
            "toWarehouse" => $this->toWarehouse,
            "type" => $this->type,
            "description" => $this->description,
            "topay" => $this->topay,
            "total" => $this->total,
            "credit" => $this->credit,
            "payment" => $this->payment,
            "productTransactions" => $this->whenLoaded('products', function () {
            return $this->products->map(function ($product) {
                // Return pivot data merged with product identification if needed
                return
                    $product->pivot->toArray()
                ;
            });
        }),
            "tax" => $this->tax,
            'dateTime' =>$this->datetime==null?null:Carbon::parse($this->datetime)->getTimestampMs(),
            'createdAt' =>$this->created_at==null?null:Carbon::parse($this->created_at)->getTimestampMs(),
            'updatedAt' => $this->updated_at==null?null:Carbon::parse($this->updated_at)->getTimestampMs(),
            'deleted_at' => $this->deleted_at==null?null:Carbon::parse($this->deleted_at)->getTimestampMs(),
            'deletedAt' =>$this->deletedAt,
            "createdBy" => $this->createdBy,
            "createdByUser" => $this->createdByUser,
            "updatedBy" => $this->updatedBy,
            "updatedByUser" => $this->updatedByUser,
        ];
    }
}
