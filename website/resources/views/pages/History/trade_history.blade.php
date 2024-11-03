@extends('layout.app')
@section('title', '')
@section('pagetitle', 'Trade History')
@section('content')
<div class="container-fluid">
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
    <form>
        <div class="row" style="margin-bottom: 20px">
            <div class="col-sm-3 mt-1">
                <h6>FROM DATE</h6>
                <input type="date" class="form-control" name="after">
            </div>
            <div class="col-sm-3 mt-1">
                <h6>TO DATE</h6>
                <input type="date" class="form-control" name="before">
            </div>
            
            {{-- <div class="col-sm-6 mt-4">
                <button type="submit" class="btn btn-info">Download Funds Request</button>
            </div> --}}
    
            <div class="col-sm-3 mt-1">
                <h6>USER</h6>
                <select class="form-control" name="user_id" id="script-dropdown">
                
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}">{{ $u->first_name }} {{ $u->last_name }}</option>
                        @endforeach
                 
                </select>
            </div>
    
            <div class="col-sm-2 mt-4">
                <button type="submit" class="btn btn-success">Search</button>
            </div>
            <div class="col-sm-1 mt-4">
                <a class="btn btn-info" 
                href="{{ route('trade_history.create', ['after' => request('after'), 'before' => request('before'), 'user_id' => request('user_id')]) }}">
                    CSV
                </a>
            </div>
        </div>
</div>

</form>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>D</th>
                <th>ID</th>
                <th>USERNAME</th>
                <th>NAME</th>
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
                @foreach ($trades as $t)
                @php
                // Extract and format the expiry date
                    $expiryDate = \Carbon\Carbon::parse($t->expiry->expiry_date);
                    $formattedExpiryDate = strtoupper($expiryDate->format('dMY'));
                @endphp
                <tr class="{{ $t->action == 'SELL' ? 'tr-primary' : 'tr-danger' }}">
                    <td>{{ $loop->index+1 }}</td>
                    <td>{{ $t->id}}</td>
                    <td>{{ $t->user->client}}</td>
                    <td>{{ $t->user->first_name}} {{ $t->user->last_name}}</td>
                    <td>{{$t->created_at}}</td>
                    <td>{{ $t->segment?$t->segment->name:''}}{{ $t->segment?$t->segment->instrument_type:''}}</td>
                    <td>{{ $t->script?$t->script->name:''}} {{ $formattedExpiryDate }}</td>
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
                    
                </tr>
                <tr class="{{ $t->action == 'SELL' ? 'tr-danger' : 'tr-primary' }}">
                    <td>{{ $loop->index+2 }}</td>
                    <td>{{ $t->id}}</td>
                    <td>{{ $t->user->client}}</td>
                    <td>{{ $t->user->first_name}} {{ $t->user->last_name}}</td>
                    <td>{{$t->created_at}}</td>
                    <td>{{ $t->segment?$t->segment->name:''}}{{ $t->segment?$t->segment->instrument_type:''}}</td>
                    <td>{{ $t->script?$t->script->name:''}} {{ $formattedExpiryDate }}</td>
                    <td>
                        @if($t->action == 'BUY')
                            BUY
                        @else
                            SELL
                        @endif
                    </td>
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
                    
                </tr>
                @endforeach
        </tbody>
    </table>
    <div class="dataTables_info" id="load_data_info" role="status" aria-live="polite">Showing 0 to 0 of 0 entries</div>
</div>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        
            $('#segment-dropdown').on('change', function() {
                changescript(this.value);
            });
            $('#script-dropdown').on('change', function() {
            changeexpiry(this.value);
            });
        });

        function toggleReasonTextBox(tradeId) {
        var textBox = document.getElementById('textbox-' + tradeId);
        textBox.style.display = textBox.style.display === 'none' ? 'block' : 'none';
        
    }



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

        function changeexpiry(value) {
            console.log("expiry = ", value);
            var idScript = value;
            $("#expiry-dropdown").html('');
            $.ajax({
                url: "{{ url('/fetch_expiry_date') }}",
                type: "POST",
                data: {
                    script_id: idScript,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(res) {
                    $('#expiry-dropdown').html('<option value=""> Select Expiry Date </option>');
                    $.each(res.date, function(key, value) {
                        $("#expiry-dropdown").append('<option value="' + value
                            .id + '" ' + ($("#expiry-dropdown").attr('data-value') == value.id ?
                                "selected" : "") + '>' + value.expiry_date + '</option>');
                    });
                    console.log("fetch expiry = ", res);
                }
            });
        }

</script>
@endsection