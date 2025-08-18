<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Existing stylesheets -->
    <link rel="stylesheet" href="<?php echo e(asset('dsg_login/fonts/icomoon/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dsg_login/css/owl.carousel.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dsg_login/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dsg_login/css/style.css')); ?>">
    
    <!-- Enhanced styles -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .content {
            width: 100%;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .image-section {
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 600px;
            position: relative;
        }

        .image-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .form-section {
            padding: 60px 40px;
            display: flex;
            align-items: center;
            min-height: 600px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h3 {
            font-weight: 700;
            color: #333;
            font-size: 2.2rem;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 1.1rem;
            margin: 0;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            padding: 15px 50px 15px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: #fff;
            outline: none;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            margin-top: 12px;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .back-home {
            text-align: center;
            margin-top: 30px;
        }

        .back-home a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-home a:hover {
            color: #764ba2;
        }

        .logo-section {
            text-align: center;
            color: white;
            z-index: 1;
            position: relative;
        }

        .logo-container {
            margin-bottom: 20px;
        }

        .logo-img {
            max-width: 120px;
            max-height: 120px;
            margin-bottom: 20px;
            opacity: 0.95;
            /* Menghapus filter yang bisa menyembunyikan logo */
        }

        .logo-fallback {
            color: white;
            opacity: 0.9;
        }

        .logo-section h4 {
            font-weight: 300;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .logo-section p {
            font-weight: 300;
            opacity: 0.8;
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 20%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 60%;
            right: 20%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 100px;
            height: 100px;
            bottom: 20%;
            left: 30%;
            animation-delay: 4s;
        }

        @keyframes  float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .invalid-feedback {
            display: block;
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .form-section {
                padding: 40px 20px;
            }
            
            .login-header h3 {
                font-size: 1.8rem;
            }
            
            .image-section {
                min-height: 300px;
            }
        }
    </style>
    
    <link rel="icon" type="image/png" href="<?php echo e(asset('dsg_login/images/Logo Meta.png')); ?>">
    <title>Politeknik META Industri</title>
</head>

<body>
    <?php echo $__env->yieldContent('content'); ?>
    
    <script src="<?php echo e(asset('dsg_login/js/jquery-3.3.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dsg_login/js/popper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dsg_login/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dsg_login/js/main.js')); ?>"></script>
</body>

</html><?php /**PATH /var/www/html/resources/views/layouts/master_auth.blade.php ENDPATH**/ ?>