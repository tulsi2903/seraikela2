<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\User;
use Hash;
use App\Designation;
use App\Organisation;
use DB;
use Session;
use App\CheakLogout;


class UserAdd_Controller extends Controller
{
    
    public function adduser(){     
        $results=DB::table('users')->leftjoin('designation','users.userRole','designation.desig_id')
                    	->select('designation.name as designame','users.first_name as first_name','users.last_name as last_name',
                            	'users.email as email_id','users.mobile_number as mobile_number','users.is_active as is_active','users.name as username','users.address as address')->get();
        $designation_data=Designation::select('desig_id','name','org_id')->get();
        $organization_data=Organisation::select('org_id','org_name')->get();

         return view('user.add',compact('results','designation_data','organization_data'));

    }

    //register new login
    public function store(Request $request){      
        $new_user= new User();
        $new_user->title=$request->title;
        $new_user->first_name=$request->first_name;    
        $new_user->last_name=$request->last_name;
        $new_user->suffix=$request->suffix;      
        $new_user->is_active=$request->is_active;
        $new_user->start_date=$request->start_date;    
        $new_user->end_date=$request->end_date;   
        $new_user->name=$request->user;
        $new_user->userRole=$request->desig_id;
        $new_user->org_id=$request->org_id;
        
        if($request->password == $request->confirm_pass){
            // session()->put('alert-class','alert-danger');
            // session()->put('alert-content','password not correct');
             $new_user->password = Hash::make($request->password);
        }
        else{
            // session()->put('alert-class','alert-danger');
            // session()->put('alert-content','password not correct');
        }
        $new_user->mobile_number =$request->mobile;   
        $new_user->email=$request->email;     
        $new_user->address=$request->address;
        $new_user->save();
        $check_logout = new CheakLogout();
        $check_logout->user_id = $new_user->id;
        $check_logout->save();
      return redirect('user');
    }
}
