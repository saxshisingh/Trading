@extends('layout.app')
@section('title', '')
@section('pagetitle', '')
@section('content')
<div class="row">
    <div class="container-fluid">
    
        @if (old('error'))
            <div class="alert alert-danger">{{ old('error') }}</div>
        @endif
        @if (old('success'))
            <div class="alert alert-success">{{ old('success') }}</div>
        @endif
        <div class="col-md-12">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <div class="card-header" >
                        <h4 class="card-title">Closed Positions</h4>
                    </div>
                    
                    <div class="table-responsive text-center">
                    @php
                        $totalProfitLoss = 0;
                        $sellRate = 0;
                        $buyRate = 0;
                        $lots = 0;
                        $quantity = 0;
                    @endphp
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Script</th>
                                    <th>Symbol</th>
                                    <th>Lots</th>
                                    <th>Avg Buy Rate</th>
                                    <th>Avg Sell Rate</th>
                                    <th>Profit/ Loss</th>
                                    <!-- <th>Brokerage</th>
                                    <th>Net P/L</th> -->
                                </tr>
                            </thead>
                            <tbody >

                                @foreach($positions as $position)
                                    @php
                                    $totalProfitLoss += $position->wallet ? $position->wallet->profit_loss : 0;
                                    $sellRate += $position->sell_rate?$position->sell_rate:0;
                                    $buyRate += $position->buy_rate?$position->buy_rate:0;
                                    $lots += $position->lot;
                                    $quantity += $position->qty;

                                    @endphp
                                    <tr>
                                    <td>{{$position->user->first_name}} {{$position->user->last_name}}</td>
                                    <td>{{ $position->script->name }}</td>
                                        <td>{{$position->expiry->tradingsymbol}}</td>
                                        <td>{{ $position->lot }}</td>
                                        <td>{{ $position->qty }}</td>
                                        <td>{{ $position->buy_rate?$position->buy_rate:"0" }}</td>
                                        <td>{{ $position->sell_rate?$position->sell_rate:"0" }}</td>
                                        <td>{{ $position->wallet ? number_format($position->wallet->profit_loss, 2) : '0' }}</td>                                        
                                       
                                    </tr>
                                @endforeach
                      
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Totals</th>
                                    <th></th>
                                    <th>{{ $lots }}</th>
                                    <th>{{$quantity}}</th>
                                    <th>{{ $buyRate}}</th>
                                    <th>{{ $sellRate}}</th>
                                    <th>{{ $totalProfitLoss }}</th>
                                
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

