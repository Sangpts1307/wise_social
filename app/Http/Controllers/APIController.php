<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;
class APIController extends Controller
{

    private $apiResponse;

    public function __construct()
    {
        $this->apiResponse = new ApiResponse;
    }
    /**
     * Controller method for api list all user
     * 
     * @return bool|string JSON
     */
    public function listUser()
    {
        $users = User::select('id', 'email', 'address', 'created_at', 'role')
        ->get();
        return $this->apiResponse->success($users);
    }

    public function store(Request $request) 
    {
        $param = $request->all();
        $user = new User();
        $user->status = 1;
        $user->phone = $param['phone'];
        $user->address = $param['address'];
        $user->avatar = $param['avatar'];
        $user->name = $param['name'];
        $user->email = $param['email'];
        $user->password = Hash::make($param['password']);
        $user->role = 0;
        $user->save();
        return $this->apiResponse->success($user);
    }

    public function updatePassword(Request $request)
    {
        $param = $request->all();
        $user = User::find($param['user_id']);
        if (!$user) {
            return $this->apiResponse->dataNotFound();
        }
        $user->password = Hash::make($param['new_password']);
        $user->update();
        return $this->apiResponse->success($user);
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->apiResponse->dataNotFound();
        }
        $user->delete();
        return $this->apiResponse->success(null);
    }

    public function login(Request $request)
    {
        $param = $request->all();
        $crediticals = [
            'email' => $param['email'],
            'password' => $param['password']
        ];
        if (Auth::attempt($crediticals)) {  
            $user = Auth::user();
            $success = $user->createToken($user->id);
            return $this->apiResponse->success($success);
        } else {
            return $this->apiResponse->UnAuthorization();
        }
    }

    public function testView() 
    {
        return $this->apiResponse->success([]);
    }
}
