<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeRateResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'usd' => $this->usd,
            'eur' => $this->eur,
            'rub' => $this->rub,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
