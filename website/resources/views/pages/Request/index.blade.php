@extends('layout.app')
@section('title', '')
@section('pagetitle', 'Trade Request')
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
            <h6>SELECT SCRIPT</h6>
            <select class="form-control" name="script_id" id="script-dropdown">
                <option value=""></option>
            </select>
        </div>

        <div class="col-sm-3 mt-4">
            <h6>EXPIRY</h6>
                <select class="form-control" name="expiry_date_id" id="expiry-dropdown">
                    <option value=""></option>
                </select>
        </div>
        
        <div class="col-sm-3 mt-4">
            <h6>SELECT ORDER TYPE</h6>
            <select class="form-control" name="order_id">
                {{-- @foreach ($order as $seg)
                    <option value="{{ $seg->id }}">{{ $seg->type }}</option>
                @endforeach --}}
            </select>
        </div>
        <div class="col-sm-3 mt-4">
            <h6>STATUS</h6>
            <select class="form-control" name="status">
                <option value="">--All--</option>
                <option value="2">Pending</option>
                <option value="1">Approved</option>
                <option value="0">Rejected</option>
            </select>
        </div>
    </div>
</div>
<div class="container-fluid" style="margin-top: 50px">
    <div class="row" style="padding: 20px">
        <div class="col-sm-3">
            <button class="btn btn-light btn-sm" style="padding: 10px;" type="submit">FIND ORDERS</button>
        </div>
        <div class="col-sm-3">
            <button class="btn btn-secondary btn-sm" style="padding: 10px;" type="reset">CLEAR FILTER</button>
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
                <th>TIME</th>
                <th>MARKET</th>
                <th>SCRIPT</th>
                <th>B/S</th>
                <th>ORDER TYPE</th>
                <th>LOT</th>
                <th>QTY</th>
                <th>ORDER PRICE</th>
                <th>STATUS</th>
                <th>MODIFY</th>
                <th>ACTION</th>
                <th>CANCEL</th>
            </tr>
        </thead>
        <tbody>			
                {{-- @foreach ($trade as $t)
                <tr>
                    <td>{{ $loop->index+1 }}</td>
                    <td>{{ $t->created_at->format('H:i:s') }}</td>
                    <td>{{ $t->segment->name}}</td>
                    <td>{{ $t->script->name}}</td>
                    <td>{{ $t->type}}</td>
                    <td>{{ $t->order->type}}</td>
                    <td>{{ $t->lot}}</td>
                    <td>{{ $t->qty}}</td>
                    <td>{{ $t->order_price}}</td>
                    <td>
                        @if($t->status=='0')
                        <span class="badge bg-danger">FAILED</span>
                        @else 
                            @if($t->status=='1')
                            <span class="badge bg-success">SUCCESS</span>
                            @else
                            <span class="badge bg-warning">PENDING</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('tradeRequest.edit',$t->id) }}" data-bs-toggle="tooltip"
                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                            data-bs-title="Edit" class="btn btn-secondary btn-sm"><i
                                class="bi bi-pencil"></i></a>
                                   
                </td>
                    <td>
                        <form action="{{ url('toggleActiveTrade', $t->id) }}" method="post">
                            @csrf
                            @if ($t->status == 2)
                                <button data-bs-toggle="tooltip" data-bs-placement="top" type="button"
                                    data-bs-custom-class="custom-tooltip" data-bs-title="Reject" onclick="return confirm('Are you sure you want to reject this trade request?')"
                                    onclick="toggleReasonTextBox({{ $t->id }})" class="btn btn-danger btn-sm"><i class="bi bi-x"></i></button>
                                <button data-bs-toggle="tooltip" data-bs-placement="top" type="submit"
                                    data-bs-custom-class="custom-tooltip" data-bs-title="Approve" value="1" name="status" onclick="return confirm('Are you sure you want to approve this trade request?')"
                                    class="btn btn-success btn-sm"><i class="bi bi-check"></i></button>

                                    <div id="textbox-{{ $t->id }}" style="display: none;margin-top:10px;">
                                        <textarea id="remark-{{ $t->id }}" name="remark" class="form-control mb-2" placeholder="Enter reason for Rejecting"></textarea>
                                        <button class="btn btn-primary btn-sm mt-2" value="0" name="status" type="submit">Submit</button>
                                    </div>
                                    
                                </form>
                            @else
                                <h6>Remark: {{$t->remark?$t->remark:'-'}}</h6>

                            @endif
                        </form>
                        
                    </td>
                    <td>
                        <form action="{{ route('tradeRequest.destroy', $t->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-dark btn-sm">
                                <i class="bi bi-x"></i>
                            </button>
                        </form>
                    </td>>
                </tr>
                @endforeach --}}
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