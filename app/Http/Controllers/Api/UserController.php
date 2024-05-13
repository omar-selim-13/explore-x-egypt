<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $users = User::get();

        if ($users) {
         return $this->apiresponse(200, 'data retrived successfully', UserResourse::collection($users));
        }
      return $this->apiresponse(404, 'data not found', null);   
     }


    /* public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:60', 'string'],
            'email' => ['required', 'email', 'max:80', 'unique:' . User::class,],
            'password' => ['required', 'confirmed', Rules\password::defaults()],
            'age' => ['required', 'integer', 'min:0', 'max:150'],
            'gender' => ['required', 'string', 'in:male,female',],
            'country' => ['required', 'max:50', 'string'],
            'phone_number' => ['required', 'regex:/^\+?[0-9]{1,3}?[- .]?\(?[0-9]{3}\)?[- .]?[0-9]{3}[- .]?[0-9]{4}$/i'],

        ]);
        if ($validator->fails()) {
            return $this->apiresponse(422,  'Validation Errors',$validator->messages()->all());
        }        
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'age' => $request->age,
            'gender' => $request->gender,
            'country' => $request->country,
            'phone_number' => $request->phone_number
        ]);

        $data['token'] = $user->createToken('userToken')->plainTextToken;
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['age'] = $user->age;
        $data['gender'] = $user->gender;
        $data['country'] = $user->country;
        $data['phone_number'] = $user->phone_number;

        if ($data) {
            return $this->apiresponse(201, 'data created successfully', new UserResourse($data));
        }

        return $this->apiresponse(400, 'data not created', null);
    } */


    public function show(string $id)
    {
        $user = User::find($id);

        if ($user) {
            return $this->apiresponse(200, 'data retrived successfully', new UserResourse($user));
        }

        return $this->apiresponse(404, 'data not found', null);
    }


    /* public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:60', 'string'],
            'email' => ['required', 'email', 'max:80', 'unique:' . User::class,],
            'password' => ['required', 'confirmed', Rules\password::defaults()],
            'age' => ['required', 'integer', 'min:0', 'max:150'],
            'gender' => ['required', 'string', 'in:male,female',],
            'country' => ['required', 'max:50', 'string'],
            'phone_number' => ['required', 'regex:/^\+?[0-9]{1,3}?[- .]?\(?[0-9]{3}\)?[- .]?[0-9]{3}[- .]?[0-9]{4}$/i'],

        ]);
        if ($validator->fails()) {
            return $this->apiresponse(422,  'Validation Errors',$validator->messages()->all());
        }

        try {   $user = User::findOrFail($id);   } 
        catch (ModelNotFoundException $e) {  return $this->apiresponse(404, 'User not found', []);  }  

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'age' => $request->age,
            'gender' => $request->gender,
            'country' => $request->country,
            'phone_number' => $request->phone_number
        ];
        $updated = $user->update($data);

        if ($updated) {
         return $this->apiresponse(201, 'data updated successfully', new UserResourse($data));
        }
    }
 */

    public function destroy(string $id)
    {
        try {   $user = User::findOrFail($id);   } 
        catch (ModelNotFoundException $e) {  return $this->apiresponse(404, 'User not found', []);  }       
     
        $user->favorites()->delete(); // Delete all associated favorites
        $deleted = $user->delete();

        if ($deleted) {
          return $this->apiresponse(200, 'data deleted successfully', []);
        }

    }


    public function search(Request $request)
    {

        $word = $request->has('search') ? $request->input('search') : null;
        $user = user::when($word != null, function ($q) use ($word) {
            $q->where('user_name', 'like', '%' . $word . '%');
        })->latest()->get();

        if (count($user) > 0) {
            return $this->apiresponse(200, 'Search completed', UserResourse::collection($user));
        }

        return $this->apiresponse(200, 'No matching data', null);
    }
}
