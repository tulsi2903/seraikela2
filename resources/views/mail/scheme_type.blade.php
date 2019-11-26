<html>
<head>
    <title>scheme_type</title>
    <style type="text/css">
        table {
            border-color: black;
      
        }
    </style>
</head>
<body>
    <h3><u>All Scheme Type</h3></u>
    <br> 
    <table border="1px" style="border-collapse: collapse;" class="table table-striped table-bordered table-datatable table-sm">
        <thead>
            <tr class="table-secondary">
                <th scope="col">#</th>
                <th scope="col">Name</th>
            </tr>
        </thead>
        <?php $count=1; ?>
            @foreach($user{'results'} as $data)
                <tr>
                    <td width="40px;">{{$count++}}</td>
                    <td>{{$data->sch_type_name}}</td>
                    
                </tr>
            @endforeach
    </table>
</body>
</html>