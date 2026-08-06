<?php
    $active = isset($active) ? $active : '';
    $items = [
        [
            'key' => 'ta',
            'label' => 'Data Nilai TA',
            'desc' => 'Kelola nilai pembimbing, penguji, dan validasi Tugas Akhir.',
            'url' => url('/data_nilai_ta_mahasiswa'),
            'icon' => 'fa-graduation-cap',
            'class' => 'type-ta',
        ],
        [
            'key' => 'skripsi',
            'label' => 'Data Nilai Skripsi',
            'desc' => 'Kelola nilai pembimbing, penguji, dan validasi Skripsi.',
            'url' => url('/data_nilai_skripsi_mahasiswa'),
            'icon' => 'fa-book',
            'class' => 'type-skripsi',
        ],
    ];
?>

<div class="box box-solid nilai-selector">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="fa fa-list-alt"></i> Pilih Tipe Penilaian
        </h3>
    </div>
    <div class="box-body">
        <div class="row nilai-type-grid">
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-sm-6 nilai-type-col">
                    <a href="<?php echo e($item['url']); ?>"
                        class="nilai-type-option <?php echo e($item['class']); ?> <?php echo e($active == $item['key'] ? 'active' : ''); ?>">
                        <span class="nilai-type-icon"><i class="fa <?php echo e($item['icon']); ?>"></i></span>
                        <span class="nilai-type-title"><?php echo e($item['label']); ?></span>
                        <span class="nilai-type-desc"><?php echo e($item['desc']); ?></span>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/prausta/partials/nilai_ta_skripsi_nav.blade.php ENDPATH**/ ?>