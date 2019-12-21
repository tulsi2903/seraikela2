<html>
   <style>
   .table_css {
    display: table;
    border-collapse: collapse;
}
   </style>
   <table border="1" class="table_css" style="width:100%">
    <thead>
       <tr>
          <th style="text-align: center;" colspan="5">
             Department
          </th>
       </tr>
       <tr>
          <th style="text-align: center;width: 30px;">Sl.No.</th>
          <th style="text-align: center;">Name</th>
          <th style="text-align: center;">Org Name</th>
          <th style="text-align: center;">Status</th>
          <th style="text-align: center;">Date</th>
           
       </tr>
    </thead>
    <tbody>
       @foreach($data as $index => $data)
       <tr>
          <td>{{ $index+1 }}</td>
          <td>{{ $data->dept_name }}</td>
          <td>{{ $data->org_name }}</td>
          
          @if($data->is_active == 1)
            <td>Active</td>
          @endif
          @if($data->is_active == 0)
            <td>InActive</td>
          @endif
          <td>{{ date('d/m/Y',strtotime($data->created_at))}}</td>
       </tr>
       @endforeach
    </tbody>
   </table>

</html>