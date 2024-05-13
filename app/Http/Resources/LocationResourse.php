<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'location_name'=> $this->location_name,
            'type'=> $this->type,
            'image'=> $this->image,
            'description'=> $this->description,
            'total_artifacts'=>count([$this->artifact]),
            'city'=> $this->city->city_name,              /* cityResourse::collaction($this->city) */

        ];
    }
}
