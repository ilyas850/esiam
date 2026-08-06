<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('prausta.prakerin.partials.nilai_prausta_style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <section class="content nilai-prausta">
        <?php echo $__env->make('prausta.prakerin.partials.nilai_prausta_nav', ['active' => 'magang'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('prausta.prakerin.partials.nilai_prausta_table', [
            'title' => 'Data Nilai Magang 1 Mahasiswa',
            'boxType' => 'box-success',
            'editRoute' => 'edit_nilai_magang',
            'validateRoute' => 'validate_nilai_magang',
            'unvalidateRoute' => 'unvalidate_nilai_magang',
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/prakerin/nilai_magang.blade.php ENDPATH**/ ?>