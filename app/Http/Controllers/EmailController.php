<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
use PDF;
use DB;




class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
 
        if($request->department=="department")
        {
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details= DB::table('department')->join('organisation','department.org_id','=','organisation.id')->select('department.*','organisation.org_name')->get();
                   
            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$send_subject,'results'=>$details);
            $pdf = PDF::loadView('mail.departs',['user'=>$user]);  
            //$data =$pdf; 
            // echo $data;
            // die;

            Mail::send('mail.departs',['user'=> $user], function($message) use ($user,$pdf)
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
            
                $message->attachData($pdf->output(), "department.pdf");
                $message->subject($user['subject']);
                $message->from('rohit18212@gmail.com','seraikela'); 
                session()->put('alert-class','alert-success');
                session()->put('alert-content','Email send');
            });
            return redirect('department');

        }
        elseif($request->designation=="designation")
        {
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);


            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$send_subject,'results'=>$details);
            $pdf = PDF::loadView('mail.designation',['user'=>$user]);

            // $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
            Mail::send('mail.designation',['user'=> $user], function($message) use ($user,$pdf)
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
                $message->attachData($pdf->output(), "designation.pdf");
                $message->subject($user['subject']);
                $message->from('rohit18212@gmail.com','seraikela'); 
                session()->put('alert-class','alert-success');
                session()->put('alert-content','Email send');
            });
            return redirect('designation');
        }
        elseif($request->asset=="asset"){
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
            Mail::send('mail.asset',['user'=> $user], function($message) use ($user)
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
            return redirect('asset');
        }
        elseif($request->scheme_type=="scheme_type"){
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
            Mail::send('mail.scheme_type',['user'=> $user], function($message) use ($user)
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
            return redirect('scheme_type');
        }
        elseif($request->asset_numbers=="asset_numbers"){
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
            Mail::send('mail.asset_numbers',['user'=> $user], function($message) use ($user)
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
            return redirect('asset_numbers');
        }
        elseif($request->geo_structure=="geo_structure"){
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
            Mail::send('mail.geo-structure',['user'=> $user], function($message) use ($user)
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
            return redirect('geo-structure');
        }
        elseif($request->scheme_indicator=="scheme_indicator"){

            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
             Mail::send('mail.scheme-indicator',['user'=> $user], function($message) use ($user)
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
              return redirect('scheme-indicator');
        }
        elseif($request->scheme_structure=="scheme_structure"){

            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
             Mail::send('mail.scheme-structure',['user'=> $user], function($message) use ($user)
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
              return redirect('scheme-structure');
        }
        elseif($request->group=="group"){
            
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->result);

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
            
            // return view('mail.groups')->with('user',$user);       
            // print_r($user['results']);
            // die;
            
            Mail::send('mail.groups',['user'=> $user], function($message) use ($user)
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
        elseif($request->geo_target=="geo_target"){
            
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
            
            Mail::send('mail.scheme-geo-target',['user'=> $user], function($message) use ($user)
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
              return redirect('scheme-geo-target');
        }
    }
    // public function mail(){
    //     return view('index1');
    // }
        public function sendmail(Request $request){
            
            ini_set('memory_limit', '-1');
            $data["email"]=$request->get("email");
            $data["client_name"]=$request->get("client_name");
            $data["subject"]=$request->get("subject");
                // print_r($data);
                // die;

            $pdf = PDF::loadView('mail.test',['data'=>$data] );
            try{
                Mail::send('mail.test', $data, function($message)use($data,$pdf) {
                $message->to($data["email"], $data["client_name"])
                ->subject($data["subject"])
                ->attachData($pdf->output(), "invoice.pdf");
                });
            }catch(JWTException $exception){
                $this->serverstatuscode = "0";
                $this->serverstatusdes = $exception->getMessage();
            }
            if (Mail::failures()) {
                 $this->statusdesc  =   "Error sending mail";
                 $this->statuscode  =   "0";
    
            }else{
    
               $this->statusdesc  =   "Message sent Succesfully";
               $this->statuscode  =   "1";
            }
            return response()->json(compact('this'));
        }
        
}