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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersSectionExport;
use PDF;
use Response;
use Illuminate\Support\Facades\Auth;



class UserAdd_Controller extends Controller
{
    

    public function adduser(){   
        
        if(Auth::user()->userRole==1){
            $results=User::leftjoin('designation','users.desig_id','designation.desig_id')
            ->select('designation.name as desig_name','users.*')
            ->get();
         }
         else{
           
             $results=User::leftjoin('designation','users.desig_id','designation.desig_id')
             ->select('designation.name as desig_name','users.*')
             ->where('users.id',Auth::user()->id)
             ->get();
         }

       
        $designation_data=Designation::select('desig_id','name','org_id')->get();
        $organization_data=Organisation::select('org_id','org_name')->get();
  

         return view('user.add',compact('results','designation_data','organization_data'));

    }


    //register new login
    public function store(Request $request){
       
      
        $upload_directory = "public/uploaded_documents/user/";  
     

        $new_user= new User();

        if($request->hidden_input_purpose=="edit"){
            $new_user = $new_user->find($request->hidden_input_id);          
        }

         // for profile_picture
         if($request->hasFile('profile_picture'))
         {
             if($request->hidden_input_purpose=="edit")
             {
                 if(file_exists($new_user->profile_picture)){
                     unlink($new_user->profile_picture);
                 }
             }
 
             $file = $request->file('profile_picture');
             $profile_picture_tmp = "profile_picture-".time().rand(1000,5000).'.'.strtolower($file->getClientOriginalExtension());
             $file->move($upload_directory, $profile_picture_tmp); //  move file
             $new_user->profile_picture = $upload_directory.$profile_picture_tmp; // assign
         }
         else{
             if($request->hidden_input_purpose=="add"){
                 $new_user->profile_picture="";
             }
             else if($request->hidden_input_purpose=="edit" && $request->profile_picture_delete){
                 $new_user->profile_picture = "";
 
             }
         }

         
        if ($request->profile_picture_delete) {
            if (file_exists($request->profile_picture_delete)) {
                unlink($request->profile_picture_delete);
            }
        }

     
        $new_user->title=$request->title;
        $new_user->first_name=$request->first_name;    
        $new_user->middle_name=$request->middle_name;    
        $new_user->last_name=$request->last_name; 
        $new_user->org_id = 1;
        $new_user->userRole=$request->desig_id;
        $new_user->desig_id=$request->desig_id;
       
        $new_user->email=$request->email;
        $new_user->username=$request->username;
        $new_user->mobile =$request->mobile;
        $new_user->address=$request->address; 
        $new_user->status=$request->status;
        $new_user->created_by = 1;
        $new_user->updated_by = 1;
        
        if($request->hidden_input_purpose=="add"){           
            if($request->password == $request->confirm_password){
                $new_user->password = Hash::make($request->password);
            }
            else{
                session()->put('alert-class','alert-danger');
                session()->put('alert-content','Password did not matched');
                return redirect('user');
            }
        }



        $response="error";
        // duplicate entry
        if(((User::where('username',$request->username)->exists()) || (User::where('email',$request->email)->exists())) && $request->hidden_input_purpose=="add"){
            $message="The combination of this username or email already exist";
           $response="error";
            return ["message"=>$message, "response"=>$response];

           
        }

        // return $request;

        if($new_user->save()){
            $message="User data has been saved successfully !!";
            $response="success";
        }
        return ["message"=>$message, "response"=>$response];
    }

    public function change_password(Request $request)
    {
        
        $new_user= new User();
        $new_user = $new_user->find($request->input_id);   
       
        if($request->input_id)
        {

            $new_password = $request->new_password;
            $confirm_password = $request->confirm_password;
            if($new_password == $confirm_password)
            {
                 $new_user->password = Hash::make($request->new_password);
               
                $new_user->save();
                session()->put('alert-class','alert-success');
                session()->put('alert-content','Password changed successfully');
                return redirect('user');
            }
            else{
                session()->put('alert-class','alert-danger');
                session()->put('alert-content','Password did not matched');
                return redirect('user');
            }

        }
        
    }
   
    public function exportExcelFunctiuonforusers()
    {
        return Excel::download(new UsersSectionExport, 'Usersdata-Sheet.xls');
    }

    public function exportpdfFunctiuonforusers()
    {
        $Usersdata = DB::table('users')->leftjoin('designation','users.desig_id','designation.desig_id')
                            ->select('designation.name as desig_name','users.*')->get();
        date_default_timezone_set('Asia/Kolkata');
        $UsersdateTime = date('d-m-Y H:i A');
        $pdf = PDF::loadView('department/Createpdfs',compact('Usersdata','UsersdateTime'));
        return $pdf->download('Users.pdf');
    }

    public function get_user_details($id){
        $results  = DB::table('users')
        ->leftjoin('organisation', 'organisation.org_id', '=', 'users.org_id')
        ->leftjoin('designation', 'designation.desig_id', '=', 'users.desig_id')
        ->where('id', $id)->select('users.*','organisation.org_name','designation.name')->first();

  
      if ($results) {
        return Response::json($results);
      }

    }


}
