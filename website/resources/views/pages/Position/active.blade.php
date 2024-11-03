@extends('layout.app')
@section('title', '')
@section('pagetitle', '')
@section('content')
<div class="row">
    <div class="container-fluid">
    
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="col-md-12">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <div class="card-header" >
                        <h4 class="card-title">Active Positions</h4>
                    </div>
                    
                    <div class="table-responsive text-center">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Script</th>
                                    <th>Symbol</th>
                                    <th>Active Buy</th>
                                    <th>Active Sell</th>
                                    <th>Avg Buy Rate</th>
                                    <th>Avg Sell Rate</th>
                                    <th>Total</th>
                      
                                </tr>
                            </thead>
                            <tbody >
                                @foreach($portfolios as $portfolio)
                                    <tr>
                                        <td>{{$portfolio->user->first_name}} {{$portfolio->user->last_name}}</td>
                                        <td>{{ $portfolio->script->name }}</td>
                                        <td>{{$portfolio->expiry->tradingsymbol}}</td>
                                        <td>
                                            @if($portfolio->action=="BUY")
                                                {{ $portfolio->qty }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if($portfolio->action=="SELL")
                                                {{ $portfolio->qty }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if($portfolio->action=="BUY")
                                                {{ $portfolio->buy_rate }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if($portfolio->action=="SELL")
                                                {{ $portfolio->sell_rate }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if($portfolio->action=="SELL")
                                                {{ $portfolio->sell_rate*$portfolio->qty }}
                                            @else
                                                {{ $portfolio->buy_rate*$portfolio->qty }}
                                            @endif
                                        </td>
                               
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Totals</th>
                                    <th></th>
                                    <th>{{ $totals['active_buy'] }}</th>
                                    <th>{{ $totals['active_sell'] }}</th>
                                    <th>{{ $totals['avg_buy_rate'] }}</th>
                                    <th>{{ $totals['avg_sell_rate'] }}</th>
                                    <th>{{ $totals['total'] }}</th>
                           
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

