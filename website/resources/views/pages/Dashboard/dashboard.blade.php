@extends('layout.app')
@section('title', 'Welcome')
@section('content')
<div class="row">

    <!-- Left side columns -->
    <div class="col-lg-12">
        <div class="card info-card sales-card">
            <div class="card-body">
                <div class="card-header" >
                    <h4 class="card-title">Live M2M under:</h4>
                </div>

                <div class="d-flex align-items-center">
                    <div class="col-3 ps-3">
                        <h6>Users</h6>
                        <span>Total: {{$userCount}} </span>
                    </div>

                    <div class="col-3 ps-3">
                        <h6>Active Profit/Loss</h6>
                        <span>0</span>
                    </div>
                
                    <div class="col-3 ps-3">
                        <h6>Active Trades</h6>
                        <span>{{$activeTradesCount}}</span>
                    </div>
                
                    <div class="col-3 ps-3">
                        <h6>Margin Used</h6>
                        <span>0</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
        <div class="row">
            <div class="col-md-4">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <div class="card-header" >
                            <h4 class="card-title">Buy Turnover</h4>
                        </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <p>MCX</p>
                                    <h6>0 Lakhs</h6>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <p>Equity</p>
                                    <h6>0 Lakhs</h6>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <p>Future Options</p>
                                    <h6>0 Lakhs</h6>
                                </div>
                            </div>
                       
                    </div>

                </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-md-4">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <div class="card-header" >
                            <h4 class="card-title">Sell Turnover</h4>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <p>MCX</p>
                                <h6>0 Lakhs</h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <p>Equity</p>
                                <h6>0 Lakhs</h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <p>Future Options</p>
                                <h6>0 Lakhs</h6>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-md-4">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <div class="card-header" >
                            <h4 class="card-title">Total Turnover</h4>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <p>MCX</p>
                                <h6>0 Lakhs</h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <p>Equity</p>
                                <h6>0 Lakhs</h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <p>Future Options</p>
                                <h6>0 Lakhs</h6>
                            </div>
                        </div>

                    </div>
                </div>

            </div><!-- End Customers Card -->
       

            <div class="col-md-4">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <div class="card-header" >
                            <h4 class="card-title">Profit/Loss</h4>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <p>MCX</p>
                                <h6>{{$profitLoss_MCX}}</h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <p>Equity</p>
                                <h6>0 </h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <p>Options</p>
                                <h6>{{$profitLoss_NSE}}</h6>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <div class="card-header" >
                            <h4 class="card-title">Brokerage</h4>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <p>MCX</p>
                                <h6>{{$brokerage_MCX}}</h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <p>Equity</p>
                                <h6>0</h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <p>Options</p>
                                <h6>{{$brokerage_NSE}}</h6>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
           

            <div class="col-md-4">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <div class="card-header" >
                            <h4 class="card-title">Active Buy</h4>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <p>MCX</p>
                                <h6>{{$buyTurnoverCount_MCX}}</h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <p>Equity</p>
                                <h6>0 </h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <p>Options</p>
                                <h6>{{$buyTurnoverCount_NSE}}</h6>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <div class="card-header" >
                            <h4 class="card-title">Active Sell</h4>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <p>MCX</p>
                                <h6>{{$sellTurnoverCount_MCX}}</h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <p>Equity</p>
                                <h6>0 </h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <p>Options</p>
                                <h6>{{$sellTurnoverCount_NSE}}</h6>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            
            <!-- End Revenue Card -->

            
            


                

                       


                
        </div>
        </div>

    </div><!-- End Left side columns -->
</div>
@endsection