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
        <button class="btn btn-success mb-4">Create Pending Orders</button>
        <div class="col-md-12">
            <div class="card info-card sales-card">
                <div class="card-body">
                    
                    
                    <h6>No Pending Orders</h6>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
