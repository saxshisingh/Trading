@extends('layout.app')
@section('title', 'Rejection Log')
@section('content')
<div class="container-fluid">
    <form action="" method="GET">
        @csrf
    <div class="row">    
        <div class="col-sm-2">
            <h6>SEGMENT</h6>
            <select class="form-control" name="segment_id" id="segment-dropdown">
                <option value="">Select Market</option>
                @foreach ($segment as $seg)
                    <option value="{{ $seg->id }}">{{ $seg->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <h6>SELECT SCRIPT</h6>
            <select class="form-control" name="script_id" id="script-dropdown">
                <option value=""></option>
            </select>
        </div>
        
        <div class="col-sm-2">
            <h6>FROM DATE</h6>
            <input type="date" class="form-control" name="after">
        </div>
        <div class="col-sm-2">
            <h6>TO DATE</h6>
            <input type="date" class="form-control" name="before">
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
        
        <div class="col-sm-6"></div>
        <div class="col-sm-4">
            <!-- Search bar -->
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
    <table class="table">
        <thead>
            <tr>
                <th>ACTION</th>
                <th>CLIENT</th>
                <th>DATE</th>
                <th>SCRIPT</th>
                <th>TYPE</th>
                <th>LOT</th>
                <th>QTY</th>
                <th>RATE</th>
                <th>REJECTION REASON</th>
            </tr>
        </thead>
        <tbody>				
			@foreach ($trade as $t)
                <tr>
                   <td>{{$t->action}}</td>
                    <td>{{ $t->user->client }}</td>
                    <td>{{ $t->order->exchange_timestamp}}</td>
                    <td>{{ $t->order->transaction_type}}</td>
                    <td>{{ $t->lot}}</td>
                    <td>{{ $t->order->quantity}}</td>
                    <td>{{ $t->order->price}}</td>
                    <td>{{$t->order->status_message}}</td>
                </tr>
                @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col-sm-7">

        </div>
        <div class="col-sm-3">

            {!! $trade->appends(\Request::all())->links() !!}   
        </div>
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