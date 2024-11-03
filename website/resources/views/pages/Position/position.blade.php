@extends('layout.app')
@section('title', '')
@section('pagetitle', 'Positions')
@section('content')
<div class="row">
<div class="container-fluid">

    @if (old('error'))
        <div class="alert alert-danger">{{ old('error') }}</div>
    @endif
    @if (old('success'))
        <div class="alert alert-success">{{ old('success') }}</div>
    @endif
    <div class="col-md-12">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Filters <span>| Search</span></h5>
                <form action="" method="get">
                    @csrf
                    <div class="row mt-2">
                        <div class="col-md-6 mb-2">
                            <label>Segment</label>
                            <select class="form-control" name="segment_id" value="{{$request->segment_id}}" id="segment-dropdown">
                                @foreach ($segment as $seg)
                                    <option value="{{ $seg->id }}">{{ $seg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Script</label>
                            <select class="form-control" value="{{ $request->script_id }}" name="script_id" id="script-dropdown">
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
                            <label>Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1">Open</option>
                                <option value="0">Closed</option>
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
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Market</th>
                                <th>Script</th>
                                <th>Expiry Date</th>
                                <th>Quantity</th>
                                <th>Type</th>
                                <th>Status</th>

                                <th colspan="2">Action</th>
                            </tr>
                            @foreach ($position as $u)
                                <tr>
                                    
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $u->user->client }}</td>
                                    <td>{{ $u->user->first_name }} {{ $u->user->last_name }}</td>
                                    <td>{{ $u->segment->name }}</td>
                                    <td>{{ $u->script->name }}</td>
                                    <td>{{ $u->expiry->expiry_date }}</td>
                                    <td>{{ $u->quantity }}</td>
                                    <td>{{ $u->position_type }}</td>
                                    <td>{{ $u->status }}</td>

                                    <td>
                                            <a href="{{ route('position.edit', $u->id) }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Edit" class="btn btn-secondary btn-sm"><i
                                                    class="bi bi-pencil"></i></a>
                                              
                                                    <a href="{{ route('position.show', $u->id) }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                                        data-bs-title="Show Profile" class="btn btn-warning btn-sm"><i
                                                            class="bi bi-eye"></i></a>
                                                       
                                    </td>
                                        <td>
                                            <form action="{{ url('toggleActive', $u->id) }}" method="post">

                                                @csrf
                                                @if ($u->status == "open")
                                                    <button data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-custom-class="custom-tooltip" data-bs-title="Close"
                                                        class="btn btn-danger btn-sm"><i class="bi bi-x"></i></button>
                                                @else
                                                    <button data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-custom-class="custom-tooltip" data-bs-title="Open"
                                                        class="btn btn-success btn-sm"><i class="bi bi-check"></i></button>
                                                @endif
                                            </form>
                                        </td>
                                </tr>
                            @endforeach
                        </thead>
                    </table>
                    {!! $position->appends(\Request::all())->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection