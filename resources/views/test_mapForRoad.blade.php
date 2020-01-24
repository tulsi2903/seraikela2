@extends('layout.layout')

@section('title', session()->get('dashboard_title'))
@section('page-content')
<div class="row row-card-no-pd" style="padding-bottom: 0;border-top: 3px solid #5c76b7; min-height:812px;">
    <!-- map selection starts -->
    <div class="card-body">
        <div id="mapCanvas" style="height: 500px; width:100%; background-color: #565656;">   ddssds</div>

        <div>
            <input type="button" value="click" onclick="initMap();" >
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuYbCxfGw_c6lasAlpExIOFj55MVY6xSo"></script>

    <script>
      var map;
      function initMap() {
        var map = new google.maps.Map(document.getElementById('mapCanvas'), {
            zoom: 18,
          center: {lat: 22.797205, lng: 86.134772},
          mapTypeId: 'terrain'
        });

        var flightPlanCoordinates = [
          {lat: 22.797205, lng: 86.134772},
          {lat: 22.797690, lng: 86.135834},
          {lat: 22.798171, lng: 86.135378},
          {lat: 22.798312, lng: 86.136218}
        ];
        var flightPath = new google.maps.Polyline({
          path: flightPlanCoordinates,
          geodesic: true,
          strokeColor: '#FF0000',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });

        var myLatLng = {lat: 22.797205, lng: 86.134772};
        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Hello World!'
        });
        var myLatLng = {lat: 22.798312, lng: 86.136218};
        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Hello World!'
        });
        flightPath.setMap(map);
      }
    
    </script>
    <script>
    function initMap2() {
        var myLatLng = {lat: -25.363, lng: 131.044};

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: myLatLng
        });

        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Hello World!'
        });
      }
    </script>


    @endsection