<?php

namespace App\Http\Resources;

use App\Models\Exchange_rate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurshaseResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $currentRate = Exchange_rate::latest()->first();
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'store_name' => $this->store?->name,
            'date' => $this->date,
            'sum' => $this->sum,
            'currency' => $this->currency,
            'sum_rub' => number_format($this->sum / $currentRate->{$this->currency}, 2, '.', ''),
            'document' => $this->document,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
