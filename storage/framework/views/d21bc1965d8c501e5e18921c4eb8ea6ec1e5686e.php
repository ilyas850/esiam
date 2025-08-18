<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Politeknik META Industri Cikarang</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/Logo Meta.png')); ?>">
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
        <?php if(Route::has('login')): ?>
            <div class="top-right links1">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(url('/home')); ?>">Home</a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>">Login</a>
                    
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="content">
            
        </div>
    </div>
</body>

</html>
<?php /**PATH /var/www/html/resources/views/welcome.blade.php ENDPATH**/ ?>