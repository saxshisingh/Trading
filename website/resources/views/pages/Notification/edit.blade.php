@extends('layout.app')
@section('title', '')
@section('pagetitle', 'Edit Notification')
@section('content')
<div class="container-fluid">
        <div class="col-md-12">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <form action="{{route('notification.update', $notification->id)}}" method="POST">
                    @csrf
                        @method('PUT')
                    <div class="form-group mt-4">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" value="{{$notification->title}}">
                    </div>

                    <div class="form-group mt-4">
                        <label for="message">Message:</label>
                        <textarea class="form-control" id="message" name="message" placeholder="Enter message">{{ $notification->message }}</textarea>
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
                        <input type="datetime-local" class="form-control" id="send_at" name="send_at" value="{{$notification->send_at}}">
                    </div>


                    <button type="submit" class="btn btn-primary mt-4">Update</button>
                </form>

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