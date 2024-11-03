@extends('layout.app')
@section('title', $user->first_name . ' ' . $user->last_name . ' (' . $user->client . ')')
@section('content')
<div class="container-fluid">
    <form action="" method="GET">
        @csrf
    <div class="row">
        <div class="col-sm-2">
            <h6>FROM DATE</h6>
            <input type="date" class="form-control" name="after">
        </div>
        
        <div class="col-sm-2">
            <button class="btn btn-dark btn-sm mt-4" style="padding: 10px; background-color:black">FIND LOGS</button>
        </div>
    </div>
    </form>
</div>

<div class="container-fluid" style="padding: 30px">
    <div class="row">
        <div class="col-md-2">
            @include('partials.page_select')
        </div>

        <div class="col-sm-3"></div>
        <div class="col-sm-1">
            <div class="d-flex align-items-center">
                <a href="{{ route('ledger.export') }}">
                    <span class="input-group-text" style="background-color: #946151; color:white">CSV</span>
                </a>
                {{-- <span class="input-group-text ml-2" style="background-color: #946151; color:white">PDF</span> --}}
            </div>
        </div>

        <div class="col-sm-2"></div>

        <div class="col-sm-4">
            <!-- Search bar -->
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Search</span>
                </div>
                <input id="searchInput" type="text" class="form-control" placeholder="" aria-label="Search" aria-describedby="basic-addon2">
            </div>
        </div>

    </div>
</div>
<div class="row">
    <h6>Initial Balance : {{$initial_balance}}</h6>
</div>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>SR NO</th>
                <th>REMARKS</th>
                <th>DATE</th>
                <th>DEBIT</th>
                <th>CREDIT</th>
                <th>DOWNLOAD</th>
            </tr>
        </thead>
        <tbody>				
			@foreach($wallet as $r)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{$r->remarks}}</td>
                    <td>{{$r->created_at}} </td>
                    <td>
                        @if($r->type=='debit')
                        {{ number_format($r->profit_loss, 5) }}
                        @else
                        0
                        @endif
                    </td>
                    <td>
                        @if($r->type=='credit')
                        {{ number_format($r->profit_loss, 5) }}
                        @else
                        0
                        @endif
                    </td>
                    <td>-</td>
                </tr>
                @endforeach
        </tbody>
    </table>
    <div style="text-align: right; padding:20px">
        <h6>Total Balance : {{$balance}}</h6>
    </div>


</div>
@endsection