<?php
    $active = isset($active) ? $active : '';
    $items = [
        [
            'key' => 'pkl',
            'label' => 'Data Nilai PKL',
            'desc' => 'Kelola nilai pembimbing dan seminar PKL mahasiswa.',
            'url' => url('/data_nilai_pkl_mahasiswa'),
            'icon' => 'fa-briefcase',
            'class' => 'type-pkl',
        ],
        [
            'key' => 'magang',
            'label' => 'Data Nilai Magang 1',
            'desc' => 'Kelola nilai pembimbing dan seminar Magang 1.',
            'url' => url('/data_nilai_magang_mahasiswa'),
            'icon' => 'fa-building',
            'class' => 'type-magang',
        ],
        [
            'key' => 'magang2',
            'label' => 'Data Nilai Magang 2',
            'desc' => 'Kelola nilai pembimbing dan seminar Magang 2.',
            'url' => url('/data_nilai_magang2_mahasiswa'),
            'icon' => 'fa-industry',
            'class' => 'type-magang2',
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
                <div class="col-md-4 col-sm-6 nilai-type-col">
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
<?php /**PATH /var/www/html/resources/views/prausta/prakerin/partials/nilai_prausta_nav.blade.php ENDPATH**/ ?>