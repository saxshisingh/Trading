@extends('layout.app')
@section('title', '')
@section('pagetitle', 'Trade Funds')
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
    <div class="row">
        <div class="col-sm-5 mt-1">
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

        <div class="col-sm-5 mt-4">
            <h6>USER</h6>
            <select class="form-control" name="user_id" id="script-dropdown">
            
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}">{{ $u->first_name }} {{ $u->last_name }}</option>
                    @endforeach
             
            </select>
        </div>

        <div class="col-sm-3 mt-4">
            <h6>AMOUNT</h6>
            <input type="text" class="form-control" name="amount">
        </div>

        <div class="col-sm-5 mt-4">
            <button type="submit" class="btn btn-success">Search</button>
        </div>
        
    </div>
</div>

</form>
<div class="row">
    <div class="col-md-10" style="padding: 20px">
        <form action="{{route('trader_funds.create')}}" method="GET">
            <button type="submit" class="btn btn-success">Create Funds WD</button>
        </form>
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
                <th>USERNAME</th>
                <th>NAME</th>
                <th>AMOUNT</th>
                <th>TXN TYPE</th>
                <th>NOTES</th>
                <th>CREATED AT</th>
              
            </tr>
        </thead>
        <tbody>			
                @foreach ($wallet as $t)
                <tr>
                    <td>{{ $loop->index+1 }}</td>
                    <td>{{ $t->id}}</td>
                    <td>{{ $t->user->client}}</td>
                    <td>{{ $t->user->first_name}} {{ $t->user->last_name}}</td>
                    <td>{{ $t->amount}}</td>
                    <td>{{ $t->type}}</td>
                    <td>{{ $t->remarks}}</td>
                    <td>{{ $t->created_at}}</td>                    
                    
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