@extends('layout.app')
@section('title', '')
@section('pagetitle', 'Lot Details')
@section('content')
<div class="row">
    <div class="container-fluid">
        @if (old('error'))
            <div class="alert alert-danger">{{ old('error') }}</div>
        @endif
        @if (old('success'))
            <div class="alert alert-success">{{ old('success') }}</div>
        @endif
        {{-- <form action="{{route('lotdetail.store')}}" method="POST">
            @csrf
            <button type="submit">add logs</button>
        </form> --}}
        <div class="col-md-12">
            <div class="card info-card sales-card">
                <div class="card-body">
                    {{-- <form action="{{route('lotdetail.store')}}" method="POST">
                        @csrf
                        <button type="submit">add lot data</button>
                    </form> --}}
                    <h5 class="card-title">Filters <span>| Search</span></h5>
                    <form action="" method="get">
                        <div class="row mt-2">
                            <div class="col-md-6 mb-2">
                                <label>Market</label>
                                <select class="form-control" name="segment_id" id="segment-dropdown">
                                    <option value="">All</option>
                                    @foreach ($segment as $seg)
                                        <option value="{{ $seg->id }}">{{ $seg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Script</label>
                                <select class="form-control" name="script_id" id="script-dropdown">
                                    <option value=""></option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 d-flex align-items-center">
                                <label></label>
                                <div class="d-flex flex-column flex-md-row w-100 gap-2">
                                    <button type="submit" class="btn btn-sm btn-primary w-100">Search</button>
                                    {{-- <a class="btn btn-sm btn-success w-100" href="{{ route('member.export', \Request::all()) }}">Export CSV</a> --}}
                                </div>
                                <label></label>
                            </div>
                             <div class="col-md-4 d-grid gap-2">
                             
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
                    <div class="table-responsive text-center">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Market</th>
                                    <th>Script</th>
                                    <th>LOT</th>
                                    <th>Quantity</th>
    
                                    <th colspan="2">Action</th>
                                </tr>
                                @foreach ($lotDetails as $u)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $u->segment->name }}</td>
                                        <td>{{ $u->script->name }}</td>
                                        <td>1</td>
                                        <td>{{$u->lot_quantity}}</td>
                                        <td>
                                                <a href="{{ route('lotdetail.edit',  $u->id) }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Edit" class="btn btn-secondary btn-sm"><i
                                                        class="bi bi-pencil"></i></a>
                                                           
                                        </td>
                                    </tr>
                                @endforeach
                            </thead>
                        </table>
                        {!! $lotDetails->appends(\Request::all())->links() !!}
                    </div>
                </div>
            </div>
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