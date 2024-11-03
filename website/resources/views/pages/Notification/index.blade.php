@extends('layout.app')
@section('title', '')
@section('pagetitle', 'Push Notification')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Write Push Notification</h5>
                    <form action="{{route('notification.store')}}" method="POST">
                    @csrf
                
                    <div class="form-group mt-4">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter title">
                    </div>

                    <div class="form-group mt-4">
                        <label for="message">Message:</label>
                        <textarea class="form-control" id="message" name="message" placeholder="Enter message"></textarea>
                    </div>

                    <div class="form-group mt-4">
                        <label for="users">Select Users:</label>
                        <select class="form-control" id="users" name="users[]" multiple>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group mt-4">
                        <label for="schedule">Schedule:</label>
                        <input type="datetime-local" class="form-control" id="send_at" name="send_at">
                    </div>

                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="send-now" name="send_now">
                        <label class="form-check-label" for="send-now">Send now</label>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Send</button>
                </form>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9" style="padding: 20px">
        
                        </div>
                        <div class="col-md-2">
                        @include('partials.page_select')
                        </div>
                        <div class="col-md-1">
                            
                            </div>
                    </div>
                    <h5 class="card-title">All Notification</h5>
                    <div class="table-responsive text-center">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Send At</th>
    
                                    <th colspan="2">Action</th>
                                </tr>
                                @foreach ($notification as $u)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $u->title }}</td>
                                        <td>{{ $u->message }}</td>
                                        <td>{{$u->send_at}}</td>
                                        <td>
                                            @if($u->status!='1')
                                                <a href="{{ route('notification.edit',  $u->id) }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Edit" class="btn btn-secondary btn-sm"><i
                                                        class="bi bi-pencil"></i></a>
                                            @else
                                            @endif
                                                           
                                        </td>
                                    </tr>
                                @endforeach
                            </thead>
                        </table>
                        {!! $notification->appends(\Request::all())->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    
    const sendNowCheckbox = document.getElementById('send-now');
    const scheduleInput = document.getElementById('send_at');

  
    sendNowCheckbox.addEventListener('change', function() {
        if (sendNowCheckbox.checked) {
           
            const now = new Date().toISOString().slice(0, 16);
           
            scheduleInput.value = now;
           
            scheduleInput.disabled = true;
        } else {
        
            scheduleInput.disabled = false;
        }
    });
</script>
@endsection