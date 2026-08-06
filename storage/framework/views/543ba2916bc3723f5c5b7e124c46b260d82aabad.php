<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('prausta.partials.nilai_akhir_style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <section class="content nilai-akhir">
        <?php echo $__env->make('prausta.partials.nilai_ta_skripsi_nav', ['active' => 'skripsi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('prausta.partials.nilai_akhir_table', [
            'title' => 'Data Nilai Skripsi Mahasiswa',
            'dateLabel' => 'Tanggal Sidang',
            'boxType' => 'box-success',
            'downloadRoutes' => [
                ['route' => 'unduh_nilai_ta_a', 'class' => 'btn-info', 'label' => 'Pembimbing', 'title' => 'Unduh nilai pembimbing Skripsi'],
                ['route' => 'unduh_nilai_ta_b', 'class' => 'btn-success', 'label' => 'Penguji I', 'title' => 'Unduh nilai penguji I Skripsi'],
                ['route' => 'unduh_nilai_ta_c', 'class' => 'btn-warning', 'label' => 'Penguji II', 'title' => 'Unduh nilai penguji II Skripsi'],
            ],
            'editRoutes' => [
                ['route' => 'edit_nilai_skripsi_bim', 'class' => 'btn-info', 'label' => 'Pembimbing', 'title' => 'Edit nilai pembimbing Skripsi'],
                ['route' => 'edit_nilai_skripsi_p1', 'class' => 'btn-success', 'label' => 'Penguji I', 'title' => 'Edit nilai penguji I Skripsi'],
                ['route' => 'edit_nilai_skripsi_p2', 'class' => 'btn-warning', 'label' => 'Penguji II', 'title' => 'Edit nilai penguji II Skripsi'],
            ],
            'validateRoute' => 'validate_nilai_ta',
            'unvalidateRoute' => 'unvalidate_nilai_ta',
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/ta/nilai_skripsi.blade.php ENDPATH**/ ?>