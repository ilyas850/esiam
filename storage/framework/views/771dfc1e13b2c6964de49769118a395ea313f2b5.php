<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <section class="content-header">
        <h1>
            Jurnal Perkuliahan
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="<?php echo e(url('makul_diampu_kprd')); ?>"> Data Matakuliah yang diampu</a></li>
            <li><a href="/entri_bap_kprd/<?php echo e($bap->id_kurperiode); ?>"> BAP</a></li>
            <li class="active">Jurnal Perkuliahan</li>
        </ol>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <!-- Info Matakuliah Card (Native AdminLTE box-primary) -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-info-circle text-primary"></i> Informasi Matakuliah</h3>
                <div class="box-tools pull-right">
                    <a href="/entri_bap_kprd/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-default btn-sm">
                        <i class="fa fa-arrow-left"></i> Kembali ke BAP
                    </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <table class="table table-condensed table-borderless" style="margin-bottom: 0;">
                            <tr>
                                <th style="width: 35%;"><i class="fa fa-book text-muted"></i> Matakuliah</th>
                                <td style="width: 5%;">:</td>
                                <td><strong><?php echo e($bap->makul); ?></strong> &nbsp; <span class="label label-primary"><?php echo e($bap->akt_sks); ?> SKS</span></td>
                            </tr>
                            <tr>
                                <th><i class="fa fa-graduation-cap text-muted"></i> Program Studi</th>
                                <td>:</td>
                                <td><?php echo e($bap->prodi); ?></td>
                            </tr>
                            <tr>
                                <th><i class="fa fa-users text-muted"></i> Kelas</th>
                                <td>:</td>
                                <td><span class="label label-default"><?php echo e($bap->kelas); ?></span></td>
                            </tr>
                            <tr>
                                <th><i class="fa fa-user-md text-muted"></i> Dosen Pengampu</th>
                                <td>:</td>
                                <td><?php echo e($bap->nama); ?>, <?php echo e($bap->akademik); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <table class="table table-condensed table-borderless" style="margin-bottom: 0;">
                            <tr>
                                <th style="width: 35%;"><i class="fa fa-calendar text-muted"></i> Tahun Akademik</th>
                                <td style="width: 5%;">:</td>
                                <td><span class="label label-info"><?php echo e($bap->periode_tahun); ?> <?php echo e($bap->periode_tipe); ?></span></td>
                            </tr>
                            <tr>
                                <th><i class="fa fa-clock-o text-muted"></i> Waktu Kuliah</th>
                                <td>:</td>
                                <td>
                                    <?php echo e($bap->hari); ?>,
                                    <?php if($bap->id_kelas == 1): ?>
                                        <?php echo e(date('H:i', strtotime($bap->jam))); ?> - <?php echo e(date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 50 + 60 * $bap->akt_sks_praktek * 120)); ?>

                                    <?php elseif($bap->id_kelas == 2 || $bap->id_kelas == 3): ?>
                                        <?php echo e(date('H:i', strtotime($bap->jam))); ?> - <?php echo e(date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 45 + 60 * $bap->akt_sks_praktek * 90)); ?>

                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fa fa-building text-muted"></i> Ruangan</th>
                                <td>:</td>
                                <td><?php echo e($bap->nama_ruangan); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Jurnal Perkuliahan (Native AdminLTE box-info) -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list-alt text-info"></i> Daftar Jurnal Perkuliahan</h3>
                <div class="box-tools pull-right">
                    <a href="/print_jurnal_kprd/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-success btn-sm" target="_blank">
                        <i class="fa fa-print"></i> Print
                    </a>
                    <a href="/download_jurnal_kprd/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-info btn-sm">
                        <i class="fa fa-download"></i> Download
                    </a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr class="bg-gray" style="font-weight: 600;">
                            <th class="text-center" style="width: 50px;">No</th>
                            <th class="text-center" style="width: 130px;">Tanggal</th>
                            <th class="text-center" style="width: 140px;">Jam</th>
                            <th>Materi</th>
                            <th class="text-center" style="width: 130px;">Paraf Dosen</th>
                            <th class="text-center" style="width: 110px;">Validasi</th>
                            <th class="text-center" style="width: 100px;">Honor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-center"><?php echo e($index + 1); ?></td>
                                <td class="text-center">
                                    <?php echo e($item->tanggal ? date('Y-m-d', strtotime($item->tanggal)) : ''); ?>

                                </td>
                                <td class="text-center">
                                    <?php echo e($item->jam_mulai ? date('H:i', strtotime($item->jam_mulai)) : ''); ?> - <?php echo e($item->jam_selsai ? date('H:i', strtotime($item->jam_selsai)) : ''); ?>

                                </td>
                                <td><?php echo e($item->materi_kuliah); ?></td>
                                <td class="text-center">By System</td>
                                <td class="text-center">
                                    <?php if($item->tanggal_validasi == '2001-01-01'): ?>
                                        <span class="label label-danger">BELUM</span>
                                    <?php else: ?>
                                        <span class="label label-primary">SUDAH</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?php echo e($item->payroll_check); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    <em>Belum ada data perkuliahan.</em>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/kaprodi/bap/jurnal_perkuliahan.blade.php ENDPATH**/ ?>