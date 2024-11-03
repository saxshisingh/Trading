@extends('layout.app')
@section('title', '')
@section('pagetitle', 'Closed Trades')
@section('content')
<div class="container-fluid">
<div class="container-fluid">
    <form>
    <div class="row">
        <div class="col-sm-3 mt-4">
            <h6>TRADE AFTER</h6>
            <input type="date" class="form-control" name="after">
        </div>
        <div class="col-sm-3 mt-4">
            <h6>TRADE BEFORE</h6>
            <input type="date" class="form-control" name="before">
        </div>
        
        <div class="col-sm-3 mt-4">
            <h6>SELECT MARKET</h6>
            <select class="form-control" name="segment_id" id="segment-dropdown">
                @foreach ($segment as $seg)
                    <option value="{{ $seg->id }}">{{ $seg->name }}</option>
                @endforeach 
            </select>
        </div>
        <div class="col-sm-3 mt-4">
            <h6>Search Name</h6>
            <input type="text" class="form-control" name="name">
        </div>

        <!-- <div class="col-sm-3 mt-4">
            <h6>EXPIRY</h6>
                <select class="form-control" name="expiry_date_id" id="expiry-dropdown">
                    <option value=""></option>
                </select>
        </div> -->
        
        
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
            @foreach ($organizedTrades as $item)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $item->id }}</td>
                <td>{{ $item->segment->name }}</td>
                <td>{{ $item->script->name }}</td>
                <td>{{$item->expiry->tradingsymbol}}</td>
                <td>{{ $item->user->first_name }} {{ $item->user->last_name }}</td>
                <td>{{ $item->buy_rate }}</td>
                <td>{{ $item->sell_rate }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->wallet ? number_format($item->wallet->profit_loss, 2) : '0' }}</td>
                <td>{{ $item->created_at }}</td>
                <td>{{ $item->buy_at?$item->buy_at:"-" }}</td>
                <td>{{ $item->sell_at?$item->sell_at:"-" }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {!! $organizedTrades->appends(\Request::all())->links() !!}
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