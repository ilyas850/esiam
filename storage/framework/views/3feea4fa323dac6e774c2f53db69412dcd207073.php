<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('prausta.partials.nilai_akhir_style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <section class="content nilai-akhir">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="nilai-page-title">Nilai TA dan Skripsi</h3>
                <div class="nilai-page-subtitle">Pilih tipe penilaian untuk melihat, mengedit, mengunduh form, atau melakukan validasi nilai mahasiswa.</div>
            </div>
        </div>

        <?php echo $__env->make('prausta.partials.nilai_ta_skripsi_nav', ['active' => ''], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/ta/nilai_ta_skripsi.blade.php ENDPATH**/ ?>