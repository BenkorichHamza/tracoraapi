<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class MoneyTransactionResource extends JsonResource
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
            "type" => $this->type,
            "description" => $this->description,
            "amount" => $this->amount,
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
