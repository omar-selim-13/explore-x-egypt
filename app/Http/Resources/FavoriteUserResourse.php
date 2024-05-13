<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteUserResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
{
    $location = $this->location;
    
    if ($location) {
        $data = [
            'id' => $this->id,
            'location_id' => $this->location_id,
            'user_id' => $this->user_id,
        ];

        $data['location_name'] = $location->location_name;
        $data['type'] = $location->type;
        $data['image'] = $location->image;
        $data['description'] = $location->description;

        if ($location->city) {
            $data['city'] = $location->city->city_name;
        }
   } else {
        $data = [
            'id' => $this->id,
            'location_id' => $this->location_id,
            'user_id' => $this->user_id,
            //'location' => null,
        ];
   }

    return $data;
}

}