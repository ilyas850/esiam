<?php
    $dataCollection = collect($data);
    $totalData = $dataCollection->count();
    $totalValidasi = $dataCollection->where('validasi', 1)->count();
    $totalBelumValidasi = $totalData - $totalValidasi;
    $averageScores = $dataCollection->map(function ($item) {
        $scores = collect([$item->nilai_1, $item->nilai_2, $item->nilai_3])->filter(function ($score) {
            return $score !== null && $score !== '' && is_numeric($score);
        });

        return $scores->count() > 0 ? $scores->avg() : null;
    })->filter(function ($score) {
        return $score !== null;
    });
    $averageScore = $averageScores->count() > 0 ? number_format($averageScores->avg(), 2) : '-';
?>

<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Data</span>
                <span class="info-box-number"><?php echo e($totalData); ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sudah Validasi</span>
                <span class="info-box-number"><?php echo e($totalValidasi); ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-clock-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Belum Validasi</span>
                <span class="info-box-number"><?php echo e($totalBelumValidasi); ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="fa fa-star"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Rata-Rata Nilai</span>
                <span class="info-box-number"><?php echo e($averageScore); ?></span>
            </div>
        </div>
    </div>
</div>

<div class="box <?php echo e($boxType); ?>">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="fa fa-table"></i> <?php echo e($title); ?>

        </h3>
        <div class="box-tools pull-right">
            <span class="label label-info"><?php echo e($totalData); ?> Mahasiswa</span>
        </div>
    </div>
    <div class="box-body">
        <?php if($totalData > 0): ?>
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped table-hover nilai-table">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2"><?php echo e($dateLabel); ?></th>
                            <th rowspan="2">Nama Mahasiswa</th>
                            <th rowspan="2">NIM</th>
                            <th rowspan="2">Program Studi</th>
                            <th rowspan="2">Kelas</th>
                            <th colspan="4">Nilai</th>
                            <th rowspan="2">Status</th>
                            <th rowspan="2">Unduh Form</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Pembimbing</th>
                            <th>Penguji I</th>
                            <th>Penguji II</th>
                            <th>Huruf</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="nilai-number"><?php echo e($loop->iteration); ?></td>
                                <td class="nilai-number"><?php echo e($key->tanggal_selesai ?: '-'); ?></td>
                                <td class="nilai-student"><?php echo e($key->nama); ?></td>
                                <td class="nilai-number"><strong><?php echo e($key->nim); ?></strong></td>
                                <td class="nilai-number"><?php echo e($key->prodi ?: '-'); ?></td>
                                <td class="nilai-number"><?php echo e($key->kelas ?: '-'); ?></td>
                                <td class="nilai-number"><?php echo e(is_numeric($key->nilai_1) ? number_format($key->nilai_1, 2) : '-'); ?></td>
                                <td class="nilai-number"><?php echo e(is_numeric($key->nilai_2) ? number_format($key->nilai_2, 2) : '-'); ?></td>
                                <td class="nilai-number"><?php echo e(is_numeric($key->nilai_3) ? number_format($key->nilai_3, 2) : '-'); ?></td>
                                <td class="nilai-number">
                                    <span class="label label-primary"><?php echo e($key->nilai_huruf ?: '-'); ?></span>
                                </td>
                                <td class="nilai-number">
                                    <?php if($key->validasi == 1): ?>
                                        <span class="label label-success"><i class="fa fa-check"></i> Tervalidasi</span>
                                    <?php else: ?>
                                        <span class="label label-warning"><i class="fa fa-clock-o"></i> Menunggu</span>
                                    <?php endif; ?>
                                </td>
                                <td class="nilai-download">
                                    <?php $__currentLoopData = $downloadRoutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $download): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(url($download['route'] . '/' . $key->id_settingrelasi_prausta)); ?>"
                                            class="btn <?php echo e($download['class']); ?> btn-xs" title="<?php echo e($download['title']); ?>">
                                            <i class="fa fa-download"></i> <?php echo e($download['label']); ?>

                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                                <td class="nilai-action">
                                    <?php $__currentLoopData = $editRoutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(url($edit['route'] . '/' . $key->id_settingrelasi_prausta)); ?>"
                                            class="btn <?php echo e($edit['class']); ?> btn-xs" title="<?php echo e($edit['title']); ?>">
                                            <i class="fa fa-edit"></i> <?php echo e($edit['label']); ?>

                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <?php if($key->validasi == 0): ?>
                                        <a href="<?php echo e(url($validateRoute . '/' . $key->id_settingrelasi_prausta)); ?>"
                                            class="btn btn-primary btn-xs" title="Validasi nilai">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo e(url($unvalidateRoute . '/' . $key->id_settingrelasi_prausta)); ?>"
                                            class="btn btn-danger btn-xs" title="Batalkan validasi">
                                            <i class="fa fa-close"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="nilai-empty">
                <i class="fa fa-folder-open-o"></i>
                Belum ada data nilai mahasiswa untuk ditampilkan.
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/prausta/partials/nilai_akhir_table.blade.php ENDPATH**/ ?>