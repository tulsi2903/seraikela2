
<html>
<head>
    <title>scheme-Asset</title>
    <style type="text/css">
        table {
            border-color: black;
      
        }
    </style>
</head>
<body>
    <h3><u>All Scheme-Asset</h3></u>
    <br> 
    <table border="1px" style="border-collapse: collapse;" class="table table-striped table-bordered table-datatable table-sm">
        <thead>
            <tr class="table-secondary">
                <th scope="col">#</th>
                <th scope="col">Scheme Name</th>
                <th scope="col">Geo Related</th>
                <th scope="col">Multiple Geo Related</th>
               
            </tr>
        </thead>
        <?php $count=1; ?>
            @foreach($user['results'] as $data)
            

                <tr>
                    <td width="40px;">{{$count++}}</td>
                    <td>{{@$data->scheme_asset_name}}</td>
                    <td><?php
                        if(@$data->geo_related == 1){
                            echo "yes";
                        }
                        else 
                        {
                            echo "no";
                        }?>
                    </td>
                    <td><?php
                        if(@$data->multiple_geo_tags == 1){
                            echo "yes";
                        }
                        else 
                        {
                            echo "no";
                        }?>
                    </td>
                    
                </tr>
            @endforeach
    </table>
    
</body>
</html>