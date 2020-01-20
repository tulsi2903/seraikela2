<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Group;
use Mail;
use Auth;



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
    public function scheme_group_excel_function()        
    {
            $data = array(1 => array("Scheme Group Sheet"));
            $data[] = array('Sl. No.','Group','Is Active','Date');
    
            $items = Group::orderBy('scheme_group_id','desc')->Select('scheme_group_name','is_active','created_at as createdDate')->get(); 
            foreach ($items as $key => $value) {
                if ($value->is_active == 1) {
                    $value->is_active = "Active";
                } else {
                    $value->is_active = "Inactive";
                }       
                $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
                $data[] = array(
                    $key + 1,
                    $value->scheme_group_name,
                    $value->is_active,
                    $value->createdDate,
                );
            }
            \Excel::create('SchemeGroup-Sheet', function ($excel) use ($data) {
    
                // Set the title
                $excel->setTitle('Scheme Group Sheet');
    
                // Chain the setters
                $excel->setCreator('Seraikela')->setCompany('Seraikela');
    
                $excel->sheet('Fees', function ($sheet) use ($data) {
                    $sheet->freezePane('A3');
                    $sheet->mergeCells('A1:I1');
                    $sheet->fromArray($data, null, 'A1', true, false);
                    $sheet->setColumnFormat(array('I1' => '@'));
                });
            })->download('xls');
        }
    public function scheme_group_pdf_function(){
        $scheme_group = Group::orderBy('scheme_group_id','desc')->Select('scheme_group_name','is_active','created_at as createdDate')->get();
        
        foreach ($scheme_group as $key => $value) {
            $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
            if ($value->is_active == 1) {
                $value->is_active = "Active";
            } else {
                $value->is_active = "Inactive";
            }
        }

        $doc_details = array(
            "title" => "Scheme Group",
            "author" => 'IT-Scient',
            "topMarginValue" => 10,
            "mode" => 'P'
        );

        date_default_timezone_set('Asia/Kolkata');
        $currentDateTime = date('d-m-Y H:i:s'); 
        $user_name=Auth::user()->first_name;
        $user_last_name=Auth::user()->last_name;
        $pdfbuilder = new \PdfBuilder($doc_details);

        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
        $content .= "<th style='border: solid 1px #000000;' colspan=\"4\" align=\"left\" ><b>Scheme Group</b></th></tr>";
        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Scheme Group
        "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
        "</b>"."</p>";

        /* ========================================================================= */
        /*                Total width of the pdf table is 1017px                     */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 459px;\" align=\"center\"><b>Group</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 110px;\" align=\"center\"><b>Status</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 90px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";
        $content .= "<tbody>";
        foreach ($scheme_group as $key => $row) {
            $index = $key+1;
            $content .= "<tr>";
            $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 459px;\" align=\"left\">" . $row->scheme_group_name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 110px;\" align=\"left\">" . $row->is_active . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 90px;\" align=\"right\">" . $row->createdDate. "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('SchemeGroup.pdf');
        exit;
    }

       // abhishek 
       public function view_diffrent_formate(Request $request)
       {
        //    return "dsfs";
           $department=array();
           if($request->print=="print_pdf")
           {
   
               if($request->scheme_group_id!="")
               {
   
                $scheme_group = Group::whereIn('scheme_group_id',$request->scheme_group_id)->orderBy('scheme_group_id','desc')->Select('scheme_group_name','is_active','created_at as createdDate')->get();
        
                foreach ($scheme_group as $key => $value) {
                    $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
                    if ($value->is_active == 1) {
                        $value->is_active = "Active";
                    } else {
                        $value->is_active = "Inactive";
                    }
                }
        
                $doc_details = array(
                    "title" => "Scheme Group",
                    "author" => 'IT-Scient',
                    "topMarginValue" => 10,
                    "mode" => 'P'
                );
        
                date_default_timezone_set('Asia/Kolkata');
                $currentDateTime = date('d-m-Y H:i:s'); 
                $user_name=Auth::user()->first_name;
                $user_last_name=Auth::user()->last_name;
                $pdfbuilder = new \PdfBuilder($doc_details);
        
                $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
                $content .= "<th style='border: solid 1px #000000;' colspan=\"4\" align=\"left\" ><b>Scheme Group</b></th></tr>";
                $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Scheme Group
                "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
                "</b>"."</p>";
        
                /* ========================================================================= */
                /*                Total width of the pdf table is 1017px                     */
                /* ========================================================================= */
                $content .= "<thead>";
                $content .= "<tr>";
                $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
                $content .= "<th style=\"border: solid 1px #000000;width: 459px;\" align=\"center\"><b>Group</b></th>";
                $content .= "<th style=\"border: solid 1px #000000;width: 110px;\" align=\"center\"><b>Status</b></th>";
                $content .= "<th style=\"border: solid 1px #000000;width: 90px;\" align=\"center\"><b>Date</b></th>";
                $content .= "</tr>";
                $content .= "</thead>";
                $content .= "<tbody>";
                foreach ($scheme_group as $key => $row) {
                    $index = $key+1;
                    $content .= "<tr>";
                    $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
                    $content .= "<td style=\"border: solid 1px #000000;width: 459px;\" align=\"left\">" . $row->scheme_group_name . "</td>";
                    $content .= "<td style=\"border: solid 1px #000000;width: 110px;\" align=\"left\">" . $row->is_active . "</td>";
                    $content .= "<td style=\"border: solid 1px #000000;width: 90px;\" align=\"right\">" . $row->createdDate. "</td>";
                    $content .= "</tr>";
                }
                $content .= "</tbody></table>";
                $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
                $pdfbuilder->output('SchemeGroup.pdf');
                exit;
                    
 
               }
            //    return $request;
           }
           elseif($request->print=="excel_sheet")
           {
   
               if($request->scheme_group_id!="")
               {
   
                
                $data = array(1 => array("Scheme Group Sheet"));
                $data[] = array('Sl. No.','Group','Is Active','Date');
        
                $items = Group::whereIn('scheme_group_id',$request->scheme_group_id)->orderBy('scheme_group_id','desc')->Select('scheme_group_name','is_active','created_at as createdDate')->get(); 
                foreach ($items as $key => $value) {
                    if ($value->is_active == 1) {
                        $value->is_active = "Active";
                    } else {
                        $value->is_active = "Inactive";
                    }       
                    $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
                    $data[] = array(
                        $key + 1,
                        $value->scheme_group_name,
                        $value->is_active,
                        $value->createdDate,
                    );
                }
                \Excel::create('SchemeGroup-Sheet', function ($excel) use ($data) {
        
                    // Set the title
                    $excel->setTitle('Scheme Group Sheet');
        
                    // Chain the setters
                    $excel->setCreator('Seraikela')->setCompany('Seraikela');
        
                    $excel->sheet('Scheme Group Sheet', function ($sheet) use ($data) {
                        $sheet->freezePane('A3');
                        $sheet->mergeCells('A1:I1');
                        $sheet->fromArray($data, null, 'A1', true, false);
                        $sheet->setColumnFormat(array('I1' => '@'));
                    });
                })->download('xls');
                   
   
               }
            //    return $request;
           }
       }
   
}

