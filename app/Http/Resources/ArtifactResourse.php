<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtifactResourse extends JsonResource
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
            'artifact_name'=> $this->artifact_name,
            'date'=> $this->date,
            'image'=> $this->image,
            'description'=> $this->description,
            'location_name'=> $this->location->location_name,
        ];    }
}
