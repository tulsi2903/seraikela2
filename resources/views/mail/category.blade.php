<html>
<head>
    <title>Asset Category</title>
    <style type="text/css">
        table {
            border-color: black;
        }
    </style>
</head>

<body>
    <h3><u>Asset Category</u></h3>
    <br> 
    <table border="1px" style="border-collapse: collapse; width: 80%;margin-left:10%;" class="table table-striped table-bordered table-datatable">
            <thead>
                <tr>
                     <th>#</th>
					<th>Name</th>
					<th>Category Description</th>
					<th>Type</th>  
                    
                </tr>
            </thead>
			  <?php $count=1; ?>
			
                @foreach($user['results'] as $key => $val)
                <tbody style="text-align:center;">
                    <tr>
                        <td width="40px;">{{$count++}}</td>
						<td>{{$val->asset_cat_name}}</td>
						<td>{{$val->asset_cat_description}}</td>
						
						<td>
							<?php
							if($val->movable == '1'){
								echo "Movable";
							}
							else{
								echo "Immovable";
							}
							?>
						</td>
                        
                    </tr>
                </tbody>
                @endforeach
           
    </table>
</body>
</html>