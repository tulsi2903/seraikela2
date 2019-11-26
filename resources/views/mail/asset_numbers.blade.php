<html>
<head>
    <title>asset_numbers</title>
    <style type="text/css">
        table {
            border-color: black;
      
        }
    </style>
</head>
<body>
    <h3><u>All Asset Type</h3></u>
    <br> 
    <table border="1px" style="border-collapse: collapse;" class="table table-striped table-bordered table-datatable table-sm">
        <thead>
            <tr class="table-secondary">
                <th>#</th>
                <th>Year</th>
                <th>Asset</th>
                <th>Panchyat</th>
                <th>Pre Value</th>
                <th>Current Value</th>
            </tr>
        </thead>
        <tbody>
            <?php $count=1; ?>
           
                @foreach($user{'results'} as $data)
                    <tr>
                        <td width="40px;">{{$count++}}</td>
                        <td>{{$data->year_value}}</td>
                        <td>{{$data->asset_name}}</td>
                        <td>{{$data->geo_name}}</td>
                        <td>{{$data->pre_value}}</td>
                        <td>{{$data->current_value}}</td>        
                    </tr>
                @endforeach
        </tbody>
    </table>
    
</body>
</html>