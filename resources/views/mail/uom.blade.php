<html>

<head>
    <title>uom</title>
    <style type="text/css">
        table {
            border-color: black;
        }
    </style>
</head>

<body>
    <h3><u>UoM</h3></u>
    <br>
    <table border="1px" style="border-collapse: collapse; width: 70%;margin-left:10%;" class="table table-striped table-bordered table-datatable">
        <thead>
            <tr style="text-align:center;">
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Conversion Unit</th>
            </tr>
        </thead>

        @if(@count($user['results']!=0))
        @foreach($user['results'] as $key => $val)
        <tbody>
            <tr>
                <td style="text-align:left;">{{++$key}}</td>
                <td style="text-align:center;">{{$val->uom_name}}</td>
                <td style="text-align:center;">
                    <?php
                    echo "1 " . $val->uom_name . " = <b>" . $val->conversion_unit . "</b>";
                    if ($val->uom_type_id == 1) {
                        echo " meter";
                    } else if ($val->uom_type_id == 2) {
                        echo " litre";
                    }
                    ?>
                </td>
            </tr>
        </tbody>
        @endforeach
        @endif
    </table>
</body>

</html>