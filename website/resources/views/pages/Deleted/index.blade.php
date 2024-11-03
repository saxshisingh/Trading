@extends('layout.app')
@section('title', '')
@section('pagetitle', 'Deleted Trades')
@section('content')
<div class="container-fluid">
<div class="container-fluid">
    <form>
    <div class="row">
        
        <div class="col-sm-3 mt-1">
            <h6>USERNAME</h6>
            <select class="form-control" name="username">
                {{-- @foreach ($order as $seg)
                    <option value="{{ $seg->id }}">{{ $seg->type }}</option>
                @endforeach --}}
            </select>
        </div>

        <div class="col-sm-1 mt-4">
            <button class="btn btn-success" type="submit">SEARCH</button>
        </div>
        <div class="col-sm-1 mt-4">
            <button class="btn btn-success" type="reset">RESET</button>
        </div>
        
    </div>
</div>

</form>
<div class="row">
    <div class="col-md-10" style="padding: 20px">

    </div>
    <div class="col-md-2">
    @include('partials.page_select')
    </div>
</div>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>D</th>
                <th>ID</th>
                <th>SEGMENT</th>
                <th>SCRIPT</th>
                <th>SYMBOL</th>
                <th>USER ID</th>
                <th>BUY RATE</th>
                <th>SELL RATE</th>
                <th>Lots/Units</th>
                <th>Profit/Loss</th>
                <th>TIME</th>
                <th>BROUGHT AT</th>
                <th>SOLD AT</th>
               
            </tr>
        </thead>
        <tbody>			
            @foreach ($trades as $item)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $item->id }}</td>
                <td>{{ $item->segment->name }}</td>
                <td>{{ $item->script->name }}</td>
                <td>{{ $item->expiry->tradingsymbol}}</td>
                <td>{{ $item->user->id }}</td>
                <td>{{ $item->buy_rate?$item->buy_rate:'-' }}</td>
                <td>{{ $item->sell_rate?$item->sell_rate:'-' }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ number_format($item->wallet->profit_loss,2) }}</td>
                <td>{{ $item->created_at }}</td>
                <td>{{ $item->buy_at?$item->buy_at:'-' }}</td>
                <td>{{ $item->sell_at?$item->sell_at:'-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="dataTables_info" id="load_data_info" role="status" aria-live="polite">Showing 0 to 0 of 0 entries</div>
</div>

</div>
@endsection

