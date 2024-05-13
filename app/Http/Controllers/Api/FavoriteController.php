<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
//use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\FavoriteResourse;
use App\Http\Resources\LocationResourse;
use App\Http\Resources\FavoriteUserResourse;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $favorites = Favorite::get();

        if ($favorites) {
         return $this->apiresponse(200, 'data retrived successfully', FavoriteResourse::collection($favorites));
        }
      return $this->apiresponse(404, 'data not found', null);   
     }

     public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->apiresponse(422, $validator->errors(), null);
        }

        $data =  $request->all();
        $data['user_id'] = $request->user()->id;

        $favorite = Favorite::create($data);

        if ($favorite) {
            return $this->apiresponse(201, 'data created successfully', new FavoriteResourse($favorite));
        }

        return $this->apiresponse(400, 'data not created', null);
    }

    public function show(string $id)
    {
        $favorite = Favorite::find($id);

        if ($favorite) {
            return $this->apiresponse(200, 'data retrived successfully', new FavoriteResourse($favorite));
        }

        return $this->apiresponse(404, 'data not found', null);
    }


    public function destroy(string $id)
    {
        try {   $favorite = Favorite::findOrFail($id);   } 
        catch (ModelNotFoundException $e) {  return $this->apiresponse(404, 'Favorite not found', []);  }       
        
        $deleted = $favorite->delete();

        if ($deleted) {
          return $this->apiresponse(200, 'data deleted successfully', []);
        }

    }

    // public function search(Request $request)
    // {

    //     $word = $request->has('search') ? $request->input('search') : null;
    //     $favorite = Favorite::when($word != null, function ($q) use ($word) {
    //         $q->where('location_id', 'like', '%' . $word . '%');
    //     })->latest()->get();

    //     if (count($favorite) > 0) {
    //         return $this->apiresponse(200, 'Search completed', FavoriteResourse::collection($favorite));
    //     }

    //     return $this->apiresponse(200, 'No matching data', null);
    // }



public function showUserFavorite(Request $request)
{
    $user = $request->user();
    
    if ($user) {
        try {
            $favorites = Favorite::with('locations')->where('user_id', $request->user()->id)->latest()->get();    //with('locations')
            
            if ($favorites->isNotEmpty()) {
                return $this->apiresponse(200, 'favorites retrieved successfully', FavoriteUserResourse::collection($favorites));
            } else {
                return $this->apiresponse(404, 'No favorites found for the user', null);
            }
        } catch (ModelNotFoundException $exception) {
            return $this->apiresponse(404, 'User not found', null);
        }
    } else {
        return $this->apiresponse(401, 'Unauthenticated', null);
    }
}

    

    
}

   

