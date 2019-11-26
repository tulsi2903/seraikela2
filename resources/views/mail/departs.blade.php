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
    <table border="1px" style="border-collapse: collapse;" class="table table-striped table-bordered table-datatable table-sm">
        <thead>
            <tr class="table-secondary">
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Organisation</th>
                <th scope="col">is Active</th>

            </tr>
        </thead>
        <?php print_r($user['results']); ?>
    <?php $count=1; ?>
            @foreach($user['results'] as $data)
            <tr>
                <td width="40px;">{{$count++}}</td>
                <td>{{$data->dept_name}}</td>
                <td>{{$data->org_name}}</td>
                <td>
                    <?php if($data->is_active=='1'){
                                                echo "Active";
                                            }
                                            else{
                                                echo "Inactive";
                                            } ?>
                </td>
            </tr>
            @endforeach 

    </table>
</body>
</html>