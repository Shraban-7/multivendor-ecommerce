<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->first()->option->id ?? null,
            'name' => $this->first()->option->name ?? null,
            'values' => $this->unique('id')->map(fn ($v) => [
                'id' => $v->id,
                'value' => $v->value,
            ])->values()->toArray(),
        ];
    }
}
