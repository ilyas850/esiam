<?php if(Auth::user()->role == 1): ?>
    <?php echo $__env->make('layouts.side_sadmin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(Auth::user()->role == 2): ?>
    <?php echo $__env->make('layouts.side_dosen_dlm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(Auth::user()->role == 3): ?>
    <?php echo $__env->make('layouts.side_mhs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(Auth::user()->role == 5): ?>
    <?php echo $__env->make('layouts.side_dosen_luar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(Auth::user()->role == 6): ?>
    <?php echo $__env->make('layouts.side_kaprodi', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(Auth::user()->role == 7): ?>
    <?php echo $__env->make('layouts.side_wadir1', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(Auth::user()->role == 8): ?>
    <?php echo $__env->make('layouts.side_bauk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(Auth::user()->role == 9): ?>
    <?php echo $__env->make('layouts.side_admin_prodi', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(Auth::user()->role == 10): ?>
    <?php echo $__env->make('layouts.side_wadir3', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(Auth::user()->role == 11): ?>
    <?php echo $__env->make('layouts.side_admin_prausta', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(Auth::user()->role == 12): ?>
    <?php echo $__env->make('layouts.side_gugus_mutu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/layouts/side.blade.php ENDPATH**/ ?>