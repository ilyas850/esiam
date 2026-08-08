<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <!-- Filter Section -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filter Periode Tahun Akademik - Semester</h3>
            </div>
            <form class="form" role="form" action="<?php echo e(url('filter_rekap_perkuliahan_prodi')); ?>" method="POST">
                <?php echo e(csrf_field()); ?>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Periode Tahun</label>
                                <select class="form-control" name="id_periodetahun" required>
                                    <option value="">-- Pilih Tahun --</option>
                                    <?php $__currentLoopData = $tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($thn->id_periodetahun); ?>" <?php echo e((isset($idtahun) && $idtahun == $thn->id_periodetahun) ? 'selected' : ''); ?>><?php echo e($thn->periode_tahun); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Semester</label>
                                <select class="form-control" name="id_periodetipe" required>
                                    <option value="">-- Pilih Semester --</option>
                                    <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($tipee->id_periodetipe); ?>" <?php echo e((isset($idtipe) && $idtipe == $tipee->id_periodetipe) ? 'selected' : ''); ?>><?php echo e($tipee->periode_tipe); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Data Table Section -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-table"></i> Rekap Perkuliahan <?php echo e(isset($namaperiodetahun) ? $namaperiodetahun : ''); ?> - <?php echo e(isset($namaperiodetipe) ? $namaperiodetipe : ''); ?></h3>
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
                                    <a href="/cek_rekapan_prodi/<?php echo e($key->id_kurperiode); ?>" class="btn btn-info btn-xs" title="Lihat Detail BAP">
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/adminprodi/perkuliahan/rekap_perkuliahan.blade.php ENDPATH**/ ?>