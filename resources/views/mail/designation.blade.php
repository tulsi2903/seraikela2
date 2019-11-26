<html>
<head>
    <title>designation</title>
    <style type="text/css">
        table {
            border-color: black;
      
        }
    </style>
</head>
<body>
    <h3><u>All Designation</h3></u>
    <br> 
    <table border="1px" style="border-collapse: collapse;" class="table table-striped table-bordered table-datatable table-sm">
        <thead>
            <tr class="table-secondary">
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Organisation</th>
                
            </tr>
        </thead>
        <?php $count=1; ?>
 
            @foreach($user['results'] as $data)
                <tr>
                    <td width="40px;">{{$count++}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->org_name}}</td>
                    
                </tr>
            @endforeach
 
       
    </table>
    
</body>
</html>