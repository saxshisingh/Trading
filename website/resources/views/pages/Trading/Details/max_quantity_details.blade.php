@extends('layout.app')
@section('title', 'Max Quantity Details')
@section('content')
<h4>Max Quantity Details</h4>
<div class="container-fluid" style="padding: 30px">
    @if (old('error'))
            <div class="alert alert-danger">{{ old('error') }}</div>
        @endif
        @if (old('success'))
            <div class="alert alert-success">{{ old('success') }}</div>
        @endif
    <div class="row">
        
            <div class="col-md-2">
                @include('partials.page_select')
            </div>
        
        <div class="col-sm-6"></div>
        <div class="col-sm-4">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Search</span>
                </div>
                <input type="text" class="form-control" placeholder="" aria-label="Search" aria-describedby="basic-addon2">
            </div>
        </div>
        
    </div>
</div>

<div class="table-responsive">
    <style>
        td, th {
            text-align: center;
            border: 1px solid #888484; /* Add border to both th and td */
            padding: 8px; 
        }
    </style>
    <table class="table">
        <thead>
            <tr>
                <th>
                    MARKET
                    <i class="bi bi-caret-up-fill caret"></i>
                    <i class="bi bi-caret-down-fill caret"></i>
                </th>
                <th>
                    SCRIPT
                    <i class="bi bi-caret-up-fill caret"></i>
                    <i class="bi bi-caret-down-fill caret"></i>
                </th>
                <th>
                    POSITION LIMIT
                    <i class="bi bi-caret-up-fill caret"></i>
                    <i class="bi bi-caret-down-fill caret"></i>
                </th>
                <th>
                    MAX ORDER
                    <i class="bi bi-caret-up-fill caret"></i>
                    <i class="bi bi-caret-down-fill caret"></i>
                </th>  
            </tr>
        </thead>
        <tbody>
            @foreach ($script as $u)
                <tr>
                <td>{{ $u->segment->name }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->position_limit }}</td>
                <td>{{ $u->max_order }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col-sm-7">

        </div>
        <div class="col-sm-3">

            {!! $script->appends(\Request::all())->links() !!}   
        </div>
    </div>
</div>
@endsection