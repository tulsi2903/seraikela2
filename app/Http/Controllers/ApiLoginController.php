<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Validator;
use DB;
use Hash;
use App\User;
use Str;
use Exception;
class ApiLoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return 'Validation Error.';       
        }
        // $tokenResult = $user->createToken('Personal Access Token');
        $accessToken=md5(uniqid(rand()));
        $username =$request->username;
        $password =Hash::make($request->username);
        $getUserDetails  = DB::table('users')->where('username', $username)->where('password', $password)->where('status',"1")->first();
        $success="";
        if ($getUserDetails) {
            $success=$getUserDetails ;
            return 'User Login successfully.';
        }
        else
        {
            return  'Login Unsuccessful';
        }


    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        
        $this->appupdate();
        return response()->json([
        'message' => 'Successfully logged out'
    ]);
    }

    public function appupdate(Request $request)
    {
        $token = Str::random(60);

        $request->user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return ['token' => $token];
    }
}

