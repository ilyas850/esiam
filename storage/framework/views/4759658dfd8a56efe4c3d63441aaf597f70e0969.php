<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-eye"></i> Detail Laporan BAP</h3>
                <div class="box-tools pull-right">
                    <a class="btn btn-warning btn-sm btn-flat" href="/print_bap/<?php echo e($dtbp->id_bap); ?>" target="_blank">
                        <i class="fa fa-print"></i> Cetak BAP
                    </a>
                    <a class="btn btn-default btn-sm btn-flat" href="/entri_bap/<?php echo e($dtbp->id_kurperiode); ?>">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="box-body">
                <!-- SECTION 1: INFORMASI UMUM -->
                <h4 class="text-light-blue" style="border-bottom: 2px solid #ddd; padding-bottom: 5px; margin-top: 0;">
                    <i class="fa fa-info-circle"></i> Informasi Umum
                </h4>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Program Studi</label>
                            <div class="form-control-static"><?php echo e($prd); ?></div>
                        </div>
                    </div>
                     <div class="col-md-3">
                        <div class="form-group">
                            <label>Semester</label>
                            <div class="form-control-static"><?php echo e($tipe); ?> – <?php echo e($tahun); ?></div>
                        </div>
                    </div>
                     <div class="col-md-3">
                        <div class="form-group">
                            <label>Kelas / Semester</label>
                            <div class="form-control-static"><?php echo e($data->kelas); ?> / <?php echo e($data->semester); ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Matakuliah</label>
                            <div class="form-control-static" style="font-weight: bold; font-size: 16px;"><?php echo e($data->makul); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Dosen Pengampu</label>
                            <div class="form-control-static"><?php echo e($data->nama); ?></div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: WAKTU PELAKSANAAN -->
                <h4 class="text-light-blue" style="border-bottom: 2px solid #ddd; padding-bottom: 5px; margin-top: 20px;">
                    <i class="fa fa-calendar"></i> Waktu Pelaksanaan
                </h4>
                
                <div class="row">
                    <div class="col-md-3">
                         <div class="form-group">
                            <label>Pertemuan Ke-</label>
                            <div class="form-control-static"><span class="badge bg-blue"><?php echo e($dtbp->pertemuan); ?></span></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <div class="form-control-static"><?php echo e(Carbon\Carbon::parse($dtbp->tanggal)->formatLocalized('%d %B %Y')); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                         <div class="form-group">
                            <label>Waktu</label>
                            <div class="form-control-static"><?php echo e($dtbp->jam_mulai); ?> - <?php echo e($dtbp->jam_selsai); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                         <div class="form-group">
                            <label>Kehadiran Mahasiswa</label>
                            <div class="form-control-static">
                                <span class="text-green h4"><?php echo e($dtbp->hadir); ?></span> Hadir / 
                                <span class="text-red h4"><?php echo e($dtbp->tidak_hadir); ?></span> Absen
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: KONTEN MATERI -->
                <h4 class="text-light-blue" style="border-bottom: 2px solid #ddd; padding-bottom: 5px; margin-top: 10px;">
                    <i class="fa fa-file-text-o"></i> Konten Materi
                </h4>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Materi Perkuliahan</label>
                            <div class="well well-sm" style="min-height: 100px; background-color: #f9f9f9;">
                                <?php echo nl2br(e($dtbp->materi_kuliah)); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Media Pembelajaran</label>
                            <div class="well well-sm" style="min-height: 100px; background-color: #f9f9f9;">
                                <?php echo nl2br(e($dtbp->media_pembelajaran)); ?>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: BUKTI & LAMPIRAN -->
                <h4 class="text-light-blue" style="border-bottom: 2px solid #ddd; padding-bottom: 5px; margin-top: 10px;">
                    <i class="fa fa-paperclip"></i> Bukti & Lampiran
                </h4>

                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box bg-aqua">
                            <span class="info-box-icon"><i class="fa fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Kuliah Tatap Muka</span>
                                <span class="info-box-number" style="font-weight: normal; font-size: 14px; margin-top: 5px;">
                                    <?php if($dtbp->file_kuliah_tatapmuka != null): ?>
                                        <a href="/File_BAP/<?php echo e($data->iddosen); ?>/<?php echo e($dtbp->id_kurperiode); ?>/Kuliah Tatap Muka/<?php echo e($dtbp->file_kuliah_tatapmuka); ?>" target="_blank" style="color: white; text-decoration: underline;">
                                            <i class="fa fa-download"></i> Lihat Lampiran
                                        </a>
                                    <?php else: ?>
                                        Tidak ada lampiran
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="fa fa-book"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Materi Perkuliahan</span>
                                <span class="info-box-number" style="font-weight: normal; font-size: 14px; margin-top: 5px;">
                                    <?php if($dtbp->file_materi_kuliah != null): ?>
                                        <a href="/File_BAP/<?php echo e($data->iddosen); ?>/<?php echo e($dtbp->id_kurperiode); ?>/Materi Kuliah/<?php echo e($dtbp->file_materi_kuliah); ?>" target="_blank" style="color: white; text-decoration: underline;">
                                            <i class="fa fa-download"></i> Lihat Materi
                                        </a>
                                    <?php elseif($dtbp->link_materi != null): ?>
                                        <a href="<?php echo e($dtbp->link_materi); ?>" target="_blank" style="color: white; text-decoration: underline;">
                                            <i class="fa fa-link"></i> Link Materi
                                        </a>
                                    <?php else: ?>
                                        Tidak ada lampiran
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-yellow">
                            <span class="info-box-icon"><i class="fa fa-pencil"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Materi Tugas</span>
                                <span class="info-box-number" style="font-weight: normal; font-size: 14px; margin-top: 5px;">
                                    <?php if($dtbp->file_materi_tugas != null): ?>
                                        <a href="/File_BAP/<?php echo e($data->iddosen); ?>/<?php echo e($dtbp->id_kurperiode); ?>/Tugas Kuliah/<?php echo e($dtbp->file_materi_tugas); ?>" target="_blank" style="color: white; text-decoration: underline;">
                                            <i class="fa fa-download"></i> Lihat Tugas
                                        </a>
                                    <?php else: ?>
                                        Tidak ada lampiran
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="box-footer">
                <a class="btn btn-default btn-flat" href="/entri_bap/<?php echo e($dtbp->id_kurperiode); ?>">
                     Kembali ke Daftar BAP
                </a>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosen/view_bap.blade.php ENDPATH**/ ?>