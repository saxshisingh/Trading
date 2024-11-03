@extends('layout.app')
@section('title', 'Welcome')
@section('pagetitle', 'users Create')

@section('button')
    <div class="text-end">
        <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm" style="padding: 10px">View Members</a>
    </div>
@endsection
@section('content')
<div class="container-fluid">
    @foreach ($errors->all() as $error)
    <div class="alert alert-danger">{{ $error }}</div>
    @endforeach
    @if (old('error'))
        <div class="alert alert-danger">{{ old('error') }}</div>
    @endif
    @if (old('success'))
        <div class="alert alert-success">{{ old('success') }}</div>
    @endif
    <form action="{{ route('users.store') }}" method="post" >
        @csrf
        <div class="card info-card sales-card">
            <div class="card-body">
                <div class="row mt-2">
                    <h4>Personal Details :</h4>
                    @if (Route::is('users.edit'))
                        <input type="hidden" name="id" value="{{ $users->id }}" />
                    @endif
                    <div class="col-md-6 mb-2">
                        <label>First Name</label>
                        <input type="text" class="form-control"
                            value="{{ Route::is('users.edit') ? $users->first_name : old('first_name') }}" name="first_name"
                            placeholder="Enter First Name" />
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Last Name</label>
                        <input type="text" class="form-control"
                            value="{{ Route::is('users.edit') ? $users->last_name : old('last_name') }}" name="last_name"
                            placeholder="Enter Last Name" />
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Mobile</label>
                        <input type="tel" class="form-control"
                            value="{{ Route::is('users.edit') ? $users->phone : old('phone') }}" name="phone"
                            placeholder="Enter Mobile No." />
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>User Name</label>
                        <input type="text" class="form-control"
                            value="{{ Route::is('users.edit') ? $users->client : old('client') }}" name="client"
                            placeholder="Enter User Name" />
                    </div>
                
                    <div class="col-md-6 mb-2">
                        <label>Password</label>
                        <input type="text" class="form-control" name="password" placeholder="Enter Password" />
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Confirm Password</label>
                        <input type="text" class="form-control" name="confirm_password"
                            placeholder="Enter Confirm Password" />
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Transaction Password to set</label>
                        <input type="text" class="form-control" name="transaction_password" placeholder="Enter Transaction Password" />
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Type</label>
                        <select name="type" id="" class="form-control">
                            <option value="" {{ Route::is('users.edit') && $users->type == '' ? 'selected' : '' }}>Select User Type</option>
                            <option value="0" {{ Route::is('users.edit') && $users->type == 0 ? 'selected' : '' }}>Broker</option>
                            <option value="1" {{ Route::is('users.edit') && $users->type == 1 ? 'selected' : '' }}>Office Staff</option>
                        </select>
                    </div>
                    
                    <h4 style="margin: 20px">Config :</h4>
                    <div class="col-md-6 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" id="flexCheckDefault" {{ Route::is('users.edit') && $users->status == 'active' ? 'checked' : '' }}>
                            <label class="form-check-label" for="flexCheckDefault">Account Status</label>
                        </div>                        
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>auto-Close all active trades when the losses reach % of Ledger-balance</label>
                        <input type="text" class="form-control" value="{{ Route::is('users.edit') ? $users->auto_close_limit : old('auto_close_limit', 90) }}"
                            name="auto_close_limit" id="auto_close_limit" />
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Notify client when the losses reach % of Ledger-balance</label>
                        <input type="text" class="form-control" value="{{ Route::is('users.edit') ? $users->notify_loss_limit : old('notify_loss_limit',70) }}"
                            name="notify_loss_limit" id="notify_loss_limit"  />
                    </div>
                    <!-- <div class="col-md-6 mb-2">
                        <label>Profit/Loss Share in %</label>
                        <input type="text" class="form-control" value="{{ Route::is('users.edit') ? $users->p_l_share : old('p_l_share',0) }}"
                            name="p_l_share" id="p_l_share" />
                    </div> -->
                    <div class="col-md-6 mb-2">
                        <label>Charge Per Lot</label>
                        <input type="text" class="form-control" value="{{ Route::is('users.edit') ? $users->charge_per_lot : old('charge_per_lot',20) }}"
                            name="charge_per_lot" id="charge_per_lot" />
                    </div>
                    
                    <!-- <div class="col-md-6 mb-2">
                        <label>Brokerage Share in %</label>
                        <input type="text" class="form-control" name="brokerage_share" value="{{ Route::is('users.edit') ? $users->brokerage_share : old('brokerage_share', 50) }}"/>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Trading Clients Limit</label>
                        <input type="text" name="trading_client_limit" class="form-control" value="{{ Route::is('users.edit') ? $users->trading_client_limit : old('trading_client_limit', 10) }}"/>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Sub Brokers Limit</label>
                        <input type="text" name="sub_broker_limit" class="form-control" value="{{ Route::is('users.edit') ? $users->sub_broker_limit : old('sub_broker_limit', 1) }}"/>
                    </div> -->
                    <h4 style="margin: 20px">Permissions :</h4>
                    <div class="col-md-6 mb-2">
                        <label>Sub Brokers Actions (Create, Edit)</label>
                        <select name="sub_broker_action" id="" class="form-control">
                            <option value="0" {{ Route::is('users.edit') && $users->sub_broker_action == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ Route::is('users.edit') && $users->sub_broker_action == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-2">
                        <label>Payout Allowed</label>
                        <select name="payout_allow" id="" class="form-control">
                            <option value="0" {{ Route::is('users.edit') && $users->payout_allow == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ Route::is('users.edit') && $users->payout_allow == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-2">
                        <label>Payin Allowed</label>
                        <select name="payin_allow" id="" class="form-control">
                            <option value="0" {{ Route::is('users.edit') && $users->payin_allow == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ Route::is('users.edit') && $users->payin_allow == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-2">
                        <label>Create Clients Allowed (Create, Update and Reset Password)</label>
                        <select name="create_client_allow" id="" class="form-control">
                            <option value="0" {{ Route::is('users.edit') && $users->create_client_allow == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ Route::is('users.edit') && $users->create_client_allow == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-2">
                        <label>Client Tasks Allowed (Account Reset, Recalculate brokerage etc.)</label>
                        <select name="client_task_allow" id="" class="form-control">
                            <option value="0" {{ Route::is('users.edit') && $users->client_task_allow == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ Route::is('users.edit') && $users->client_task_allow == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Trade Activity Allowed (Create, Update, Restore, Delete Trade)</label>
                        <select name="trade_activity_allow" id="" class="form-control">
                            <option value="0" {{ Route::is('users.edit') && $users->trade_activity_allow == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ Route::is('users.edit') && $users->trade_activity_allow == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Notifications Allowed</label>
                        <select name="notification_allow" id="" class="form-control">
                            <option value="0" {{ Route::is('users.edit') && $users->notification_allow == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ Route::is('users.edit') && $users->notification_allow == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    <h4 style="margin: 20px">MCX Futures :</h4>
                    <div class="col-md-6 mb-2">
                        <input class="form-check-input" type="checkbox" name="mcx_trade" id="flexCheckDefault" {{ Route::is('users.edit') && $users->mcx_trade ? 'checked' : '' }}>
                        <label class="form-check-label" for="flexCheckDefault">MCX Trading</label>
                    </div>                    
                    <!-- <div class="col-md-6 mb-2">
                        <label>Mcx Brokerage Type</label>
                        <select name="mcx_broker_type" id="" class="form-control">
                            <option value="0" {{ Route::is('users.edit') && $users->mcx_broker_type == 0 ? 'selected' : '' }}>Per Crore Basis</option>
                            <option value="1" {{ Route::is('users.edit') && $users->mcx_broker_type == 1 ? 'selected' : '' }}>Per Lot Basis</option>
                        </select>
                    </div> -->
                    <!-- <div class="col-md-6 mb-2">
                        <label>MCX Brokerage</label>
                        <input type="text" name="mcx_brokerage" class="form-control" value="{{ Route::is('users.edit') ? $users->mcx_brokerage : old('mcx_brokerage', '800') }}" placeholder="Enter Identification Number"/>
                    </div> -->
                    <div class="col-md-6 mb-2">
                        <label>Charge Per Lot MCX</label>
                        <input type="text" class="form-control" value="{{ Route::is('users.edit') ? $users->charge_per_lot_mcx : old('charge_per_lot_mcx',200) }}"
                            name="charge_per_lot_mcx" id="charge_per_lot_mcx" />
                    </div>
                    <!-- <div class="col-md-6 mb-2">
                        <label>Exposure Brokerage Type</label>
                        <select name="exposure_mcx_type" id="" class="form-control">
                            <option value="0" {{ Route::is('users.edit') && $users->exposure_mcx_type == 0 ? 'selected' : '' }}>Per Turnover Basis</option>
                            <option value="1" {{ Route::is('users.edit') && $users->exposure_mcx_type == 1 ? 'selected' : '' }}>Per Lot Basis</option>
                        </select>
                    </div> -->
                    <div class="col-md-6 mb-2">
                        <label>Intraday Exposure/Margin MCX</label>
                        <input type="text" name="intraday_margin_mcx" class="form-control" value="{{ Route::is('users.edit') ? $users->intraday_margin_mcx : old('intraday_margin_mcx', '500') }}" placeholder="Enter Identification Number"/>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Holding Exposure/Margin MCX</label>
                        <input type="text" name="holding_margin_mcx" class="form-control" value="{{ Route::is('users.edit') ? $users->holding_margin_mcx : old('holding_margin_mcx', '100') }}" placeholder="Enter Identification Number"/>
                    </div>
                    <h4 style="margin: 20px">Equity Futures : </h4>
                    <div class="col-md-6 mb-2">
                        <input class="form-check-input" type="checkbox" name="equity_trading" id="flexCheckDefault" {{ Route::is('users.edit') && $users->equity_trading ? 'checked' : '' }}>
                        <label class="form-check-label" for="flexCheckDefault">Equity Trading</label>
                    </div>                    
                    <!-- <div class="col-md-6 mb-2">
                        <label>Equity brokerage Per Crore</label>
                        <input type="text" name="equity_borkerage_per_crore" class="form-control" value="{{ Route::is('users.edit') ? $users->equity_borkerage_per_crore : old('equity_borkerage_per_crore', '800') }}" placeholder="Enter Identification Number"/>
                    </div> -->
                    <div class="col-md-6 mb-2">
                        <label>Intraday Exposure/Margin Equity</label>
                        <input type="text" name="intraday_margin_equity" class="form-control" value="{{ Route::is('users.edit') ? $users->intraday_margin_equity : old('intraday_margin_equity', '500') }}" placeholder="Enter Identification Number"/>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Holding Exposure/Margin Equity</label>
                        <input type="text" name="holding_margin_equity" class="form-control" value="{{ Route::is('users.edit') ? $users->holding_margin_equity : old('holding_margin_equity', '100') }}" placeholder="Enter Identification Number"/>
                    </div>
                    <div class="col-md-6 text-right offset-6 row">
                        <div class="col  d-grid gap-2">
                            <button type="reset" class="btn btn-light">Reset</button>
                        </div>
                        <div class="col  d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection