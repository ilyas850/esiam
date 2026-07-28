<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<style>
    .attendance-summary-card {
        border-radius: 8px;
        border: 1px solid #e6e6e6;
        background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
        padding: 16px 18px;
        margin-bottom: 15px;
        min-height: 110px;
    }

    .attendance-summary-card .summary-label {
        color: #777;
        font-size: 12px;
        letter-spacing: .04em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .attendance-summary-card .summary-value {
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 6px;
    }

    .attendance-summary-card .summary-note {
        color: #666;
        font-size: 12px;
        margin: 0;
    }

    .attendance-status-cell {
        min-width: 42px;
        font-weight: 700;
    }

    .attendance-status-mark {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 700;
    }

    .attendance-status-hadir {
        background-color: #dff0d8;
        color: #1e7d34;
    }

    .attendance-status-sakit {
        background-color: #fcf8e3;
        color: #8a6d3b;
    }

    .attendance-status-izin {
        background-color: #d9edf7;
        color: #31708f;
    }

    .attendance-status-alfa {
        background-color: #f2dede;
        color: #a94442;
    }

    .attendance-status-empty {
        background-color: #f4f4f4;
        color: #999;
    }

    .attendance-legend {
        display: inline-block;
        margin-right: 14px;
        margin-bottom: 8px;
        color: #666;
    }

    .attendance-legend .attendance-status-mark {
        margin-right: 6px;
        width: 24px;
        height: 24px;
        font-size: 11px;
    }
</style>

<?php $__env->startSection('content_header'); ?>
    <section class="content-header">
        <h1><i class="fa fa-braille"></i> Absensi Perkuliahan</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="<?php echo e(url('makul_diampu_dsn')); ?>">Data Matakuliah</a></li>
            <li><a href="/entri_bap/<?php echo e($bap->id_kurperiode); ?>"> BAP</a></li>
            <li class="active">Absensi Perkuliahan</li>
        </ol>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        if ($bap->id_kelas == 1) {
            $jamSelesai = date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 50 + 60 * $bap->akt_sks_praktek * 120);
        } elseif (in_array($bap->id_kelas, [2, 3])) {
            $jamSelesai = date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 45 + 60 * $bap->akt_sks_praktek * 90);
        } else {
            $jamSelesai = $bap->jam;
        }
    ?>

    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <i class="fa fa-info-circle"></i>
                <h3 class="box-title">Informasi Matakuliah</h3>
                <div class="box-tools pull-right">
                    <a href="<?php echo e(url('print_absensi/' . $bap->id_kurperiode)); ?>" class="btn btn-success btn-sm" target="_blank">
                        <i class="fa fa-print"></i> Print
                    </a>
                    <a href="<?php echo e(url('download_absensi/' . $bap->id_kurperiode)); ?>" class="btn btn-warning btn-sm">
                        <i class="fa fa-file-excel-o"></i> Download
                    </a>
                </div>
            </div>

            <div class="box-body" style="background-color: #f9f9f9; border-bottom: 1px solid #eee;">
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
                            <dd><?php echo e($bap->nama); ?>, <?php echo e($bap->akademik); ?><?php if($nama_dosen_2): ?> / <?php echo e($nama_dosen_2); ?><?php endif; ?></dd>
                            <dt>Kelas / Ruang</dt>
                            <dd><?php echo e($bap->kelas); ?> / <?php echo e($bap->nama_ruangan); ?></dd>
                            <dt>Waktu</dt>
                            <dd><?php echo e($bap->hari); ?>, <?php echo e($bap->jam); ?> - <?php echo e($jamSelesai); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="attendance-summary-card">
                            <div class="summary-label">Total Mahasiswa</div>
                            <div class="summary-value text-primary"><?php echo e($summary['total_mahasiswa']); ?></div>
                            <p class="summary-note">Mahasiswa tercatat di absensi</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="attendance-summary-card">
                            <div class="summary-label">Pertemuan Terisi</div>
                            <div class="summary-value text-aqua"><?php echo e($summary['total_pertemuan_terisi']); ?></div>
                            <p class="summary-note">Dari total 16 pertemuan</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="attendance-summary-card">
                            <div class="summary-label">Total Hadir</div>
                            <div class="summary-value text-green"><?php echo e($summary['total_hadir']); ?></div>
                            <p class="summary-note">Akumulasi status hadir</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="attendance-summary-card">
                            <div class="summary-label">Total Tidak Hadir</div>
                            <div class="summary-value text-red"><?php echo e($summary['total_tidak_hadir']); ?></div>
                            <p class="summary-note">Izin, sakit, alfa, dan tidak hadir</p>
                        </div>
                    </div>
                </div>

                <div style="margin: 10px 0 15px;">
                    <span class="attendance-legend"><span class="attendance-status-mark attendance-status-hadir">&#10003;</span> Hadir</span>
                    <span class="attendance-legend"><span class="attendance-status-mark attendance-status-sakit">S</span> Sakit</span>
                    <span class="attendance-legend"><span class="attendance-status-mark attendance-status-izin">I</span> Izin</span>
                    <span class="attendance-legend"><span class="attendance-status-mark attendance-status-alfa">A</span> Alfa / Tidak hadir</span>
                    <span class="attendance-legend"><span class="attendance-status-mark attendance-status-empty">-</span> Belum ada data</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="bg-info">
                            <tr>
                                <th class="text-center" width="5%" style="vertical-align: middle;">No</th>
                                <th class="text-center" width="12%" style="vertical-align: middle;">NIM</th>
                                <th style="vertical-align: middle;">Nama Mahasiswa</th>
                                <?php for($i = 1; $i <= 16; $i++): ?>
                                    <th class="text-center attendance-status-cell" style="vertical-align: middle;"><?php echo e($i); ?></th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $data_mahasiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center"><?php echo e($index + 1); ?></td>
                                    <td class="text-center"><?php echo e($mhs->nim); ?></td>
                                    <td><?php echo e($mhs->nama); ?></td>
                                    <?php for($p = 1; $p <= 16; $p++): ?>
                                        <?php $status = $mhs->attendance[$p]; ?>
                                        <td class="text-center attendance-status-cell">
                                            <?php if($status == 'ABSEN'): ?>
                                                <span class="attendance-status-mark attendance-status-hadir" title="Hadir">&#10003;</span>
                                            <?php elseif($status == 'SAKIT'): ?>
                                                <span class="attendance-status-mark attendance-status-sakit" title="Sakit">S</span>
                                            <?php elseif($status == 'IZIN'): ?>
                                                <span class="attendance-status-mark attendance-status-izin" title="Izin">I</span>
                                            <?php elseif($status == 'ALFA' || $status == 'HADIR'): ?>
                                                <span class="attendance-status-mark attendance-status-alfa" title="Alfa / Tidak hadir">A</span>
                                            <?php else: ?>
                                                <span class="attendance-status-mark attendance-status-empty" title="Belum ada data">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="19" class="text-center text-muted">Belum ada data absensi mahasiswa.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosen/absensi_perkuliahan.blade.php ENDPATH**/ ?>