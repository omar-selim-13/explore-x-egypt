<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArtifactRequest;
use App\Http\Resources\ArtifactResourse;
use App\Models\Artifact;
use App\Models\Location;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArtifactController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $artifacts = Artifact::get();

        if ($artifacts) {
         return $this->apiresponse(200, 'data retrived successfully', ArtifactResourse::collection($artifacts));
        }
      return $this->apiresponse(404, 'data not found', null);
    }


    public function store(ArtifactRequest $request)
    {
        $data = $request->validated();
        $artifacts = Artifact::create($data);

        if ($artifacts) {
            return $this->apiresponse(201, 'data created successfully', new ArtifactResourse($artifacts));
        }

        return $this->apiresponse(400, 'data not created', null);
    }


    public function show(string $id)
    {
        $artifacts = Artifact::find($id);

        if ($artifacts) {
            return $this->apiresponse(200, 'data retrived successfully', new ArtifactResourse($artifacts));
        }

        return $this->apiresponse(404, 'data not found...', null);
    }


    public function update(ArtifactRequest $request, $id)
    {
        try {   $artifacts = Artifact::findOrFail($id);   } 
        catch (ModelNotFoundException $e) {  return $this->apiresponse(404, 'Artifact not found', []);  }  

        $data = $request->validated();

        $updated = $artifacts->update($data);

        if ($updated) {
         return $this->apiresponse(201, 'data updated successfully', new ArtifactResourse($artifacts));
        }
    }


    public function destroy(string $id)
    {
        try {   $artifacts = Artifact::findOrFail($id);   } 
        catch (ModelNotFoundException $e) {  return $this->apiresponse(404, 'Artifact not found', []);  }       
        
        $deleted = $artifacts->delete();

        if ($deleted) {
          return $this->apiresponse(200, 'data deleted successfully', []);
        }

    }


    public function search(Request $request)
    {
        $word = $request->has('search') ? $request->input('search') : null;
        $artifacts = Artifact::when($word != null, function ($q) use ($word) {
            $q->where('Artifact_name', 'like', '%' . $word . '%');
        })->latest()->get();

        if (count($artifacts) > 0) {
            return $this->apiresponse(200, 'Search completed', ArtifactResourse::collection($artifacts));
        }

        return $this->apiresponse(200, 'No matching data', null);
    }


    public function get_artifact_by_location_id(Request $request)
    { 
        $request->validate([
            'location_id' => 'required|exists:locations,id',
        ]);
       
        $artifacts = Artifact::where('location_id', $request->location_id)->latest()->get();
            
        if ($artifacts->isNotEmpty()) {
            return $this->apiresponse(200, 'artifacts retrieved successfully', ArtifactResourse::collection($artifacts));
        } else {
            return $this->apiresponse(404, 'No artifacts found', null);
        }
    }

}

