<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Api\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthAdminController extends Controller
{
    use ApiResponseTrait;


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:60', 'string'],
            'email' => ['required', 'email', 'max:80', 'unique:' . Admin::class,],
            'password' => ['required', 'confirmed', Rules\password::defaults()],
            'admin_key' => ['required', 'integer', 'in:25112002',],
        ]);
        if ($validator->fails()) {
            return $this->apiresponse(422,  'Register Validation Errors',$validator->messages()->all());
        }

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,   //Hash::make($request->password),
        ]);

        $data['token'] = $admin->createToken('adminToken')->plainTextToken;
        $data['name'] = $admin->name;
        $data['email'] = $admin->email;

        return $this->apiresponse(201, $data, 'Admin Registered Successfully');
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
        
        if (Auth::guard('admin')->attempt(['email' =>$request->email ,'password' =>$request->password, ])){
            $admin = Auth::guard('admin')->user();
            $data['token'] = $admin->createToken('adminToken')->plainTextToken;
            $data['name'] = $admin->name;
            $data['email'] = $admin->email;

            return $this->apiresponse(200, $data, 'Admin Logged in Successfully');

        }
        else {
            return $this->apiresponse(401, null, 'Admin Credentials doesn\'t Exist');
        }
    }


    public function logout (Request $request){
        Auth::guard('admin')->logout();
        return $this->apiresponse(200, [], 'Admin Logged out Successfully');

    }
}
