<html>
<head>
    <title>asset</title>
    <style type="text/css">
        table {
            border-color: black;
      
        }
    </style>
</head>
<body>
    <h3><u>All Asset</h3></u>
    <br> 
    <table border="1px" style="border-collapse: collapse;" class="table table-striped table-bordered table-datatable table-sm">
        <thead>
            <tr class="table-secondary">
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Type</th>
                <th scope="col">Department Name</th>
            </tr>
        </thead>
        <?php $count=1; ?>
            @foreach($user['results'] as $data)
            <tr>
                <td width="40px;">{{$count++}}</td>
                <td>{{$data->asset_name}}</td>
                <td>
                    <?php
                    if($data->movable == '1'){
                        echo "Movable";
                    }
                    else{
                        echo "Immovable";
                    }
                    ?>
                </td>
                <td>{{$data->dept_name}}</td>
               
            </tr>
            @endforeach 
     </table> 
</body>
</html>