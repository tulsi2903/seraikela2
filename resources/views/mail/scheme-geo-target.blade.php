<html>
<head>
    <title>scheme geo target</title>
    <style type="text/css">
        table {
            border-color: black;
        }
    </style>
</head>

<body>
    <h3><u>All Scheme Geo Target</h3></u>
    <br> 
    <table border="1px" style="border-collapse: collapse; width: 80%;margin-left:10%;" class="table table-striped table-bordered table-datatable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Scheme</th>
                    <th scope="col">Panchayat</th>
                    <th scope="col">Indicator</th>
                    <th scope="col">Asset Group Name</th>
                    <th scope="col">Target</th>
                    <th scope="col">Year</th>
                </tr>
            </thead>

            @if(@count($user['results']!=0))
                @foreach($user['results'] as $key => $val)
                <tbody style="text-align:center;">
                    <tr>
                        <td style="width:20px">{{++$key}}</td>
                        <td>{{$val->scheme_name}}({{$val->scheme_short_name}})</td>
                        <td>{{$val->geo_name}}</td>
                        <td>{{$val->indicator_name}}</td>
                        <td>{{$val->asset_group_name}}</td>
                        <td>{{$val->target}}</td>
                        <td>{{$val->year_value}}</td> 
                    </tr>
                </tbody>
                @endforeach
            @endif
    </table>
</body>
</html>