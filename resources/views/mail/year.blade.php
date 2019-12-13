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
                    <th scope="col">Year</th>
                    <th scope="col">Status</th>
                    
                </tr>
            </thead>

            @if(@count($user['results']!=0))
                @foreach($user['results'] as $key => $val)
                <tbody style="text-align:center;">
                    <tr>
                        <td style="width:20px">{{++$key}}</td>
                        <td>{{$val->year_value}}</td>
                        <td><?php if($val->status=='1'){
                                                echo '<i class="fas fa-check text-success"></i> Active';
                                            }
                                            else{
                                                echo '<i class="fas fa-times text-danger"></i> Inactive';
                                            } ?></td>
                        
                    </tr>
                </tbody>
                @endforeach
            @endif
    </table>
</body>
</html>