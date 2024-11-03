@extends('layout.app')
@section('title', 'Change Password')
@section('content')

<div class="container-fluid">
    <h4>Change Password</h4>

    <div class="card" style="background-color: rgba(29, 27, 27, 0.791); box-shadow: 0 2px 10px rgba(0, 0, 0, .1); padding:20px">
        <div class="card-body">
            <div class="row">
                <h5 class="card-title" style="color: white;font-size:20px;font-weight:1000">Password</h5>
                <form action="{{route('users.update', auth()->user()->id)}}" method="POST">
                  @csrf
                  @method("PUT")
                        <div class="row" style="padding: 10px">
                          <div class="col-4">
                            <label for="current_password">PASSWORD</label>
                          </div>
                          <div class="col-8">
                            <input type="password" name="current_password" id="current_password" placeholder="Enter Password" class="form-control" >
                          </div>
                        </div>
                      
                        <div class="row" style="padding: 10px">
                          <div class="col-4">
                            <label for="new_password">NEW PASSWORD</label>
                          </div>
                          <div class="col-8">
                            <input type="password" name="new_password" id="new_password" placeholder="Enter New Password" class="form-control" >
                          </div>
                        </div>
                      
                        <div class="row" style="padding: 10px">
                          <div class="col-4">
                            <label for="confirm_password">CONFIRM PASSWORD</label>
                          </div>
                          <div class="col-8">
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="Enter Confirm Password" class="form-control">
                          </div>
                        </div>
                      </form>
                <div class="container-fluid" style="margin-top: 50px">
                        <div class="row" style="padding: 20px">
                            <div class="col-sm-1">
                                <button class="btn btn-danger btn-sm" style="padding: 10px; color:white">CANCEL</button>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-sm" style="padding: 10px; background-color: purple; color:white">SUBMIT</button>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

@endsection
