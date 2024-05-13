<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthUserController extends Controller
{
    use ApiResponseTrait;


    public function register(Request $request)
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
            return $this->apiresponse(422,  'Register Validation Errors',$validator->messages()->all());
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

        return $this->apiresponse(201, $data, 'User Registered Successfully');
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:80'],
            'password' => ['required'],
           
        ]);
        if ($validator->fails()) {
            return $this->apiresponse(422, $validator->messages()->all(), 'login Validation Errors');
        }

        if (Auth::attempt(['email' =>$request->email ,'password' =>$request->password, ])){
            $user = Auth::user();
            $data['token'] = $user->createToken('userToken')->plainTextToken;
            $data['name'] = $user->name;
            $data['email'] = $user->email;

            return $this->apiresponse(200, $data, 'User Logged in Successfully');

        }
        else {
            return $this->apiresponse(401, null, 'User Credentials doesn\'t Exist');
        }
    }


    public function logout (Request $request){
        $request->user()->currentAccessToken()->delete();
        return $this->apiresponse(200, [], 'User Logged out Successfully');

    }
}
