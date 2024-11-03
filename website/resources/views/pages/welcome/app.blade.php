@extends('layout.app')
@section('title', 'Welcome')
@section('content')
        <div style="color: white;display: flex; justify-content: center; align-items: center; flex-direction: column; margin-top:-13vh; position:relative">
                {{-- <div style="position: absolute; top: 130px; right:10px;">
                        <img src="{{url('assets/img/year.png')}}" class="img-fluid" alt="..." style="height: 30%;">
                </div> --}}
                <img src="{{url('assets/img/home.png')}}" class="img-fluid" alt="..." style="padding: 80px;max-width:60%; margin-top:50px">
                @if(auth()->user()->role_id=='1')
                        <a href="{{ url('/watchlist') }}" class="btn btn-danger" style="border-radius: 20px; cursor:pointer; margin-top:1%">Go to Home</a>
                @endif
                <h5 style="text-align:center ;margin-top: 2%">Most trusted platform for trading in Stock Exchange.</h5>
        </div>
@endsection