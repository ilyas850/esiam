<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('prausta.prakerin.partials.nilai_prausta_style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <section class="content nilai-prausta">
        <?php echo $__env->make('prausta.prakerin.partials.nilai_prausta_nav', ['active' => 'pkl'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('prausta.prakerin.partials.nilai_prausta_table', [
            'title' => 'Data Nilai PKL Mahasiswa',
            'boxType' => 'box-info',
            'editRoute' => 'edit_nilai_pkl',
            'validateRoute' => 'validate_nilai_pkl',
            'unvalidateRoute' => 'unvalidate_nilai_pkl',
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/prakerin/nilai_pkl.blade.php ENDPATH**/ ?>