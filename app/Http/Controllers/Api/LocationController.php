<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResourse;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LocationController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $locations = Location::get();

        if ($locations) {
         return $this->apiresponse(200, 'data retrived successfully', LocationResourse::collection($locations));
        }
      return $this->apiresponse(404, 'data not found', null);   
     }


    public function store(LocationRequest $request)
    {
        $data = $request->validated();
        $location = Location::create($data);

        if ($location) {
            return $this->apiresponse(201, 'data created successfully', new LocationResourse($location));
        }

        return $this->apiresponse(400, 'data not created', null);
    }


    public function show(string $id)
    {
        $location = Location::find($id);

        if ($location) {
            return $this->apiresponse(200, 'data retrived successfully', new LocationResourse($location));
        }

        return $this->apiresponse(404, 'data not found', null);
    }


    public function update(LocationRequest $request, $id)
    {
        /* $validator = Validator::make($request->all(), [
            'location_name' => 'required|max:60',
            'type' => 'required|max:50',
            'image' => 'required',
            'description' => 'required|max:255',
            'total_artifacts' => 'required',
            'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiresponse(400, $validator->errors(), null);
        } */

        try {   $location = Location::findOrFail($id);   } 
        catch (ModelNotFoundException $e) {  return $this->apiresponse(404, 'Location not found', []);  }  

        $data = $request->validated();

        $updated = $location->update($data);

        if ($updated) {
         return $this->apiresponse(201, 'data updated successfully', new LocationResourse($location));
        }
    }


    public function destroy(string $id)
    {
        try {   $location = Location::findOrFail($id);   } 
        catch (ModelNotFoundException $e) {  return $this->apiresponse(404, 'Location not found', []);  }       
        
        $deleted = $location->delete();

        if ($deleted) {
          return $this->apiresponse(200, 'data deleted successfully', []);
        }

    }


    public function search(Request $request)
    {

        $word = $request->has('search') ? $request->input('search') : null;
        $location = location::when($word != null, function ($q) use ($word) {
            $q->where('location_name', 'like', '%' . $word . '%');
        })->latest()->get();

        if (count($location) > 0) {
            return $this->apiresponse(200, 'Search completed', LocationResourse::collection($location));
        }

        return $this->apiresponse(200, 'No matching data', null);
    }
}
