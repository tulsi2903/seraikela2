<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
use PDF;


class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        ini_set('memory_limit', '-1');
        if($request->department=="department")
        {
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);
            // print_r($details);
            // exit;
           
            
            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
            $pdf = PDF::loadView('mail.departs', $user);
            
    //         $pdfPath = BUDGETS_DIR . '/' . $outputName;
    //  $invoice->pdf_url = $outputName;
    //  $invoice->update();
    //  // File::put($pdfPath, PDF::loadView($pdf, 'A4', 'portrait')->output());
    //  $pdf = PDF::loadView('invoicing.invoicepdf', $input)->save($pdfPath);
           // echo "<pre>";
            // print_r($pdf->stream('mail.departs',$user));
            // print_r($user);
            // exit;
            // return view('mail.departs')->with('user',$user);

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
                $message->attachData($pdf, "invoice.pdf");
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

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
            Mail::send('mail.designation',['user'=> $user], function($message) use ($user)
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
              return redirect('group');
        }
        
    }


}
