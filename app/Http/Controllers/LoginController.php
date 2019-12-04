<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Users;
use Hash;
use App\Designation;
use App\Organisation;
use DB;
use Session;



class LoginController extends Controller
{
    public function login(Request $request){
        $this->validate($request,[
            'login' => 'required',
            'password' => 'required'
        ]);
        
        $user_data = Users::where('username',$request->login)->first();
        if(!empty($user_data) && Hash::check($request->password,$user_data->password))
        {
          return redirect('/homepage');
        }
        else{


            return redirect('/');
            
           
        }
       
        // $results=Users::select('password')->first();
       // return $results['password'];
   
            // if($getUserDetails)
            // {
            //     $email_assign    =$getUserDetails->email;
            //     $password_assign =$getUserDetails->password;
            //     $user_type=$getUserDetails->user_type;
            //     $user_id=$getUserDetails->user_id;
       
            //     if ($email_id==$email_assign && $password==$password_assign)
            //     {       
            //         $session_data = array(
            //         'id'   =>$getUserDetails->ID,
            //         'email'=>$getUserDetails->email,
            //         'full_name'=>$getUserDetails->full_name,
            //         'user_id'=>$getUserDetails->user_id,
            //         'type' =>$getUserDetails->user_type,
            //         'org_ID'=>$getUserDetails->org_ID
            //         );
    
            //         // session create
            //         Session::put($session_data);
            //         if($user_type=='seeker')
            //         {
            //             return redirect('indexjobseeker');
            //         }
            //         elseif($user_type=='teammember')
            //         {
            //             $toReturn['team_member']=tbl_team_member::where('ID',$user_id)->get();
            //             // return $toReturn;
            //             $toReturn['user_permission']=Tbl_team_member_permission::where('team_member_id',$user_id)
            //             ->leftjoin('tbl_module','tbl_team_member_permission.permission_value','=','tbl_module.module_id')
            //             ->get()->toArray();
    
            //             $session_data = array(
            //                 'id'   =>$getUserDetails->ID,
            //                 'email'=>$getUserDetails->email,
            //                 'full_name'=>$getUserDetails->full_name,
            //                 'user_id'=>$getUserDetails->user_id,
            //                 'type' =>$getUserDetails->user_type,
            //                 'org_ID'=>$getUserDetails->org_ID,
            //                 'user_permission'=>$toReturn['user_permission']
            //             );
            //             Session::put($session_data);
            //             // return $session_data;
            //             return redirect('employer/dashboard');
            //         }
            //         else
            //         {
            //             return redirect('employer/dashboard');
            //         }        
            //     }
            //     else{
            //         $error="NOT LOGIN !!!!";
            //         return view('employee_admin')->with('error',$error);
            //     }
            
            // }
            // else{
            //         $error="Email Id Not Found !!!!";
            //     return view('employee_admin')->with('error',$error);
            // }
        }       

    public function index(){

        $results=DB::table('user')->leftjoin('designation','user.user_type','designation.desig_id')
                    ->select('designation.name as designame','user.first_name as first_name','user.last_name as last_name',
                            'user.email as email_id','user.mobile_number as mobile_number','user.is_active as is_active','user.username as username','user.address as address')->get();
        $designation_data=Designation::select('desig_id','name','org_id')->get();
        $organization_data=Organisation::select('org_id','org_name')->get();

        return view('user.add',compact('results','designation_data','organization_data'));

    }


    public function store(Request $request){      
        $new_user= new Users();
        $new_user->title=$request->title;
        $new_user->first_name=$request->first_name;    
        $new_user->last_name=$request->last_name;   
        $new_user->suffix=$request->suffix;      
        $new_user->is_active=$request->is_active;
        $new_user->start_date=$request->start_date;    
        $new_user->end_date=$request->end_date;   
        $new_user->username=$request->user;
        $new_user->user_type=$request->desig_id;
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
      return redirect('user');
    }
}
