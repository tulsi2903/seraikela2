@extends('layout.layout')

@section('title', 'Seraikela')

@section('content')
   <div class="container"> 
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h4>Modules</h4>
                        <ul class="list-group">
                            <li class="list-group-item"><a href="{{url('department')}}">Department</a></li>
                            <li class="list-group-item"><a href="{{url('designation')}}">Designation</a></li>
                            <li class="list-group-item"><a href="{{url('year')}}">Year</a></li>
                            <li class="list-group-item"><a href="{{url('uom')}}">UoM</a></li>
                            <li class="list-group-item"><a href="{{url('asset')}}">Asset</a></li>
                            <li class="list-group-item"><a href="{{url('scheme_type')}}">Scheme Type</a></li>
                            <li class="list-group-item"><a href="{{url('asset_numbers')}}">Asset Numbers</a></li>
                            <li class="list-group-item"><a href="{{url('geo-structure')}}">Geo Structure</a></li>
                            <li class="list-group-item"><a href="{{url('scheme-indicator')}}">Scheme Indicator</a></li>
                            <li class="list-group-item"><a href="{{url('scheme-geo-target')}}">Scheme Geo Target</a></li>
                            <li class="list-group-item"><a href="{{url('scheme-structure')}}">Scheme Structure</a></li>
                            <li class="list-group-item"><a href="{{url('asset-review')}}">Asset Review</a></li>
                            <li class="list-group-item"><a href="{{url('group')}}">Group</a></li>
                           
                        </ul>
                    </div>
                </div>
            </div>
        </div>
   </div>
@endsection