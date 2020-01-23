<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
use PDF;
use DB;
use App\GeoStructure;




class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {       
        // return $request;
  
        if($request->department=="department")
        {
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            
            $details= DB::table('department')->join('organisation','department.org_id','=','organisation.org_id')->select('department.*','organisation.org_name');
            if($request->search_query!="")
            {
              $details = $details->where('department.dept_name', 'LIKE', "%{$request->search_query}%")->get();
            }
            else
            {
                $details=$details->get();
            }
            // return $details;
            // exit;
            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$send_subject,'results'=>$details);
            $pdf = PDF::loadView('mail.departs',['user'=>$user]);  
            //$data =$pdf; 
            // echo $data;
            // die;

            Mail::send('mail.departs',['user'=> $user], function($message) use ($user)
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
            
                // $message->attachData($pdf->output(), "department.pdf");
                $message->subject($user['subject']);
                $message->from('dsrm.skla@gmail.com','DSRM Mailer'); 
                session()->put('alert-class','alert-success');
                session()->put('alert-content','Email send');
            });
            // return view('mail.departs')->with('user',$user);
            return redirect('department');

        }
        elseif($request->designation=="designation")
        {
          
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            // return $request->search_query;
            $details_designation= DB::table('designation')->join('organisation','designation.org_id','=','organisation.org_id')->select('designation.*','organisation.org_name');
            if($request->search_query!="")
            {
              $details_designation = $details_designation->where('designation.name', 'LIKE', "%{$request->search_query}%")->get();
            }
            else
            {
                $details_designation=$details_designation->get();
            }
            // return $details_designation;
            // exit;

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$send_subject,'results'=>$details_designation);
            $pdf = PDF::loadView('mail.designation',['user'=>$user]);

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details_designation);
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
                // $message->attachData($pdf->output(), "designation.pdf");
                $message->subject($user['subject']);
                $message->from('dsrm.skla@gmail.com','DSRM Mailer'); 
                session()->put('alert-class','alert-success');
                session()->put('alert-content','Email send');
            });
            // return view('mail.designation')->with('user',$user);
            return redirect('designation');
        }
       
        elseif($request->scheme_type=="scheme_type"){
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            //   return $request->search_query;
              $details_scheme_type= DB::table('scheme_type');
              if($request->search_query!="")
              {
                $details_scheme_type = $details_scheme_type->where('scheme_type.sch_type_name', 'LIKE', "%{$request->search_query}%")->get();
              }
              else
              {
                  $details_scheme_type=$details_scheme_type->get();
              }
              // return $details_designation;
              // exit;

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details_scheme_type);
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
                $message->from('dsrm.skla@gmail.com','DSRM Mailer'); 
                session()->put('alert-class','alert-success');
                session()->put('alert-content','Email send');
            });
            // return view('mail.scheme_type')->with('user',$user);
            return redirect('scheme_type');
        }
        elseif($request->asset_numbers=="asset_numbers"){
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

             //   return $request->search_query;
             $details_scheme_type= DB::table('asset_numbers')->join('year','asset_numbers.year','=','year.year_id')->join('asset','asset_numbers.asset_id','=','asset.asset_id')->join('geo_structure','asset_numbers.geo_id','=','geo_structure.geo_id')
                                ->select('asset_numbers.*','year.year_value','asset.asset_name','geo_structure.geo_name');
             if($request->search_query!="")
             {
               $details_scheme_type = $details_scheme_type->where('asset_numbers_id', 'LIKE', "%{$request->search_query}%")->get();
             }
             else
             {
                 $details_scheme_type=$details_scheme_type->get();
             }
             foreach($details_scheme_type as $value){
                $value->block_name = GeoStructure::find(GeoStructure::find($value->geo_id)->bl_id)->geo_name;
             }
             // return $details_designation;
             // exit;
            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details_scheme_type);
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
                $message->from('dsrm.skla@gmail.com','DSRM Mailer'); 
                session()->put('alert-class','alert-success');
                session()->put('alert-content','Email send');
            });
            return redirect('asset_numbers');
            // return view('mail.asset_numbers')->with('user',$user);
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
                $message->from('dsrm.skla@gmail.com','DSRM Mailer'); 
                session()->put('alert-class','alert-success');
                session()->put('alert-content','Email send');
            });
            return redirect('geo-structure');                                     // lol
            // return view('mail.geo-structure')->with('user',$user);

        }
        elseif($request->scheme_indicator=="scheme_indicator"){

            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
            //  Mail::send('mail.scheme-indicator',['user'=> $user], function($message) use ($user)
            //  {
            //      $email_to=explode(',',$user['email_to']);
            //      foreach($email_to as $key=>$value)
            //     {
            //         $message->to($email_to[$key]);
            //     }

            //     if(@$user['cc'])
            //     {
            //      $email_cc=explode(',',$user['cc']);
            //         foreach($email_cc as $key=>$value)
            //         {
            //             $message->cc($email_cc[$key]);
            //         }
            //     }
            //     $message->subject($user['subject']);
            //     $message->from('dsrm.skla@gmail.com','seraikela'); 
            //      session()->put('alert-class','alert-success');
            //      session()->put('alert-content','Email send');
            //  });
            //   return redirect('scheme-indicator');                                //lol
            return view('mail.scheme-indicator')->with('user',$user);
            
        }
        elseif($request->scheme_structure=="scheme_structure"){
            ;
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);
            //    return $request->scheme_structure;


             //   return $request->search_query;
             $details_scheme_structure= DB::table('scheme_structure')->join('department','scheme_structure.dept_id','=','department.dept_id')->select('scheme_structure.*','department.dept_name');
             if($request->search_query!="")
             {
               $details_scheme_structure = $details_scheme_structure->where('scheme_structure.scheme_name', 'LIKE', "%{$request->search_query}%")->get();
             return $details_scheme_structure;

             }
             else
             {
                 $details_scheme_structure=$details_scheme_structure->get();
             }
            //  return $details_scheme_structure;
             // exit;




            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details_scheme_structure);
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
                $message->from('dsrm.skla@gmail.com','DSRM Mailer'); 
                 session()->put('alert-class','alert-success');
                 session()->put('alert-content','Email send');
             });
            //     return $user;
            //  return view('mail.scheme-structure')->with('user',$user);
            
              return redirect('scheme-structure');
        }
        elseif($request->scheme_asset=="scheme_asset"){
           
          
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);
      

             $details_scheme_assets= DB::table('scheme_assets');
             if($request->search_query!="")
             {
               $details_scheme_assets = $details_scheme_assets->where('scheme_asset_name', 'LIKE', "%{$request->search_query}%")->get();
             }
             else
             {
                 $details_scheme_assets=$details_scheme_assets->get();
             }
         

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details_scheme_assets);
             Mail::send('mail.scheme-asset',['user'=> $user], function($message) use ($user)
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
                $message->from('dsrm.skla@gmail.com','DSRM Mailer'); 
                 session()->put('alert-class','alert-success');
                 session()->put('alert-content','Email send');
             });
          
            //  return view('mail.scheme-asset')->with('user',$user);
            
              return redirect('scheme-asset');
        }
        elseif($request->group=="group"){
            
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->result);

            //    return $request->search_query;
             $details_scheme_group= DB::table('scheme_group');
             if($request->search_query!="")
             {
               $details_scheme_group = $details_scheme_group->where('scheme_group.scheme_group_name', 'LIKE', "%{$request->search_query}%")->get();
             }
             else
             {
                 $details_scheme_group=$details_scheme_group->get();
             }
             // return $details_designation;
             // exit;


            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details_scheme_group);
            
            // return view('mail.groups')->with('user',$user);       
            // print_r($user['results']);
            // die;
            
            // Mail::send('mail.groups',['user'=> $user], function($message) use ($user)
            //  {
            //      $email_to=explode(',',$user['email_to']);
            //      foreach($email_to as $key=>$value)
            //     {
            //         $message->to($email_to[$key]);
            //     }

            //     if(@$user['cc'])
            //     {
            //      $email_cc=explode(',',$user['cc']);
            //         foreach($email_cc as $key=>$value)
            //         {
            //             $message->cc($email_cc[$key]);
            //         }
            //     }
            //     $message->subject($user['subject']);
            //     $message->from('dsrm.skla@gmail.com','seraikela'); 
            //      session()->put('alert-class','alert-success');
            //      session()->put('alert-content','Email send');
            //  });
            return view('mail.groups')->with('user',$user);
            //   return redirect('scheme-group');
        }
        elseif($request->geo_target=="geo_target"){
            
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
            
            // Mail::send('mail.scheme-geo-target',['user'=> $user], function($message) use ($user)
            //  {
            //      $email_to=explode(',',$user['email_to']);
            //      foreach($email_to as $key=>$value)
            //     {
            //         $message->to($email_to[$key]);
            //     }

            //     if(@$user['cc'])
            //     {
            //      $email_cc=explode(',',$user['cc']);
            //         foreach($email_cc as $key=>$value)
            //         {
            //             $message->cc($email_cc[$key]);
            //         }
            //     }
            //     $message->subject($user['subject']);
            //     $message->from('dsrm.skla@gmail.com','seraikela'); 
            //      session()->put('alert-class','alert-success');
            //      session()->put('alert-content','Email send');
            //  });
            //   return redirect('scheme-geo-target');                      // lol
            return view('mail.scheme-geo-target')->with('user',$user);

        }

        elseif($request->year=="year"){
            
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            //    return $request->search_query;
            $details_year= DB::table('year');
            if($request->search_query!="")
            {
              $details_year = $details_year->where('year.year_value', 'LIKE', "%{$request->search_query}%")->get();
            }
            else
            {
                $details_year=$details_year->get();
            }
            // return $details_designation;
            // exit;

            
            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details_year);
            
           
            Mail::send('mail.year',['user'=> $user], function($message) use ($user)
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
                $message->from('dsrm.skla@gmail.com','DSRM Mailer'); 
                 session()->put('alert-class','alert-success');
                 session()->put('alert-content','Email send');
             });
            // return view('mail.year')->with('user',$user);
              return redirect('year');
        }

        elseif($request->module=="module"){
            
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

             //    return $request->search_query;
             $details_module= DB::table('module');
             if($request->search_query!="")
             {
               $details_module = $details_module->where('module.mod_name', 'LIKE', "%{$request->search_query}%")->get();
             }
             else
             {
                 $details_module=$details_module->get();
             }
             // return $details_designation;
             // exit;


            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details_module);
            
            Mail::send('mail.module',['user'=> $user], function($message) use ($user)
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
                $message->from('dsrm.skla@gmail.com','DSRM Mailer'); 
                 session()->put('alert-class','alert-success');
                 session()->put('alert-content','Email send');
             });
            // return view('mail.module')->with('user',$user);
              return redirect('module');
        }

        elseif($request->uom=="uom"){
            
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
            
            // Mail::send('mail.uom',['user'=> $user], function($message) use ($user)
            //  {
            //      $email_to=explode(',',$user['email_to']);
            //      foreach($email_to as $key=>$value)
            //     {
            //         $message->to($email_to[$key]);
            //     }

            //     if(@$user['cc'])
            //     {
            //      $email_cc=explode(',',$user['cc']);
            //         foreach($email_cc as $key=>$value)
            //         {
            //             $message->cc($email_cc[$key]);
            //         }
            //     }
            //     $message->subject($user['subject']);
            //     $message->from('dsrm.skla@gmail.com','seraikela'); 
            //      session()->put('alert-class','alert-success');
            //      session()->put('alert-content','Email send');
            //  });
            //   return redirect('uom');                            // lol
            return view('mail.uom')->with('user',$user);

        }

        elseif($request->sub_category=="sub_category"){
            
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            
                // return $request->search_query;
             $details_asset_subcat= DB::table('asset_subcat');
             if($request->search_query!="")
             {
               $details_asset_subcat = $details_asset_subcat->where('asset_subcat.asset_sub_cat_name', 'LIKE', "%{$request->search_query}%")->get();
             }
             else
             {
                 $details_asset_subcat=$details_asset_subcat->get();
             }
             // return $details_designation;
             // exit;


            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details_asset_subcat);
            
            Mail::send('mail.sub_category',['user'=> $user], function($message) use ($user)
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
                $message->from('dsrm.skla@gmail.com','DSRM Mailer'); 
                 session()->put('alert-class','alert-success');
                 session()->put('alert-content','Email send');
             });
            return redirect('asset_subcat');
            // return view('mail.sub_category')->with('user',$user);
        }

        elseif($request->category=="category"){
            
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            //    return $request->search_query;
             $details_asset_cat= DB::table('asset_cat');
             if($request->search_query!="")
             {
               $details_asset_cat = $details_asset_cat->where('asset_cat.asset_cat_name', 'LIKE', "%{$request->search_query}%")->get();
             }
             else
             {
                 $details_asset_cat=$details_asset_cat->get();
             }
             // return $details_designation;
             // exit;

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details_asset_cat);
            
            Mail::send('mail.category',['user'=> $user], function($message) use ($user)
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
                $message->from('dsrm.skla@gmail.com','DSRM Mailer'); 
                 session()->put('alert-class','alert-success');
                 session()->put('alert-content','Email send');
             });
            return redirect('assetcat');
            // return view('mail.category')->with('user',$user);
        }

        elseif($request->asset=="asset"){
            
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

             //   return $request->search_query;
             $details_asset= DB::table('asset')->join('department','asset.asset_id','=','department.dept_id')->select('asset.*','department.dept_name');
             if($request->search_query!="")
             {
               $details_asset = $details_asset->where('asset.asset_name', 'LIKE', "%{$request->search_query}%")->get();
            //  return $details_scheme_structure;

             }
             else
             {
                 $details_asset=$details_asset->get();
             }
            //  return $details_scheme_structure;
             // exit;

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details_asset);
            
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
                $message->from('dsrm.skla@gmail.com','DSRM Mailer'); 
                 session()->put('alert-class','alert-success');
                 session()->put('alert-content','Email send');
             });
              return redirect('asset');
            //  return view('mail.asset')->with('user',$user);

        }

        elseif($request->mgnrega=="mgnrega"){
                        
            $email_from=$request->from;
            $email_to=$request->to;
            $email_cc=$request->cc;
            $send_subject=$request->subject;
            $details=json_decode($request->data);

            $user = array('email_from'=>$email_from,'email_to'=>$email_to, 'cc'=>$email_cc, 'subject'=>$request->subject, 'content'=>$request->message,'results'=>$details);
            
            Mail::send('mail.mgnrega',['user'=> $user], function($message) use ($user)
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
                $message->from('dsrm.skla@gmail.com','DSRM Mailer'); 
                 session()->put('alert-class','alert-success');
                 session()->put('alert-content','Email send');
             });
              return redirect('mgnrega');

            
          
        }
    }


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