<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Http\Resources\AdminResourse;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $admins = Admin::get();

        if ($admins) {
         return $this->apiresponse(200, 'data retrived successfully', AdminResourse::collection($admins));
        }
      return $this->apiresponse(404, 'data not found', null);   
     }


    /* public function store(AdminRequest $request)
    {
        $data = $request->validated();
        $admin = Admin::create($data);

        if ($admin) {
            return $this->apiresponse(201, 'data created successfully', new AdminResourse($admin));
        }

        return $this->apiresponse(400, 'data not created', null);
    }
 */

    public function show(string $id)
    {
        $admin = Admin::find($id);

        if ($admin) {
            return $this->apiresponse(200, 'data retrived successfully', new AdminResourse($admin));
        }

        return $this->apiresponse(404, 'data not found', null);
    }


    /* public function update(AdminRequest $request, $id)
    {
         $validator = Validator::make($request->all(), [
            'admin_name' => 'required|max:60',
            'type' => 'required|max:50',
            'image' => 'required',
            'description' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->apiresponse(400, $validator->errors(), null);
        } 

        try {   $admin = Admin::findOrFail($id);   } 
        catch (ModelNotFoundException $e) {  return $this->apiresponse(404, 'Admin not found', []);  }  

        $data = $request->validated();

        $updated = $admin->update($data);

        if ($updated) {
         return $this->apiresponse(201, 'data updated successfully', new AdminResourse($admin));
        }
    }
 */

    public function destroy(string $id)
    {
        try {   $admin = Admin::findOrFail($id);   } 
        catch (ModelNotFoundException $e) {  return $this->apiresponse(404, 'Admin not found', []);  }       
        
        $deleted = $admin->delete();

        if ($deleted) {
          return $this->apiresponse(200, 'data deleted successfully', []);
        }

    }


    public function search(Request $request)
    {

        $word = $request->has('search') ? $request->input('search') : null;
        $admin = admin::when($word != null, function ($q) use ($word) {
            $q->where('admin_name', 'like', '%' . $word . '%');
        })->latest()->get();

        if (count($admin) > 0) {
            return $this->apiresponse(200, 'Search completed', AdminResourse::collection($admin));
        }

        return $this->apiresponse(200, 'No matching data', null);
    }
}
