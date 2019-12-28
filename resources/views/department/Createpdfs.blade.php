<html>
   <style>
   .table_css {
         display: table;
         border-collapse: collapse;
      }
   </style>
   @if($department)
      <table border="1" class="table_css" style="width:100%">
      <thead>
         <tr>
            <th style="text-align: center;" colspan="5">
               Department
            </th>
         </tr>
         <tr>
            <th style="text-align: center;width: 30px;">Sl.No.</th>
            <th style="text-align: center;">Name</th>
            <th style="text-align: center;">Org Name</th>
            <th style="text-align: center;">Status</th>
            <th style="text-align: center;">Date</th>
            
         </tr>
      </thead>
      <tbody>
         @foreach($department as $index => $department)
         <tr>
            <td>{{ $index+1 }}</td>
            <td>{{ $department->dept_name }}</td>
            <td>{{ $department->org_name }}</td>
            
            @if($department->is_active == 1)
               <td>Active</td>
            @endif
            @if($department->is_active == 0)
               <td>InActive</td>
            @endif
            <td>{{ date('d/m/Y',strtotime($department->created_at))}}</td>
         </tr>
         @endforeach
      </tbody>
      </table>
   @elseif($year)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="4">
                  Year
               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Year</th>
               <th style="text-align: center;">Status</th>
               <th style="text-align: center;">Date</th>
      
            </tr>
         </thead>
         <tbody>
            @foreach($year as $index => $year)
            <tr>
               <td>{{ $index+1 }}</td>
               <td>{{ $year->year_value }}</td>
      
               @if($year->status == 1)
               <td>Active</td>
               @endif
               @if($year->status == 0)
               <td>InActive</td>
               @endif
               <td>{{ date('d/m/Y',strtotime($year->created_at))}}</td>
            </tr>
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $yeardateTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($uomdata)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="3">
                  UoM
               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Name</th>
               <th style="text-align: center;">Date</th>
      
            </tr>
         </thead>
         <tbody>
            @foreach($uomdata as $index => $uomdata)
            <tr>
               <td>{{ $index+1 }}</td>
               <td>{{ $uomdata->uom_name }}</td>
               <td>{{ date('d/m/Y',strtotime($uomdata->created_at))}}</td>
            </tr>
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $UoMdateTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($Moduledata)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="3">
                  Module
               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Module Name</th>
               <th style="text-align: center;">Date</th>
      
            </tr>
         </thead>
         <tbody>
            @foreach($Moduledata as $index => $Moduledata)
            <tr>
               <td>{{ $index+1 }}</td>
               <td>{{ $Moduledata->mod_name }}</td>
               <td>{{ date('d/m/Y',strtotime($Moduledata->created_at))}}</td>
            </tr>
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $ModuledateTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($Designationdata)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="4">
                  Designation
               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Name</th>
               <th style="text-align: center;">Organisation Name</th>
               <th style="text-align: center;">Date</th>
      
            </tr>
         </thead>
         <tbody>
            @foreach($Designationdata as $index => $Designationdata)
            <tr>
               <td>{{ $index+1 }}</td>
               <td>{{ $Designationdata->name }}</td>
               <td>{{ $Designationdata->org_name }}</td>
               <td>{{ date('d/m/Y',strtotime($Designationdata->created_at))}}</td>
            </tr>
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $DesignationdateTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($Usersdata)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="8">
                  User Detail
               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Name</th>
               <th style="text-align: center;">Email Id</th>
               <th style="text-align: center;">User Name</th>
               <th style="text-align: center;">Designation</th>
               <th style="text-align: center;">Address</th>
               <th style="text-align: center;">Mobile Number</th>
               <th style="text-align: center;">Status</th>
      
            </tr>
         </thead>
         <tbody>
            @foreach($Usersdata as $index => $Usersdata)
            <tr>
               <td>{{ $index+1 }}</td>
               <td>{{ $Usersdata->title }} {{ $Usersdata->first_name }} {{ $Usersdata->middle_name }} {{ $Usersdata->last_name }}</td>
               <td>{{ $Usersdata->email }}</td>
               <td>{{ $Usersdata->username }}</td>
               <td>{{ $Usersdata->desig_name }}</td>
               <td>{{ $Usersdata->address }}</td>
               <td>{{ $Usersdata->mobile }}</td>
               @if($Usersdata->status == 1)
               <td>Active</td>
               @endif
               @if($Usersdata->status == 0)
                  <td>InActive</td>
               @endif
               </tr>
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $UsersdateTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($Assetdata)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="5">
                  Asset
               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Name</th>
               <th style="text-align: center;">Type</th>
               <th style="text-align: center;">Department Name</th>
               <th style="text-align: center;">Date</th>
      
            </tr>
         </thead>
         <tbody>
            @foreach($Assetdata as $index => $Assetdata)
            <tr>
               <td>{{ $index+1 }}</td>
               <td>{{ $Assetdata->asset_name }}</td>
               @if($Assetdata->movable == 1)
                  <td>Movable</td>
               @endif
               @if($Assetdata->movable == 0)
                  <td>Immovable</td>
               @endif
               <td>{{ $Assetdata->dept_name }}</td>
               <td>{{ date('d/m/Y',strtotime($Assetdata->created_at))}}</td>
            </tr>
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $AssetdateTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($AssetNumberdata)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="6">
                  Asset Numbers

               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Year</th>
               <th style="text-align: center;">Asset</th>
               <th style="text-align: center;">Block</th>
               <th style="text-align: center;">Panchyat</th>
               <th style="text-align: center;">Current Value</th>
      
            </tr>
         </thead>
         <tbody>
            @foreach($AssetNumberdata as $index => $AssetNumberdata)
            <tr>
               <td>{{ $index+1 }}</td>
               <td>{{ $AssetNumberdata->year_value }}</td>
               <td>{{ $AssetNumberdata->asset_name }}</td>
               <td>{{ $AssetNumberdata->block_name }}</td>
               <td>{{ $AssetNumberdata->panchayat_name }}</td>
               <td>{{ $AssetNumberdata->current_value }}</td>
            </tr>
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $AssetNumberdateTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($export_assest_catagory)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="4">
                  Asset Category

               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Name</th>
               <th style="text-align: center;">Category Description</th>
               <th style="text-align: center;">Type</th>
      
            </tr>
         </thead>
         <tbody>
            @foreach($export_assest_catagory as $index => $export_assest_catagory_data)
            <tr>
               <td>{{ $index+1 }}</td>
               <td>{{ $export_assest_catagory_data->asset_cat_name }}</td>
               <td>{{ $export_assest_catagory_data->asset_cat_description }}</td>
               <td>
                  <?php
                  if($export_assest_catagory_data->movable == '1'){
                      echo "Movable";
                  }
                  else{
                      echo "Immovable";
                  }
                  ?>
              </td>
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $AssetCatagoryTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($export_assest_subcatagory)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="4">
                  Asset Sub Category
               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Sub Category Name</th>
               <th style="text-align: center;">Sub Category Description</th>
               <th style="text-align: center;">Category Name</th>
      
            </tr>
         </thead>
         <tbody>
            @foreach($export_assest_subcatagory as $index => $export_assest_subcatagory_data)
            <tr>
               <td>{{ $index+1 }}</td>
               <td>{{ $export_assest_subcatagory_data->asset_sub_cat_name }}</td>
               <td>{{ $export_assest_subcatagory_data->asset_sub_cat_description }}</td>
               <td>{{ $export_assest_subcatagory_data->asset_cat_name}}</td>
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $AssetSubCatagoryTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($departmentpdf)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="3">
                  Department
               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Check Favourite</th>
               <th style="text-align: center;">Department Name</th>
      
            </tr>
         </thead>
         <tbody>
            @foreach($departmentpdf as $index => $departmentpdf_data)
            <tr>
               <td>{{ $index+1 }}</td>
               <td>@if($departmentpdf_data->checked==1) Yes  @else  No @endif </td>
               <td>{{ $departmentpdf_data->dept_name }}</td>
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $DeprtmentTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($AssetReviewdata)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="<?php echo count($AssetReviewdata[0]); ?>">
                  Asset Review
               </th>
            </tr>
         </thead>
         <tbody>
            @for($i = 0; $i < count($AssetReviewdata); $i++)
               @if ($i == 0)
                  <tr>
               @else
                  <tr>
               @endif

               @for($j = 0; $j < count($AssetReviewdata[0]); $j++)
                  @if ($i == 0)
                     @if ($AssetReviewdata[$i][$j] == null)
                        <th>Name</th>
                        @else
                        <th>{{$AssetReviewdata[$i][$j]}}</th>
                     @endif
                  @else
                        <td>{{$AssetReviewdata[$i][$j]}}</td>
                  @endif

               @endfor
            @endfor 
            </tr>
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $AssetReviewdateTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($Scheme_pdf)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="4">
                  Schemes
               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Scheme Name</th>
               <th style="text-align: center;">Short Name</th>
               <th style="text-align: center;">Check Favourite</th>
      
            </tr>
         </thead>
         <tbody>
            @foreach($Scheme_pdf as $index => $Scheme_pdf_data)
            <tr>
               <td>{{ $index+1 }}</td>               
               <td>{{ $Scheme_pdf_data->scheme_name }}</td>
               <td>{{ $Scheme_pdf_data->scheme_short_name }}</td>
               <td>@if($Scheme_pdf_data->checked==1) Yes  @else  No @endif </td>
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $SchemeTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($block_pdf)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="3">
                  Block
               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Block Name</th>
               <th style="text-align: center;">Check Favourite</th>
      
            </tr>
         </thead>
         <tbody>
            @foreach($block_pdf as $index => $block_pdf_data)
            <tr>
               <td>{{ $index+1 }}</td>               
               <td>{{ $block_pdf_data->geo_name}}</td>
               <td>@if($block_pdf_data->checked==1) Yes  @else  No @endif </td>
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $BlockTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($panchayat_pdf)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="3">
                  Panchayat
               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Panchayat Name</th>
               <th style="text-align: center;">Check Favourite</th>
      
            </tr>
         </thead>
         <tbody>
            @foreach($panchayat_pdf as $index => $panchayat_pdf_data)
            <tr>
               <td>{{ $index+1 }}</td>               
               <td>{{ $panchayat_pdf_data->geo_name}}</td>
               <td>@if($panchayat_pdf_data->checked==1) Yes  @else  No @endif </td>
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $PanchayatTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($asset_pdf)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="4">
                  Assets
               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Asset Name</th>
               <th style="text-align: center;">Department Name</th>
               <th style="text-align: center;">Check Favourite</th>      
            </tr>
         </thead>
         <tbody>
            @foreach($asset_pdf as $index => $asset_pdf_data)
            <tr>
               <td>{{ $index+1 }}</td>               
               <td>{{ $asset_pdf_data->asset_name}}</td>
               <td>{{ $asset_pdf_data->dept_name}}</td>
               <td>@if($asset_pdf_data->checked==1) Yes  @else  No @endif </td>
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $AssetsTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($SchemeStructure_pdf)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="4">
                  Define Schemes
               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Scheme Name</th>
               <th style="text-align: center;">Short Name</th>
               <th style="text-align: center;">Department</th>      
            </tr>
         </thead>
         <tbody>
            @foreach($SchemeStructure_pdf as $index => $SchemeStructure_data)
            <tr>
               <td>{{ $index+1 }}</td>               
               <td>{{ $SchemeStructure_data->scheme_name}}</td>
               <td>{{ $SchemeStructure_data->scheme_short_name}}</td>
               <td>{{ $SchemeStructure_data->dept_name}}</td>
               
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $SchemeStructureTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @elseif($SchemeGeoTarget_pdf)
   <div style="position: relative;"> <!-- position:realtive css is used for full page printing of pdf -->
      <table border="1" class="table_css" style="width:100%">
         <thead>
            <tr>
               <th style="text-align: center;" colspan="8">
                  Scheme Geo Target
               </th>
            </tr>
            <tr>
               <th style="text-align: center;width: 30px;">Sl.No.</th>
               <th style="text-align: center;">Scheme</th>
               <th style="text-align: center;">Indicator</th>
               <th style="text-align: center;">Block Name</th>      
               <th style="text-align: center;">Panchyat</th>      
               <th style="text-align: center;">Asset Group Name</th>      
               <th style="text-align: center;">Target</th>   
               <th style="text-align: center;">Year</th>
            </tr>
         </thead>
         <tbody>
            @foreach($SchemeGeoTarget_pdf as $index => $SchemeGeoTarget_data)
                  @if($SchemeGeoTarget_data->level_id=='4')
                     <tr>                                   
                           <td>{{ $index+1 }}</td>               
                           <td>{{$SchemeGeoTarget_data->scheme_name}} ({{$SchemeGeoTarget_data->scheme_short_name}})</td>
                           <td>{{ $SchemeGeoTarget_data->indicator_name}}</td>
                           <td>{{ $SchemeGeoTarget_data->bl_name}}</td>               
                           <td>{{ $SchemeGeoTarget_data->geo_name}}</td>
                           <td>{{ $SchemeGeoTarget_data->asset_group_name}}</td>
                           <td>{{ $SchemeGeoTarget_data->target}}</td>
                           <td>{{ $SchemeGeoTarget_data->year_value}}</td>
                     </tr>
                  @endif            
            @endforeach
         </tbody>
      </table>
      <p style="position: absolute; bottom: 0;"> {{ $SchemeStructureTime }}</p><!-- position:absolute and bottom:0 css is used to pull this pragraph at the end of the page -->
   </div>
   @endif
   
   
</html>