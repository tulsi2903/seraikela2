<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;

    //
    public function login(){ 
        if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){ 
            $user = Auth::user();
            if(Auth::user()->status == '1'){
                $success['token'] =  $user->createToken('MyApp')-> accessToken; 
                return response()->json(['success' => $success], $this->successStatus); 
            }
            else{
                return response()->json(['error'=>'inactive_user'], 401);  
            }
        }
        else{ 
            return response()->json(['error'=>'unauthorised'], 401); 
        }
    }

    public function logout(Request $request)
    {
        if(Auth::user()){
            Auth::user()->token()->revoke();
            return response()->json(['success' =>'logout_success'], $this->successStatus);
        }
        else{
            return response()->json(['error' =>'something went wrong'], 500);
        }
    }

    public function details() 
    { 
        // $id = Auth::user()->id;
        // $user = User::select('id','userRole','title','first_name','middle_name','last_name','email')->where('id', $id)->first();
        $user = Auth::user();
        return response()->json(['success' => $user]); 
    } 
}
