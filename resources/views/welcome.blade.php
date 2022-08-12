<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Politeknik META Industri Cikarang</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Meta.png') }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        body {
            background-image: url('images/walpaper welcome page.jpg');
            /* image-width: 10px; */
            /* background-color: #fff; */
            /*color: #636b6f;*/
            font-family: 'Nunito', sans-serif;
            /*font-weight: 200;*/
            /*height: 100vh;*/
            margin: 0;

            /*max-width:100%;*/
            /*width:100%;*/
            height: auto;

            background-size: 100% 100%;

            width: 100%;
            /*max-width: 1300px;*/
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
            color: #fff;
        }

        .links>a {
            color: #fff;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            /* text-transform: uppercase; */
        }

        .links1>a {
            color: #fff;
            padding: 0 25px;
            font-size: 25px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
            border: 3px solid #ffff00;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
            <div class="top-right links1">
                @auth
                    <a href="{{ url('/home') }}">Home</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    {{-- @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif --}}
                @endauth
            </div>
        @endif

        <div class="content">
            {{-- <img src="{{asset('images/Logo Meta.png')}}" alt="" width="300px" height="300px">
              <div class="title m-b-md">
                  <b>e-SIAM</b>
              </div>
                <div class="title m-b-md">
                    <b>Politeknik</b> META Industri
                </div>

                <div class="links">
                    <a href=""><i class="fa fa-envelope-o"></i>Contact </a>
                    <a href=""><i class="fa fa-phone"></i>ilyas@politeknikmeta.ac.id</a>

                </div> --}}
        </div>
    </div>
</body>

</html>
