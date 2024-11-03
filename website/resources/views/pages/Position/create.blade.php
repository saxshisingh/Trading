@extends('layout.app')
@section('title', '')
@section('pagetitle', 'Create Position')

@section('button')
    <div class="text-end">
        <a href="{{ route('position.index') }}" class="btn btn-primary btn-sm" style="padding: 10px">View Positions</a>
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
    <form action="{{ route('position.store') }}" method="post" enctype="multipart/form-data" >
        @csrf
        <div class="card info-card sales-card">
            <div class="card-body">
                <div class="row mt-2">
                    @if (Route::is('position.edit'))
                        <input type="hidden" name="id" value="{{ $position->id }}" />
                    @endif
                    <div class="col-md-6 mb-2">
                        <label>User ID</label>
                        <select class="form-control" name="user_id" value="{{ Route::is('position.edit') ? $position->user_id : old('user_id') }}" >
                            @foreach ($user as $seg)
                                <option value="{{ $seg->id }}">{{ $seg->client }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Segment</label>
                        <select class="form-control" name="segment_id" value="{{ Route::is('position.edit') ? $position->segment_id : old('segment_id') }}" id="segment-dropdown">
                            @foreach ($segment as $seg)
                                <option value="{{ $seg->id }}">{{ $seg->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Script</label>
                        <select class="form-control" value="{{ Route::is('position.edit') ? $position->script_id : old('script_id') }}" name="script_id" id="script-dropdown">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Quantity</label>
                        <input type="text" class="form-control"
                            value="{{ Route::is('position.edit') ? $position->client : old('quantity') }}" name="quantity"
                            placeholder="Enter Quantity" />
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Entry Price</label>
                        <input type="number" class="form-control" value="{{ Route::is('position.edit') ? $position->entry_price : old('entry_price') }}"
                            name="entry_price" placeholder="Enter Entry Price" />
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Expiry Date</label>
                        <select class="form-control" name="expiry_date_id" id="expiry-dropdown" value="{{ Route::is('position.edit') ? $position->dob : old('dob') }}">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Type</label>
                        <select name="position_type" class="form-control" id="">
                            <option value="long">Long Period</option>
                            <option value="short">Short Period</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Transaction Fee</label>
                        <input type="number" class="form-control" value="{{ Route::is('position.edit') ? $position->transaction_fee : old('transaction_fee') }}"
                            name="transaction_fee" placeholder="Enter Transaction Fee" />
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Target Price</label>
                        <input type="number" class="form-control" value="{{ Route::is('position.edit') ? $position->target_price : old('target_price') }}"
                            name="target_price" placeholder="Enter Transaction Fee" />
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Broker</label>
                        <input type="text" class="form-control" value="{{ Route::is('position.edit') ? $position->broker : old('broker') }}"
                            name="broker" placeholder="Enter Broker(if any)" />
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