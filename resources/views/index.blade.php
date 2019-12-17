@extends('layout.layout')

@section('title', 'Seraikela')

@section('page-content')
    <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <h1>Dashboard</h1>
            {{session()->get('user_full_name')}}
            {{session()->get('user_org_id')}}
            {{session()->get('user_designation')}}
            {{session()->get('user_designation_name')}}
            {{session()->get('user_id')}}
        </div>
    </div>
@endsection