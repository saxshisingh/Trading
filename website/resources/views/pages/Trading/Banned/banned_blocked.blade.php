@extends('layout.app')
@section('title', 'Banned/Blocked Scripts')
@section('content')

<div class="container-fluid">
    <h4>Banned/Blocked Scripts</h4>

    <div class="card" style="background-color: rgba(255, 248, 248, 0.791); box-shadow: 0 2px 10px rgba(0, 0, 0, .1); padding:20px">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    
                    <ul style="list-style-type: none; font-size:13px; color: black; font-weight:600; margin-bottom:0px; cursor:pointer">
                        @foreach ($script as $u)
                            <li>{{$u->name}} - {{$u->segment->name}}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-6">
                    
                    <ul style="list-style-type: none; color:red; font-size:13px; font-weight:600 ;margin-bottom:0px; cursor:pointer">
                        @foreach ($script as $u)
                            <li>BANNED</li>
                        @endforeach                       
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
</div>

@endsection
