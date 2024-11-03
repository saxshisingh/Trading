@extends('layout.app')
@section('title', '')
@section('pagetitle', 'Trade Funds')
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
        <form action="{{route('trader_funds.store')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-sm-4 mt-4">
                    <label>User Id</label>
                    <select name="user_id" class="form-control">
                        <option value="">Select User Id</option>
                        @foreach ($user as $item)
                            <option value="{{$item->id}}">{{$item->first_name}} {{$item->last_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 mt-4">
                    <label>Notes</label>
                    <input type="text" class="form-control" name="remarks">
                </div>

                <div class="col-sm-4 mt-4">
                    <label>Funds</label>
                    <input type="text" class="form-control" name="amount">
                </div>
        
                <div class="col-sm-4 mt-4">
                    <label>Type</label>
                    <select name="type" id="" class="form-control">
                        <option value="credit">Credit</option>
                        <option value="debit">Debit</option>
                    </select>
                </div>

                <div class="col-sm-4 mt-4">
                    <label>Transaction Password</label>
                    <input type="password" name="transaction_password" class="form-control">
                </div>

                <div class="col-sm-4 mt-4">
                    <button type="submit" class="btn btn-info mt-4">Save</button>
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