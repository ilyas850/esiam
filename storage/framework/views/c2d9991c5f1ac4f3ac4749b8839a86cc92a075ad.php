<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('prausta.partials.nilai_akhir_style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <section class="content nilai-akhir">
        <?php echo $__env->make('prausta.partials.nilai_ta_skripsi_nav', ['active' => 'ta'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('prausta.partials.nilai_akhir_table', [
            'title' => 'Data Nilai Tugas Akhir Mahasiswa',
            'dateLabel' => 'Tanggal Sidang',
            'boxType' => 'box-info',
            'downloadRoutes' => [
                ['route' => 'unduh_nilai_ta_a', 'class' => 'btn-info', 'label' => 'Pembimbing', 'title' => 'Unduh nilai pembimbing TA'],
                ['route' => 'unduh_nilai_ta_b', 'class' => 'btn-success', 'label' => 'Penguji I', 'title' => 'Unduh nilai penguji I TA'],
                ['route' => 'unduh_nilai_ta_c', 'class' => 'btn-warning', 'label' => 'Penguji II', 'title' => 'Unduh nilai penguji II TA'],
            ],
            'editRoutes' => [
                ['route' => 'edit_nilai_ta_bim', 'class' => 'btn-info', 'label' => 'Pembimbing', 'title' => 'Edit nilai pembimbing TA'],
                ['route' => 'edit_nilai_ta_p1', 'class' => 'btn-success', 'label' => 'Penguji I', 'title' => 'Edit nilai penguji I TA'],
                ['route' => 'edit_nilai_ta_p2', 'class' => 'btn-warning', 'label' => 'Penguji II', 'title' => 'Edit nilai penguji II TA'],
            ],
            'validateRoute' => 'validate_nilai_ta',
            'unvalidateRoute' => 'unvalidate_nilai_ta',
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/ta/nilai_ta.blade.php ENDPATH**/ ?>