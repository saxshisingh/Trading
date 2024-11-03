@extends('layout.app')
@section('title', '')
@section('pagetitle', '')
@section('content')
<div class="row">
    <div class="container-fluid">
    
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="col-md-12">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <div class="card-header">
                        <h4 class="card-title">Change Transaction Password</h4>
                    </div>
                    
                    <form action="{{ route('transaction_password.update', auth()->user()->id) }}" method="POST">
                        @csrf
                      @method('PUT')
                        <div class="col-lg mb-4">
                            <label for="password" class="form-control mb-2">Old Password</label>
                            <input type="password" name="trans_password" class="form-control" required>
                        </div>
                        <div class="col-lg mb-4 mt-2">
                            <label for="new_password" class="form-control mb-2">New Password</label>
                            <input type="password" name="new_trans_password" class="form-control" required>
                        </div>
                        <div class="col-lg mb-4">
                            <label for="confirm_password" class="form-control mb-2">Repeat New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-info">Update</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
