<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
                                    <a class="btn btn-warning" href="pwd/<?php echo e(Auth::user()->id); ?>">Klik disini untuk ganti password !!!</a>
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