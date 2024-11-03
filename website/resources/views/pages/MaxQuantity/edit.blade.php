@extends('layout.app')
@section('title', '')
@section('pagetitle', 'Edit Max Quantity')
@section('content')
<div class="container-fluid">
    

    <div class="card info-card sales-card">
        <div class="card-body">
            <form action="{{route('quantity.update', $lot->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-4">
                        <label for="segment_id">Market</label>
                        <input type="text" class="form-control" value="{{$lot->segment->name}}" readonly>
                    </div>
                    <div class="col-4">
                        <label for="script_id">Script</label>
                        <input type="text" class="form-control" value="{{$lot->name}}" readonly>
                    </div>
                    <div class="col-4">
                        <label for="position_limit">Position Limit</label>
                        <input type="number" class="form-control" placeholder="Enter Position Limit" name="position_limit" value="{{$lot->position_limit}}">
                    </div>
                    <div class="col-4">
                        <label for="max_order">Max Order</label>
                        <input type="number" class="form-control" placeholder="Enter Max Order" name="max_order" value="{{$lot->max_order}}">
                    </div>
                </div>
                <button type="submit" class="btn btn-info mt-4">Update</button>
            </form>
        </div>
    </div>
    
</div>
@endsection