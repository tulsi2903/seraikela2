<html>
<head>
    <title>Asset SubCategory</title>
    <style type="text/css">
        table {
            border-color: black;
        }
    </style>
</head>

<body>
    <h3><u>Asset SubCategory</u></h3>
    <br> 
    <table border="1px" style="border-collapse: collapse; width: 80%;margin-left:10%;" class="table table-striped table-bordered table-datatable">
            <thead>
                <tr>
                     <th>#</th>
					<th>Sub Category Name</th>
					<th>Sub Category Description</th>
					<th> Category Name  </th>
                    
                </tr>
            </thead>

            <?php $count=1; ?>
                @foreach($user['results'] as $key => $val)
                <tbody style="text-align:center;">
                    <tr>
                        <td width="40px;">{{$count++}}</td>
						<td>{{$val->asset_sub_cat_name}}</td>
						<td>{{$val->asset_sub_cat_description}}</td>
						<?php 
						$Asset_cat_name=DB::table('asset_cat')->where('asset_cat_id',@$val->asset_cat_id)->first();
						?>
						<td>{{$Asset_cat_name->asset_cat_name}}</td>
					
                        
                    </tr>
                </tbody>
                @endforeach
            
    </table>
</body>
</html>