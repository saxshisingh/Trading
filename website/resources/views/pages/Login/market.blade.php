<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kite Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .full-height {
            height: 100vh;
        }

        .bg-brown {
            background-color: #333537;
        }

        h5,
        h1,
        p,
        label {
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container-fluid full-height bg-brown d-flex align-items-center justify-content-center">
        <div class="card p-4" style="width: 400px; background-color:black">
            <img src="{{ url('assets/img/logo.jpg') }}" class="card-img-top img-fluid" alt="..."
                style="padding: 20px">
            <h1>Kite Login</h1>

            <h5>Please log in using the following URL:</h5>
            <a href="{{ $login_url }}" target="_blank">{{ $login_url }}</a>

            <p>After logging in, you will be redirected to a URL. Please paste that URL below:</p>

            @if ($errors->any())
                <div>
                    <strong>Error!</strong> {{ $errors->first() }}
                </div>
            @endif
            <a class="btn btn-primary" href="{{ $login_url }}" target="_blank">Continue Login</a>
            {{-- <form action="{{ route('market.handleCallback') }}" method="POST">
                    @csrf
                    <label for="redirected_url">Redirected URL:</label>
                    <input type="text" id="redirected_url" name="redirected_url" class="form-control" required>
                    <button type="submit" class="btn btn-info mt-4">Submit</button>
                </form> --}}
        </div>
    </div>
    @include('partials.script')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
