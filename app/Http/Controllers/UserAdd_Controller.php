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


class UserAdd_Controller extends Controller
{
    
    public function adduser(){     
        $results=DB::table('users')->leftjoin('designation','users.desig_id','designation.desig_id')
                    	->select('designation.name as desig_name','users.*')->get();
        $designation_data=Designation::select('desig_id','name','org_id')->get();
        $organization_data=Organisation::select('org_id','org_name')->get();

         return view('user.add',compact('results','designation_data','organization_data'));

    }

    //register new login
    public function store(Request $request){  
        $new_user= new User();

        $new_user->title=$request->title;
        $new_user->first_name=$request->first_name;    
        $new_user->middle_name=$request->middle_name;    
        $new_user->last_name=$request->last_name; 

        $new_user->org_id = $request->org_id;
        $new_user->userRole=$request->desig_id;
        $new_user->desig_id=$request->desig_id;
        $new_user->start_date=$request->start_date;    
        $new_user->end_date=$request->end_date;

        $new_user->email=$request->email;
        $new_user->username=$request->username;

        $new_user->mobile =$request->mobile;
        $new_user->address=$request->address; 
        $new_user->status=$request->status;
        
        if($request->password == $request->confirm_password){
             $new_user->password = Hash::make($request->password);
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Password did not matched');
            return redirect('user');
        }

        // duplicate entry
        if(User::where('email',$request->email)->exists()||User::where('username',$request->username)->exists()){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This email '.$request->email.' OR username '.$request->username.'is already exists!');
            return redirect('user');
        }
        if($new_user->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','New user data has been saved');
        }
        return redirect('user');
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
}
