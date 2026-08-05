<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">Pilih Tipe</h3>
            </div>
            <div class="box-body">
                <a href="/data_nilai_pkl_mahasiswa" class="btn btn-info">Data Nilai PKL</a>
                <a href="/data_nilai_magang_mahasiswa" class="btn btn-success">Data Nilai Magang 1</a>
                <a href="/data_nilai_magang2_mahasiswa" class="btn btn-warning">Data Nilai Magang 2</a>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/prakerin/nilai_pkl_magang.blade.php ENDPATH**/ ?>