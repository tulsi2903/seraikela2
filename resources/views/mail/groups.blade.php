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
    <table border="1px" style="border-collapse: collapse;" class="table table-striped table-bordered table-datatable table-sm">
            <thead>
                <tr class="table-secondary">
                    <th scope="col">#</th>
                    <th scope="col">Groups</th>
                    <th scope="col">Is Active</th>
                  
                </tr>
            </thead>
            <?php $count=1; ?>
            @foreach($user['results'] as $result)
                <tr>
                    <td width="40px;">{{$count++}}</td>
                    <td>{{$result->scheme_group_name}}</td>
                    <td><?php if($result->is_active=='1'){
                        echo "Active";
                    }
                    else{
                        echo "Inactive";
                    } ?></td>
                </tr>
            @endforeach               
    </table>
</body>
</html>

