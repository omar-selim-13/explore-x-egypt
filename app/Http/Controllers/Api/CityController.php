<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\CityResourse;
use App\Models\City;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CityController extends Controller
{

    use ApiResponseTrait;

    public function index()
    {
        $cities = city::get();

        if ($cities) {
            return $this->apiresponse(200, 'data retrived successfully', cityResourse::collection($cities));
        }
        return $this->apiresponse(404, 'data not found', null);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_name' => 'required|max:60',
            'total_locations' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->apiresponse(422, $validator->errors(), null);
        }

        $city = City::create($request->all());

        if ($city) {
            return $this->apiresponse(201, 'data created successfully', new CityResourse($city));
        }

        return $this->apiresponse(400, 'data not created', null);
    }


    public function show(string $id)
    {
        $city = City::find($id);

        if ($city) {
            return $this->apiresponse(200, 'data retrived successfully', new CityResourse($city));
        }

        return $this->apiresponse(404, 'data not found', null);
    }


    public function update(Request $request, string $id)
    {
        try {
            $city = City::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $this->apiresponse(404, 'City not found', []);
        }

        $validator = Validator::make($request->all(), [
            'city_name' => 'required|max:60',
            'total_locations' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->apiresponse(400, $validator->errors(), null);
        }

        $updated = $city->update();

        if ($updated) {
            return $this->apiresponse(201, 'data updated successfully', new CityResourse($city));
        }
    }


    public function destroy(string $id)
    {
        try {
            $city = City::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $this->apiresponse(404, 'City not found', []);
        }
        
         $city->locations()->delete(); // Delete all associated locations
         $deleted = $city->delete();


        if ($deleted) {
            return $this->apiresponse(200, 'data deleted successfully', []);
        }
    }


    public function search(Request $request)
    {

        $word = $request->has('search') ? $request->input('search') : null;
        $city = city::when($word != null, function ($q) use ($word) {
            $q->where('city_name', 'like', '%' . $word . '%');
        })->latest()->get();

        if (count($city) > 0) {
            return $this->apiresponse(200, 'Search completed', CityResourse::collection($city));
        }

        return $this->apiresponse(200, 'No matching data', null);
    }
}
