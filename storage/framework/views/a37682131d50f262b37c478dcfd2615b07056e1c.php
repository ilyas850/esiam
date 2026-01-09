<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-file-alt"></i> View Berita Acara Perkuliahan
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-home"></i> Halaman Utama</a></li>
            <li><a href="<?php echo e(url('data_bap_gugusmutu')); ?>">BAP Perkuliahan</a></li>
            <li><a href="/cek_bap_gugusmutu/<?php echo e($dtbp->id_kurperiode); ?>">Cek BAP</a></li>
            <li class="active">View BAP</li>
        </ol>
    </section>

    <section class="content">
        <!-- Action Buttons -->
        <div class="margin-bottom">
            <a class="btn btn-default" href="/cek_bap_gugusmutu/<?php echo e($dtbp->id_kurperiode); ?>">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Header Box -->
        <div class="box box-primary">
            <div class="box-header with-border text-center">
                <h3 class="box-title">
                    <i class="fa fa-graduation-cap"></i>
                    Laporan Pembelajaran Daring
                </h3>
            </div>
            <div class="box-body text-center">
                <h4><strong>Prodi <?php echo e($prd); ?> | Semester <?php echo e($tipe); ?> – <?php echo e($tahun); ?></strong></h4>
            </div>
        </div>

        <div class="row">
            <!-- Main Information Box -->
            <div class="col-lg-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-info-circle"></i>
                            Informasi Perkuliahan
                        </h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 30%;"><i class="fa fa-book text-success"></i> Mata Kuliah</th>
                                    <td><?php echo e($data->makul); ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-user text-info"></i> Nama Dosen</th>
                                    <td><?php echo e($data->nama); ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-users text-warning"></i> Kelas / Semester</th>
                                    <td><?php echo e($data->kelas); ?> / <?php echo e($data->semester); ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-desktop text-purple"></i> Media Pembelajaran</th>
                                    <td><?php echo e($dtbp->media_pembelajaran); ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-clock-o text-primary"></i> Waktu Pelaksanaan</th>
                                    <td><?php echo e($dtbp->jam_mulai); ?> - <?php echo e($dtbp->jam_selsai); ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-calendar text-danger"></i> Tanggal Perkuliahan</th>
                                    <td><?php echo e($dtbp->tanggal); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Summary Box -->
            <div class="col-lg-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-bar-chart"></i>
                            Ringkasan
                        </h3>
                    </div>
                    <div class="box-body">
                        <!-- Pertemuan Info Box -->
                        <div class="info-box bg-aqua">
                            <span class="info-box-icon"><i class="fa fa-hashtag"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pertemuan</span>
                                <span class="info-box-number">Ke-<?php echo e($dtbp->pertemuan); ?></span>
                            </div>
                        </div>

                        <!-- Kehadiran Section -->
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <i class="fa fa-user-check"></i> Kehadiran Mahasiswa
                                </h4>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-6 text-center">
                                        <div class="description-block border-right">
                                            <span class="description-percentage text-green">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            <h5 class="description-header"><?php echo e($dtbp->hadir); ?></h5>
                                            <span class="description-text">HADIR</span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 text-center">
                                        <div class="description-block">
                                            <span class="description-percentage text-red">
                                                <i class="fa fa-times"></i>
                                            </span>
                                            <h5 class="description-header"><?php echo e($dtbp->tidak_hadir); ?></h5>
                                            <span class="description-text">TIDAK HADIR</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center" style="margin-top: 15px;">
                                    <span class="label label-primary" style="font-size: 14px;">
                                        <i class="fa fa-users"></i> Total: <?php echo e($dtbp->hadir + $dtbp->tidak_hadir); ?> Mahasiswa
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Material Box -->
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-file-text"></i>
                    Materi Perkuliahan
                </h3>
            </div>
            <div class="box-body">
                <div class="callout callout-info">
                    <p><?php echo e($dtbp->materi_kuliah); ?></p>
                </div>
            </div>
        </div>

        <!-- Attachments Box -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-paperclip"></i>
                    Lampiran & Materi
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <!-- Kuliah Tatap Muka -->
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="small-box bg-blue">
                            <div class="inner">
                                <h4>Kuliah Tatap Muka</h4>
                                <p>
                                    <?php if(($dtbp->file_kuliah_tatapmuka) != null): ?>
                                        <i class="fa fa-check-circle"></i> Tersedia
                                    <?php else: ?>
                                        <i class="fa fa-times-circle"></i> Tidak tersedia
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-chalkboard-teacher"></i>
                            </div>
                            <?php if(($dtbp->file_kuliah_tatapmuka) != null): ?>
                                <a href="/File_BAP/<?php echo e($data->iddosen); ?>/<?php echo e($dtbp->id_kurperiode); ?>/Kuliah Tatap Muka/<?php echo e($dtbp->file_kuliah_tatapmuka); ?>"
                                    target="_blank" class="small-box-footer">
                                    <i class="fa fa-download"></i> Download
                                </a>
                            <?php else: ?>
                                <span class="small-box-footer" style="cursor: default;">
                                    <i class="fa fa-ban"></i> Tidak tersedia
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Materi Perkuliahan -->
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h4>Materi Perkuliahan</h4>
                                <p>
                                    <?php if(($dtbp->file_materi_kuliah) != null): ?>
                                        <i class="fa fa-check-circle"></i> Tersedia
                                    <?php else: ?>
                                        <i class="fa fa-times-circle"></i> Tidak tersedia
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-file-powerpoint-o"></i>
                            </div>
                            <?php if(($dtbp->file_materi_kuliah) != null): ?>
                                <a href="/File_BAP/<?php echo e($data->iddosen); ?>/<?php echo e($dtbp->id_kurperiode); ?>/Materi Kuliah/<?php echo e($dtbp->file_materi_kuliah); ?>"
                                    target="_blank" class="small-box-footer">
                                    <i class="fa fa-download"></i> Download
                                </a>
                            <?php else: ?>
                                <span class="small-box-footer" style="cursor: default;">
                                    <i class="fa fa-ban"></i> Tidak tersedia
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Materi Tugas -->
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h4>Materi Tugas</h4>
                                <p>
                                    <?php if(($dtbp->file_materi_tugas) != null): ?>
                                        <i class="fa fa-check-circle"></i> Tersedia
                                    <?php else: ?>
                                        <i class="fa fa-times-circle"></i> Tidak tersedia
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-tasks"></i>
                            </div>
                            <?php if(($dtbp->file_materi_tugas) != null): ?>
                                <a href="/File_BAP/<?php echo e($data->iddosen); ?>/<?php echo e($dtbp->id_kurperiode); ?>/Tugas Kuliah/<?php echo e($dtbp->file_materi_tugas); ?>"
                                    target="_blank" class="small-box-footer">
                                    <i class="fa fa-download"></i> Download
                                </a>
                            <?php else: ?>
                                <span class="small-box-footer" style="cursor: default;">
                                    <i class="fa fa-ban"></i> Tidak tersedia
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Link Materi -->
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h4>Link Materi</h4>
                                <p>
                                    <?php if(($dtbp->link_materi) != null): ?>
                                        <i class="fa fa-check-circle"></i> Tersedia
                                    <?php else: ?>
                                        <i class="fa fa-times-circle"></i> Tidak tersedia
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-link"></i>
                            </div>
                            <?php if(($dtbp->link_materi) != null): ?>
                                <a href="<?php echo e($dtbp->link_materi); ?>" target="_blank" class="small-box-footer">
                                    <i class="fa fa-external-link"></i> Buka Link
                                </a>
                            <?php else: ?>
                                <span class="small-box-footer" style="cursor: default;">
                                    <i class="fa fa-ban"></i> Tidak tersedia
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .margin-bottom {
            margin-bottom: 20px;
        }

        .description-header {
            font-size: 28px;
            font-weight: bold;
        }

        .description-text {
            text-transform: uppercase;
        }

        .small-box h4 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .small-box .icon {
            font-size: 60px;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/gugusmutu/perkuliahan/view_bap.blade.php ENDPATH**/ ?>