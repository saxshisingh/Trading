<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WHITE GOLD TRADES | INDIA’S MOST TRUSTED PLATFORM</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <meta content="India’s most trusted Dabba trading platform providing NSE / MCX / EQUITY / COMMODITIES with 500x & 100x margin , Since 2002 .Log in or sign up now !" name="description">
    <style>
        .full-height {
            height: 100vh;
        }
        .bg-brown {
            background-color: #191919;
            display: flex;
        }
        .d-flex{
            flex-direction: column;
        }
        h2{
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container-fluid full-height bg-brown align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center">
            <img src="{{url('assets/img/wg_logo.png')}}" class="card-img-top img-fluid" alt="..." style="padding: 20px;max-width:60%;">
            <div class="card p-4" style="width: 400px; background-color:transparent">
                <h2 class="mb-2">Login</h2>
                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control" id="user_id" name="user_id" placeholder="Enter User ID" style="background-color: rgb(19, 18, 18); color:white">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" style="background-color: rgb(19, 18, 18); color:white">
                    </div>
                    <button type="submit" class="btn btn-dark btn-block">SIGN IN</button>
                </form>
    
                
                <img src="{{url('assets/img/line.png')}}" class="card-img-top img-fluid" alt="..." style="padding: 20px">
            </div>
        </div>
    </div>
    @include('partials.script')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
