<html>
<head>
    <title>scheme-structure</title>
    <style type="text/css">
        table {
            border-color: black;
      
        }
    </style>
</head>
<body>
    <h3><u>All Scheme-Structure</h3></u>
    <br> 
    <table border="1px" style="border-collapse: collapse;" class="table table-striped table-bordered table-datatable table-sm">
        <thead>
            <tr class="table-secondary">
                <th scope="col">#</th>
                <th scope="col">Scheme Name</th>
                <th scope="col">Short Name</th>
                <th scope="col">Department</th>
               
            </tr>
        </thead>
        <?php $count=1; ?>
            @foreach($user['results'] as $data)
                <tr>
                    <td width="40px;">{{$count++}}</td>
                    <td>{{@$data->scheme_name}}</td>
                    <td>{{@$data->scheme_short_name}}</td>
                    <td>{{@$data->dept_name}}</td>
                </tr>
            @endforeach
    </table>
    
</body>
</html>