<html>
<head>
    <title>Mgnrega</title>
    <style type="text/css">
        table {
            border-color: black;
        }
    </style>
</head>

<body>
    <h3><u>Mgnrega</h3></u>
    <br> 
    <table border="1px" style="border-collapse: collapse; width: 80%;margin-left:10%;" class="table table-striped table-bordered table-datatable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>                   
                </tr>
            </thead>

            @if(@count($user['results']!=0))
                @foreach($user['results'] as $key => $val)
                <tbody style="text-align:center;">
                    <tr>
                        <td style="width:40px">{{++$key}}</td>
                        <td>{{$val->mgnrega_category_name}}</td>         
                    </tr>
                </tbody>
                @endforeach
            @endif
    </table>
</body>
</html>