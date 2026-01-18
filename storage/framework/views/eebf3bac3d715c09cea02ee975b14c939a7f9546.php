<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-braille"></i> Absensi Perkuliahan
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="<?php echo e(url('makul_diampu_dsn')); ?>"> Data Matakuliah</a></li>
            <li><a href="/entri_bap/<?php echo e($bap->id_kurperiode); ?>"> BAP</a></li>
            <li class="active">Absensi Perkuliahan </li>
        </ol>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <i class="fa fa-info-circle"></i>
                <h3 class="box-title">Informasi Matakuliah</h3>
            </div>
            <div class="box-body" style="background-color: #f9f9f9; border-bottom: 1px solid #ddd;">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="dl-horizontal" style="margin-bottom: 0;">
                            <dt>Matakuliah</dt>
                            <dd><?php echo e($bap->makul); ?> (<?php echo e($bap->akt_sks); ?> SKS)</dd>
                            <dt>Program Studi</dt>
                            <dd><?php echo e($bap->prodi); ?></dd>
                            <dt>Semester/TA</dt>
                            <dd><?php echo e($bap->periode_tipe); ?> <?php echo e($bap->periode_tahun); ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="dl-horizontal" style="margin-bottom: 0;">
                            <dt>Dosen</dt>
                            <dd><?php echo e($bap->nama); ?> <?php if($nama_dosen_2): ?>/ <?php echo e($nama_dosen_2); ?><?php endif; ?></dd>
                            <dt>Kelas / Ruang</dt>
                            <dd><?php echo e($bap->kelas); ?> / <?php echo e($bap->nama_ruangan); ?></dd>
                            <dt>Waktu</dt>
                            <dd>
                                <?php echo e($bap->hari); ?>, <?php echo e($bap->jam); ?>

                                
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="box-body">
                <div style="margin-bottom: 15px;">
                    <a href="/print_absensi/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-warning btn-flat" target="_blank">
                        <i class="fa fa-print"></i> Print Absensi
                    </a>
                    <a href="/download_absensi/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-success btn-flat">
                        <i class="fa fa-file-excel-o"></i> Download Excel
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="bg-teal">
                            <tr>
                                <th class="text-center" width="5%" style="vertical-align: middle;">No</th>
                                <th class="text-center" width="10%" style="vertical-align: middle;">NIM</th>
                                <th class="text-center" style="vertical-align: middle;">Nama Mahasiswa</th>
                                <?php for($i = 1; $i <= 16; $i++): ?>
                                    <th class="text-center" width="3%" style="vertical-align: middle; font-size: 12px;"><?php echo e($i); ?>

                                    </th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $data_mahasiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($index + 1); ?></td>
                                    <td class="text-center"><?php echo e($mhs->nim); ?></td>
                                    <td><?php echo e($mhs->nama); ?></td>

                                    
                                    <?php for($p = 1; $p <= 16; $p++): ?>
                                        <td class="text-center" style="padding: 5px;">
                                            <?php
                                                $status = $mhs->attendance[$p];
                                            ?>

                                            <?php if($status == 'ABSEN'): ?>
                                                <span class="text-green" title="Hadir" style="font-weight: bold;">&#10003;</span>
                                            <?php elseif($status == 'HADIR'): ?>
                                                <span class="text-red" title="Absen/Tidak Hadir" style="font-weight: bold;">x</span>
                                            <?php elseif($status == 'SAKIT'): ?>
                                                <span class="badge bg-yellow" title="Sakit">S</span>
                                            <?php elseif($status == 'IZIN'): ?>
                                                <span class="badge bg-blue" title="Izin">I</span>
                                            <?php elseif($status == 'ALFA'): ?>
                                                <span class="badge bg-red" title="Alfa">A</span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 10px;">
                    <small class="text-muted">
                        <strong>Keterangan: </strong>
                        <span class="text-green">&#10003;</span> : Hadir / Mengisi Absen &nbsp; | &nbsp;
                        <span class="text-red">x</span> : Tidak Hadir &nbsp; | &nbsp;
                        <span class="badge bg-yellow">S</span> : Sakit &nbsp; | &nbsp;
                        <span class="badge bg-blue">I</span> : Izin &nbsp; | &nbsp;
                        <span class="badge bg-red">A</span> : Alfa
                    </small>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosen/absensi_perkuliahan.blade.php ENDPATH**/ ?>