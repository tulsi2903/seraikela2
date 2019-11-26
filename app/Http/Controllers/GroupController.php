<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Group;
use Mail;



class GroupController extends Controller
{
    public function index()
    {
        $results = Group::orderBy('scheme_group_id','desc')->get();
        // print_r($results);
           
       return view('scheme-group.index')->with('results', $results);
    }

    public function add(Request $request)
    {
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";
    
        $results = new Group;
        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $results = $results->find($request->id);
        }
        return view('scheme-group.add',compact('hidden_input_purpose','hidden_input_id','results'))->with('results',$results);
    }

    public function store(Request $request)
    {
        // return $request;
        // die;
        $group = new Group;

        if($request->hidden_input_purpose=="edit")
        {
            $group = $group->find($request->hidden_input_id);
        }

        $group->scheme_group_name= $request->group_name;
        $group->is_active = $request->is_active;
        $group->created_by = '1';
        $group->updated_by = '1';

        if(Group::where('scheme_group_name',$request->group_name)->first() && $request->hidden_input_purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This Group '.$request->group_name.' is already exist');
        }
        else if($group->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','New Group details has been added');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details');
        }

        return redirect('scheme-group');
    }

    public function delete(Request $request){
        if(Group::find($request->id)){
            Group::where('scheme_group_id',$request->id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully');
        }
        return redirect('scheme-group');
    }


    public function sendEmail(Request $request)
    {
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $send_message=$request->message;
            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message);
            // return $user;
            Mail::send('mail.emails',['user'=> $user], function($message) use ($user)
            {
                $email_to=explode(',',$user['email_to']);
                foreach($email_to as $key=>$value)
                {
                $message->to($email_to[$key]);
                }

                if(@$user['cc'])
                {
                $email_cc=explode(',',$user['cc']);
                foreach($email_cc as $key=>$value)
                {
                    $message->cc($email_cc[$key]);
                }
                }
                
                $message->subject($user['subject']);
                $message->from('rohit18212@gmail.com','seraikela'); 
                session()->put('alert-class','alert-success');
                session()->put('alert-content','Email send');
            });
            return redirect('scheme-group');
    
    }
 
}
