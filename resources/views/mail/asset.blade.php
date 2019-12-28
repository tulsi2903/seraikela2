<html>
<head>
    <title>Asset</title>
    <style type="text/css">
        table {
            border-color: black;
        }
    </style>
</head>

<body>
    <h3><u>Asset</u></h3>
    <br> 
    <table border="1px" style="border-collapse: collapse; width: 80%;margin-left:10%;" class="table table-striped table-bordered table-datatable">
            <thead>
                <tr>
                     <th>#</th>
					
					<th>Name</th>
					<th>Type</th>
					<th>Department Name</th>
                    
                </tr>
            </thead>
			  <?php $count=1; ?>
			
                @foreach($user['results'] as $key => $val)
                <tbody style="text-align:center;">
                    <tr>
                        <td width="40px;">{{$count++}}</td>
                           
                            <td>{{$val->asset_name}}</td>
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
                            <td>{{$val->dept_name}}</td>
                        
                    </tr>
                </tbody>
                @endforeach
           
    </table>
</body>
</html>