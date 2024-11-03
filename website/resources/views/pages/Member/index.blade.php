@extends('layout.app')
@section('title', 'Welcome')
@section('pagetitle', 'Users')
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
                    <div class="row mt-2">
                        <div class="col-md-6 mb-2">
                            <label>Name</label>
                            <input type="search" name="name" value="{{ $request->name }}" class="form-control"
                                placeholder="Search By Name" />
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Mobile</label>
                            <input type="search" name="phone" value="{{ $request->phone }}" class="form-control"
                                placeholder="Search By Mobile" />
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Email</label>
                            <input type="search" name="email" value="{{ $request->email }}" class="form-control"
                                placeholder="Search By email" />
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1">Activated</option>
                                <option value="0">Deactivated</option>
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
                                <th>User Name</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Balance</th>
                                <th>Status</th>

                                <th colspan="2">Action</th>
                            </tr>
                        </thead>
                            @foreach ($users as $u)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $u->client }}</td>
                                    <td>{{ $u->first_name }} {{ $u->last_name }}</td>
                                    <td>{{ $u->phone }}</td>
                                    <td>{{ $u->credit - $u->debit }}</td>
                                    <td>
                                        @if ($u->status == '1')
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-danger">Deactive</span>
                                        @endif
                                    </td>
                                    <td>
                                            <a href="{{ route('users.edit', ['user' => $u->id]) }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Edit" class="btn btn-secondary btn-sm"><i
                                                    class="bi bi-pencil"></i></a>
                                              
                                                   
                                    </td>
                                        <td>
                                            <form action="{{ url('toggleActiveUser', $u->id) }}" method="post">

                                                @csrf
                                                @if ($u->status == 1)
                                                    <button data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-custom-class="custom-tooltip" data-bs-title="Deactivate"
                                                        class="btn btn-danger btn-sm"><i class="bi bi-x"></i></button>
                                                @else
                                                    <button data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-custom-class="custom-tooltip" data-bs-title="Activate"
                                                        class="btn btn-success btn-sm"><i class="bi bi-check"></i></button>
                                                @endif
                                            </form>
                                        </td>
                                </tr>
                            @endforeach
                    </table>
                    {!! $users->appends(\Request::all())->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection