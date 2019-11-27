<html>
<head>
    <title>schemes</title>
    <style type="text/css">
        table {
            border-color: black;
        }
    </style>
</head>

<body>
    <h3><u>All Schemes or Funds</h3></u>
    <br> 
    <table border="1px" style="border-collapse: collapse; width: 21%;" class="table table-striped table-bordered table-datatable">
            <thead>
                <tr class="table-secondary">
                    <th scope="col">#</th>
                    <th scope="col">Groups</th>
                    <th scope="col">Is Active</th>
                  
                </tr>
            </thead>
            @if(@count($user['results']!=0))
                @foreach($user['results'] as $key => $val)
                <tbody style="text-align:center;">
                    <tr >
                        <td style="width:15%;">{{++$key}}</td>
                        <td>{{$val->scheme_group_name}}</td>
                        <td>
                            @if($val->is_active=='1')
                            <h3 style="color: green; padding-left: 10px;">Active</h3>
                            @else
                            <h3 style="color: red; padding-left: 10px;">In-Active</h3>
                            @endif
                        </td>
                    </tr>
                @endforeach
           @endif
        </tbody>
    </table>
</body>
</html>

