@extends('layout.app')
@section('title', '')
@section('pagetitle', 'Edit lot Quantity')
@section('content')
<div class="container-fluid">
    

    <div class="card info-card sales-card">
        <div class="card-body">
            <form action="{{route('lotdetail.update',  $lot->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-6">
                        <label for="segment_id">Market</label>
                        <input type="text" class="form-control" value="{{$lot->segment->name}}">
                    </div>
                    <div class="col-6">
                        <label for="script_id">Script</label>
                        <input type="text" class="form-control" value="{{$lot->script->name}}" readonly>
                    </div>
                    <div class="col-6">
                        <label for="lot">Lot</label>
                        <input type="number" class="form-control" value="1" readonly>
                    </div>
                    <div class="col-6">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" placeholder="Enter Quantity" name="lot_quantity" value="{{$lot->lot_quantity}}">
                    </div>
                </div>
                <button type="submit" class="btn btn-info mt-4">Update</button>
            </form>
        </div>
    </div>
    
</div>
@endsection