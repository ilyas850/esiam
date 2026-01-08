<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <!-- Data Table Section -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-table"></i> Rekap Perkuliahan</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 40px;">No</th>
                            <th>Kode/Matakuliah</th>
                            <th class="text-center" style="width: 70px;">SKS (T/P)</th>
                            <th>Prodi</th>
                            <th class="text-center" style="width: 60px;">Kelas</th>
                            <th>Dosen</th>
                            <th class="text-center" style="width: 120px;">Jumlah Pertemuan</th>
                            <th class="text-center" style="width: 130px;">Online / Offline</th>
                            <th class="text-center" style="width: 100px;">Status</th>
                            <th class="text-center" style="width: 80px;">BAP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $jmlPer = $key->jml_per ?? 0;
                                $jmlOnline = $key->jml_online ?? 0;
                                $jmlOffline = $key->jml_offline ?? 0;
                                $percentage = min(($jmlPer / 16) * 100, 100);
                                $tercapai = $jmlPer >= 16;
                                
                                // Progress bar color based on AdminLTE
                                if ($percentage >= 100) {
                                    $progressColor = 'progress-bar-success';
                                } elseif ($percentage >= 75) {
                                    $progressColor = 'progress-bar-info';
                                } elseif ($percentage >= 50) {
                                    $progressColor = 'progress-bar-warning';
                                } else {
                                    $progressColor = 'progress-bar-danger';
                                }
                            ?>
                            <tr>
                                <td class="text-center"><?php echo e($no++); ?></td>
                                <td><?php echo e($key->makul); ?></td>
                                <td class="text-center"><?php echo e($key->sks); ?></td>
                                <td><?php echo e($key->prodi); ?></td>
                                <td class="text-center"><?php echo e($key->kelas); ?></td>
                                <td><?php echo e($key->nama ?? '-'); ?></td>
                                <td>
                                    <div class="progress progress-xs progress-striped active" style="margin-bottom: 0;">
                                        <div class="progress-bar <?php echo e($progressColor); ?>" style="width: <?php echo e($percentage); ?>%"></div>
                                    </div>
                                    <small class="text-muted"><?php echo e($jmlPer); ?> dari 16 pertemuan</small>
                                </td>
                                <td class="text-center">
                                    <span class="label label-info" title="Online">
                                        <i class="fa fa-wifi"></i> <?php echo e($jmlOnline); ?>

                                    </span>
                                    <span class="label label-success" title="Offline">
                                        <i class="fa fa-users"></i> <?php echo e($jmlOffline); ?>

                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if($tercapai): ?>
                                        <span class="label label-success">
                                            <i class="fa fa-check"></i> Tercapai
                                        </span>
                                    <?php else: ?>
                                        <span class="label label-danger">
                                            <i class="fa fa-times"></i> Belum
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="cek_rekapan_kprd/<?php echo e($key->id_kurperiode); ?>" class="btn btn-info btn-xs" title="Lihat Detail BAP">
                                        <i class="fa fa-eye"></i> Cek
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/kaprodi/perkuliahan/rekap_perkuliahan.blade.php ENDPATH**/ ?>