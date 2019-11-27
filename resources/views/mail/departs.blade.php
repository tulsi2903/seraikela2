<html>

<head>
    <title>department</title>
    <style type="text/css">
        table {
            border-color: black;

        }
    </style>
</head>

<body>
    
    <h3><u>All Department</h3></u>
    <br>
    <table border="1px" style="border-collapse: collapse;"
        class="table table-striped table-bordered table-datatable table-sm">
        <thead>
            <tr class="table-secondary">
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Organisation</th>
                <th scope="col">is Active</th>

            </tr>
        </thead> 
        @if(@count($user['results']!=0))
            @foreach($user['results'] as $key => $val)
            <tr>
                <td width="40px;">{{++$key}}</td>
                <td>{{$val->dept_name}}</td>
                <td>{{$val->org_name}}</td>
                <td>
                    @if($val->is_active=='1')
                    <h3 style="color: green;">Active</h3>
                    @else
                    <h3 style="color: red;">In-Active</h3>
                    @endif
                </td>
            </tr>
            @endforeach
        @endif

    </table>
</body>

</html>