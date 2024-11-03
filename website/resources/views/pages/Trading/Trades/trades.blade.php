@extends('layout.app')
@section('title', 'Trades')
@section('content')
<div class="container-fluid">
    @if (old('error'))
            <div class="alert alert-danger">{{ old('error') }}</div>
        @endif
        @if (old('success'))
            <div class="alert alert-success">{{ old('success') }}</div>
        @endif
<div class="container-fluid">
    <form action="" method="GET">
    <div class="row">
        <div class="col-sm-2">
            <label class="check"><input type="checkbox" class="form-check-input" name="pending" value="2"> Pending Orders</label><br>
            <label class="check"><input type="checkbox" class="form-check-input" name="executes" value="1"> Executed Orders</label>
        </div>
        <div class="col-sm-2">
            <label class="col-form-label">TRADE AFTER</label>
            <input type="date" class="form-control" name="after">
        </div>
        <div class="col-sm-2">
            <label class="col-form-label">TRADE BEFORE</label>
            <input type="date" class="form-control" name="before">
        </div>
        
        <div class="col-sm-2">
            <label class="col-form-label">SELECT MARKET</label>
            <select class="form-control" name="segment_id" id="segment-dropdown">
                <option value="">Select Market</option>
                @foreach ($segment as $seg)
                    <option value="{{ $seg->id }}">{{ $seg->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <label class="col-form-label">SELECT SCRIPT</label>
            <select class="form-control" name="script_id" id="script-dropdown">
                <option value="">Select...</option>
            </select>
        </div>
        
        <div class="col-sm-2">
            <label class="col-form-label">SELECT ORDER TYPE</label>
            <select class="form-control" name="action">
                <option value="">Select Order Type</option>
                <option value="SELL">Sell</option>
                <option value="BUY">Buy</option>

            </select>
        </div>
    </div>
</div>
<div class="container-fluid" style="margin-top: 25px">
    <div class="row" style="padding: 20px">
        <div class="col-sm-2">
            <button class="btn btn-light btn-sm"  type="submit">FIND ORDERS</button>
        </div>
        <div class="col-sm-2">
            <button class="btn btn-secondary btn-sm"  type="reset">CLEAR FILTER</button>
        </div>
    </div>
</div>
</form>
<div class="container-fluid" style="padding: 30px">
    <div class="row">
            <div class="col-md-2">
            @include('partials.page_select')
            </div>
        
        
        <div class="col-sm-6"></div>
        <div class="col-sm-4">
            <form>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Search</span>
                    </div>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search trades" aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        {{-- <button class="btn btn-primary" type="submit">Search</button> --}}
                    </div>
                </div>
        </form>
        </div>
        
    </div>
</div>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>D</th>
                <th>TIME</th>
                <th>MARKET</th>
                <th>SCRIPT</th>
                <th>B/S</th>
                <th>LOT</th>
                <th>QTY</th>
                <th>ORDER PRICE</th>
                <th>STATUS</th>
                <th>O. TIME</th>
                <th>MODIFY</th>
                <th>CANCEL</th>
            </tr>
        </thead>
        <tbody>	
            
            @if($trade->isEmpty())
            <tr>
                <td colspan="13">No data available in table</td>
            </tr>
        @else
                @foreach ($trade as $t)
                @php
                // Extract and format the expiry date
                    $expiryDate = \Carbon\Carbon::parse($t->expiry->expiry_date);
                    $formattedExpiryDate = strtoupper($expiryDate->format('dMY'));
                    @endphp
                    <tr class="{{ $t->action == 'SELL' ? 'tr-primary' : 'tr-danger' }}">
                    <td>{{ $loop->index+1 }}</td>
                    <td>{{$t->created_at}}</td>
                    <td>{{ $t->segment->name}}{{ $t->segment->instrument_type}}</td>
                    <td>{{ $t->script->name}} {{ $formattedExpiryDate }}</td>
                    <td>
                        @if($t->action == 'BUY')
                            SELL
                        @else
                            BUY
                        @endif
                    </td>
                    <td>{{ $t->lot}}</td>
                    <td>{{ $t->qty}}</td>
                    <td>
                        @if($t->action == 'BUY')
                        {{$t->sell_rate}}
                        @else
                        {{$t->buy_rate}}
                        @endif
                    </td>
                    <td>
                        @if($t->status=='1'||$t->status=='0')
                            EXECUTED
                        @else 
                        PENDING
                            @endif
                            <td>
                                @if($t->action == 'BUY')
                                {{$t->sell_at}}
                                @else
                                {{$t->buy_at}}
                                @endif
                            </td>
                    <td>NA</td>
                    <td>NA</td>

                   {{-- <td>
                        <form action="{{ route('trades.destroy', $t->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="bi bi-x"></i>
                        </button>
                    </form></td> --}}
                </tr>
                <tr class="{{ $t->action == 'SELL' ? 'tr-danger' : 'tr-primary' }}">
                    <td>{{ $loop->index+1 }}</td>
                    <td>{{$t->created_at}}</td>
                    <td>{{ $t->segment->name}}{{ $t->segment->instrument_type}}</td>
                    <td>{{ $t->script->name}} {{ $formattedExpiryDate }}</td>
                    <td>EXIT</td>
                    {{-- <td>
                        @if($t->action == 'BUY')
                        {{$t->buy_rate}}
                        @else
                        {{$t->sell_rate}}
                        @endif
                    </td> --}}
                    <td>{{ $t->lot}}</td>
                    <td>{{ $t->qty}}</td>
                    <td>
                        @if($t->action == 'BUY')
                        {{$t->buy_rate}}
                        @else
                        {{$t->sell_rate}}
                        @endif
                    </td>
                    <td>
                        @if($t->status=='1'||$t->status=='0')
                            EXECUTED
                        @else 
                        PENDING
                            @endif
                    <td>
                        @if($t->action == 'BUY')
                        {{$t->buy_at}}
                        @else
                        {{$t->sell_at}}
                        @endif
                    </td>
                    <td>NA</td>
                    <td>NA</td>

                   {{-- <td>
                        <form action="{{ route('trades.destroy', $t->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="bi bi-x"></i>
                        </button>
                    </form></td> --}}
                </tr>
                @endforeach
                @endif
        </tbody>
    </table>
    {{-- {!! $trade->appends(\Request::all())->links() !!} --}}
    {{-- <div class="dataTables_info" id="load_data_info" role="status" aria-live="polite">Showing 0 to 0 of 0 entries</div> --}}
</div>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        
            $('#segment-dropdown').on('change', function() {
                changescript(this.value);
            });
        });

        function changescript(value) {
            console.log("script =", value);
            var idSegment = value;
            $("#script-dropdown").html('');
            $.ajax({
                url: "{{ url('/fetch_script') }}",
                type: "POST",
                data: {
                    segment_id: idSegment,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(res) {
                    $('#script-dropdown').html('<option value=""> Select Script</option>');
                    $.each(res.script, function(key, value) {
                        $("#script-dropdown").append('<option value="' + value
                            .id + '" ' + ($("#script-dropdown").attr('data-value') == value.id ?
                                "selected" : "") + '>' + value.name + '</option>');
                    });   
                    console.log("fetch script = ", res);                
                }
            });
        }
</script>
@endsection