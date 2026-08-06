<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('prausta.partials.nilai_akhir_style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <section class="content nilai-akhir">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="nilai-page-title">Nilai Seminar Proposal</h3>
                <div class="nilai-page-subtitle">Kelola nilai pembimbing, penguji, form unduhan, dan validasi Seminar Proposal mahasiswa.</div>
            </div>
        </div>

        <?php echo $__env->make('prausta.partials.nilai_akhir_table', [
            'title' => 'Data Nilai Sempro Mahasiswa',
            'dateLabel' => 'Tanggal Seminar',
            'boxType' => 'box-info',
            'downloadRoutes' => [
                ['route' => 'unduh_nilai_sempro_a', 'class' => 'btn-info', 'label' => 'Pembimbing', 'title' => 'Unduh nilai pembimbing Sempro'],
                ['route' => 'unduh_nilai_sempro_b', 'class' => 'btn-success', 'label' => 'Penguji I', 'title' => 'Unduh nilai penguji I Sempro'],
                ['route' => 'unduh_nilai_sempro_c', 'class' => 'btn-warning', 'label' => 'Penguji II', 'title' => 'Unduh nilai penguji II Sempro'],
            ],
            'editRoutes' => [
                ['route' => 'edit_nilai_sempro_bim', 'class' => 'btn-info', 'label' => 'Pembimbing', 'title' => 'Edit nilai pembimbing Sempro'],
                ['route' => 'edit_nilai_sempro_p1', 'class' => 'btn-success', 'label' => 'Penguji I', 'title' => 'Edit nilai penguji I Sempro'],
                ['route' => 'edit_nilai_sempro_p2', 'class' => 'btn-warning', 'label' => 'Penguji II', 'title' => 'Edit nilai penguji II Sempro'],
            ],
            'validateRoute' => 'validate_nilai_sempro',
            'unvalidateRoute' => 'unvalidate_nilai_sempro',
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/sempro/nilai_sempro.blade.php ENDPATH**/ ?>