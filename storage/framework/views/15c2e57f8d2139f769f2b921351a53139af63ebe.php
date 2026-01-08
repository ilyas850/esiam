<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
        /* Enhancement styles that don't override existing CSS */
        .enhanced-animations {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .small-box {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
        }
        
        .small-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .small-box:hover::before {
            left: 100%;
        }
        
        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .small-box .inner h3 {
            animation: countUp 1.5s ease-out;
        }
        
        @keyframes  countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .box {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        
        .box-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #dee2e6;
        }
        
        .btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transition: width 0.3s, height 0.3s, top 0.3s, left 0.3s;
            z-index: 0;
        }
        
        .btn:hover::before {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
        }
        
        .btn > * {
            position: relative;
            z-index: 1;
        }
        
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(0,123,255,0.05);
        }
        
        .badge {
            animation: pulse 2s infinite;
        }
        
        @keyframes  pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .form-control {
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        
        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes  fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }
        
        @keyframes  slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .slide-in-right {
            animation: slideInRight 0.8s ease-out;
        }
        
        @keyframes  slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        #waktumundur {
            background: linear-gradient(135deg, #31266b 0%, #4a3c8a 100%);
            color: #fec503;
            font-size: 100%;
            text-transform: uppercase;
            text-align: center;
            padding: 20px 0;
            font-weight: bold;
            border-radius: 8px;
            line-height: 1.8em;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 15px rgba(49, 38, 107, 0.3);
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes  glow {
            from { box-shadow: 0 4px 15px rgba(49, 38, 107, 0.3); }
            to { box-shadow: 0 6px 20px rgba(49, 38, 107, 0.5), 0 0 30px rgba(254, 197, 3, 0.2); }
        }
        
        .digit {
            color: white;
            text-shadow: 0 0 10px rgba(255,255,255,0.5);
        }
        
        .judul {
            color: white;
            text-shadow: 0 0 10px rgba(255,255,255,0.5);
        }
        
        .icon {
            transition: transform 0.3s ease;
        }
        
        .small-box:hover .icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .loading {
            position: relative;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .loading.active::after {
            opacity: 1;
            visibility: visible;
        }
        
        .tooltip-custom {
            position: relative;
        }
        
        .tooltip-custom::before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .tooltip-custom:hover::before {
            opacity: 1;
            visibility: visible;
            bottom: calc(100% + 5px);
        }
    </style>
    <section class="content">
        <?php if(Auth::user()->role == 1): ?>
            <?php echo $__env->make('layouts.admin_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(Auth::user()->role == 2): ?>
            <?php echo $__env->make('layouts.dosen_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(Auth::user()->role == 3): ?>
            <?php echo $__env->make('layouts.mhs_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(Auth::user()->role == 4): ?>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <span class="fa fa-graduation-cap"></span>
                            <h3 class="box-title">Selamat Datang Mahasiswa Politeknik META Industri Cikarang</h3>
                        </div>
                        <form class="form-horizontal" role="form" method="POST"
                            action="/new_pwd_user/<?php echo e(Auth::user()->username); ?>">
                            <?php echo e(csrf_field()); ?>

                            <input id="role" type="hidden" class="form-control" name="role" value="3">
                            <div class="box-body">
                                <center>
                                    <a class="btn btn-warning" href="pwd/<?php echo e(Auth::user()->id); ?>"
                                        class="btn btn-default btn-flat">Klik disini untuk ganti password !!!</a>
                                </center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php elseif(Auth::user()->role == 5): ?>
            <?php echo $__env->make('layouts.dosenluar_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(Auth::user()->role == 6): ?>
            <?php echo $__env->make('layouts.kaprodi_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(Auth::user()->role == 7): ?>
            <?php echo $__env->make('layouts.wadir1_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(Auth::user()->role == 8): ?>
            <?php echo $__env->make('layouts.bauk_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(Auth::user()->role == 9): ?>
            <?php echo $__env->make('layouts.adminprodi_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(Auth::user()->role == 10): ?>
            <?php echo $__env->make('layouts.wadir3_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(Auth::user()->role == 11): ?>
            <?php echo $__env->make('layouts.prausta_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(Auth::user()->role == 12): ?>
            <?php echo $__env->make('layouts.gugusmutu_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/home.blade.php ENDPATH**/ ?>