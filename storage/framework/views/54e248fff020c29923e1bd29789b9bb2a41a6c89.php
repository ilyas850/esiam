<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-th-list"></i> Data Matakuliah
                    <b><?php echo e($nama_periodetahun); ?> - <?php echo e($nama_periodetipe); ?></b>
                </h3>
            </div>
            <div class="box-body table-responsive">
                <table id="example8" class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr class="bg-teal">
                            <th class="text-center" width="4%" style="vertical-align: middle;">No</th>
                            <th class="text-center" width="25%" style="vertical-align: middle;">Matakuliah</th>
                            <th class="text-center" style="vertical-align: middle;">Kelas & Prodi</th>
                            <th class="text-center" style="vertical-align: middle;">Jadwal & Ruang</th>
                            <th class="text-center" width="20%" style="vertical-align: middle;">Status Soal (UTS / UAS)</th>
                            <th class="text-center" width="15%" style="vertical-align: middle;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $makul; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center" style="vertical-align: middle;"><?php echo e($no++); ?></td>
                                <td style="vertical-align: middle;">
                                    <span
                                        style="font-size: 15px; font-weight: bold; color: #3c8dbc;"><?php echo e($item->makul); ?></span><br>
                                    <small class="text-muted"><i class="fa fa-barcode"></i> <?php echo e($item->kode); ?></small>
                                </td>
                                <td style="vertical-align: middle;">
                                    <span class="label label-primary"><?php echo e($item->prodi); ?></span>
                                    <span class="label label-success"><?php echo e($item->kelas); ?></span>

                                    <?php if(isset($item->details) && count($item->details) > 0): ?>
                                        <div style="margin-top: 5px; font-size: 12px; color: #666;">
                                            <i class="fa fa-angle-right"></i> Konsentrasi:
                                            <ul style="padding-left: 15px; margin-bottom: 0;">
                                                <?php $__currentLoopData = $item->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($detail['konsentrasi']); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="vertical-align: middle;">
                                    <div style="margin-bottom: 3px;">
                                        <i class="fa fa-calendar text-blue"></i> <?php echo e($item->hari); ?>

                                    </div>
                                    <div style="margin-bottom: 3px;">
                                        <i class="fa fa-clock-o text-green"></i> <?php echo e($item->jam); ?>

                                    </div>
                                    <div>
                                        <i class="fa fa-map-marker text-red"></i> <b><?php echo e($item->nama_ruangan); ?></b>
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <!-- UTS SECTION -->
                                    <div class="row"
                                        style="margin-bottom: 5px; border-bottom: 1px dashed #ddd; padding-bottom: 5px;">
                                        <div class="col-xs-3 text-right"><b>UTS</b></div>
                                        <div class="col-xs-9">
                                            <?php if($item->soal_uts == null): ?>
                                                <button class="btn btn-default btn-xs" data-toggle="modal"
                                                    data-target="#modalUploadSoalUts<?php echo e($item->id_kurperiode); ?>">
                                                    <i class="fa fa-cloud-upload text-blue"></i> Upload
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-warning btn-xs" data-toggle="modal"
                                                    data-target="#modalUploadSoalUts<?php echo e($item->id_kurperiode); ?>" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <a href="/Soal Ujian/UTS/<?php echo e($item->id_kurperiode); ?>/<?php echo e($item->soal_uts); ?>"
                                                    target="_blank" class="btn btn-primary btn-xs" title="Lihat">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            <?php endif; ?>

                                            <!-- Validation Badge UTS -->
                                            <?php if($item->validasi_uts == 'SUDAH'): ?>
                                                <span class="badge bg-green" title="Valid"><i class="fa fa-check"></i></span>
                                            <?php elseif($item->validasi_uts == 'BELUM' or $item->validasi_uts == null): ?>
                                                <?php if($item->komentar_uts != null): ?>
                                                    <a class="btn btn-danger btn-xs" data-toggle="modal"
                                                        data-target="#modalTambahKomentarUts<?php echo e($item->id_soal); ?>"
                                                        title="Lihat Komentar"><i class="fa fa-comment"></i></a>
                                                <?php else: ?>
                                                    <span class="badge bg-yellow" title="Belum Valid"><i
                                                            class="fa fa-hourglass-half"></i></span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- UAS SECTION -->
                                    <div class="row">
                                        <div class="col-xs-3 text-right"><b>UAS</b></div>
                                        <div class="col-xs-9">
                                            <?php if($item->soal_uas == null): ?>
                                                <button class="btn btn-default btn-xs" data-toggle="modal"
                                                    data-target="#modalUploadSoalUas<?php echo e($item->id_kurperiode); ?>">
                                                    <i class="fa fa-cloud-upload text-blue"></i> Upload
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-warning btn-xs" data-toggle="modal"
                                                    data-target="#modalUploadSoalUas<?php echo e($item->id_kurperiode); ?>" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <a href="/Soal Ujian/UAS/<?php echo e($item->id_kurperiode); ?>/<?php echo e($item->soal_uas); ?>"
                                                    target="_blank" class="btn btn-primary btn-xs" title="Lihat">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            <?php endif; ?>

                                            <!-- Validation Badge UAS -->
                                            <?php if($item->validasi_uas == 'SUDAH'): ?>
                                                <span class="badge bg-green" title="Valid"><i class="fa fa-check"></i></span>
                                            <?php elseif($item->validasi_uas == 'BELUM' or $item->validasi_uas == null): ?>
                                                <?php if($item->komentar_uas != null): ?>
                                                    <a class="btn btn-danger btn-xs" data-toggle="modal"
                                                        data-target="#modalTambahKomentarUas<?php echo e($item->id_soal); ?>"
                                                        title="Lihat Komentar"><i class="fa fa-comment"></i></a>
                                                <?php else: ?>
                                                    <span class="badge bg-yellow" title="Belum Valid"><i
                                                            class="fa fa-hourglass-half"></i></span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center" style="vertical-align: middle;">
                                    <div style="margin-bottom: 5px;">
                                        <?php if($item->id_rps == null): ?>
                                            <a href="/entri_rps/<?php echo e($item->id_kurperiode); ?>"
                                                class="btn btn-success btn-xs btn-block">
                                                <i class="fa fa-plus"></i> Input RPS
                                            </a>
                                        <?php else: ?>
                                            <div class="btn-group">
                                                <a href="edit_rps/<?php echo e($item->id_kurperiode); ?>" class="btn btn-info btn-xs"
                                                    title="Edit RPS"><i class="fa fa-pencil"></i> RPS</a>
                                                <a href="cekmhs_dsn/<?php echo e($item->id_kurperiode); ?>" class="btn btn-primary btn-xs"
                                                    title="Entri Nilai"><i class="fa fa-list-ol"></i> Nilai</a>
                                                <a href="entri_bap/<?php echo e($item->id_kurperiode); ?>" class="btn btn-warning btn-xs"
                                                    title="BAP"><i class="fa fa-newspaper-o"></i> BAP</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="btn-group">
                                        <a href="/export_xlsnilai/<?php echo e($item->id_kurperiode); ?>" class="btn btn-default btn-xs"
                                            title="Export Excel">
                                            <i class="fa fa-file-excel-o text-green"></i>
                                        </a>
                                        <a href="/unduh_pdf_nilai/<?php echo e($item->id_kurperiode); ?>" class="btn btn-default btn-xs"
                                            title="Export PDF">
                                            <i class="fa fa-file-pdf-o text-red"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            

                            <!-- Modal Upload UTS -->
                            <div class="modal fade" id="modalUploadSoalUts<?php echo e($item->id_kurperiode); ?>" tabindex="-1"
                                role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-blue">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                    aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Upload Soal UTS - <?php echo e($item->makul); ?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="<?php echo e(url('simpan_soal_uts_dsn_dlm')); ?>" method="post"
                                                enctype="multipart/form-data">
                                                <?php echo e(csrf_field()); ?>

                                                <input type="hidden" name="id_kurperiode" value="<?php echo e($item->id_kurperiode); ?>">
                                                <div class="form-group">
                                                    <label>Tipe Ujian</label>
                                                    <select name="tipe_ujian_uts" class="form-control" required>
                                                        <?php if($item->tipe_ujian_uts): ?>
                                                            <option value="<?php echo e($item->tipe_ujian_uts); ?>"><?php echo e($item->tipe_ujian_uts); ?>

                                                        </option><?php endif; ?>
                                                        <option value="TATAP MUKA">TATAP MUKA</option>
                                                        <option value="TAKE HOME">TAKE HOME</option>
                                                        <option value="PROJECT">PROJECT</option>
                                                        <option value="PRAKTIKUM">PRAKTIKUM</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>File Soal UTS</label>
                                                    <input type="file" class="form-control" name="soal_uts"
                                                        accept="application/pdf">
                                                    <span class="help-block">Max. size 4 mb dengan format (.pdf)</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Cetak Soal</label>
                                                    <select name="cetak_soal_uts" class="form-control" required>
                                                        <?php if($item->cetak_soal_uts): ?>
                                                            <option value="<?php echo e($item->cetak_soal_uts); ?>"><?php echo e($item->cetak_soal_uts); ?>

                                                        </option><?php endif; ?>
                                                        <option value="YA">YA</option>
                                                        <option value="TIDAK">TIDAK</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Upload UAS -->
                            <div class="modal fade" id="modalUploadSoalUas<?php echo e($item->id_kurperiode); ?>" tabindex="-1"
                                role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-blue">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                    aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Upload Soal UAS - <?php echo e($item->makul); ?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="<?php echo e(url('simpan_soal_uas_dsn_dlm')); ?>" method="post"
                                                enctype="multipart/form-data">
                                                <?php echo e(csrf_field()); ?>

                                                <input type="hidden" name="id_kurperiode" value="<?php echo e($item->id_kurperiode); ?>">
                                                <div class="form-group">
                                                    <label>Tipe Ujian</label>
                                                    <select name="tipe_ujian_uas" class="form-control" required>
                                                        <?php if($item->tipe_ujian_uas): ?>
                                                            <option value="<?php echo e($item->tipe_ujian_uas); ?>"><?php echo e($item->tipe_ujian_uas); ?>

                                                        </option><?php endif; ?>
                                                        <option value="TATAP MUKA">TATAP MUKA</option>
                                                        <option value="TAKE HOME">TAKE HOME</option>
                                                        <option value="PROJECT">PROJECT</option>
                                                        <option value="PRAKTIKUM">PRAKTIKUM</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>File Soal UAS</label>
                                                    <input type="file" class="form-control" name="soal_uas"
                                                        accept="application/pdf">
                                                    <span class="help-block">Max. size 4 mb dengan format (.pdf)</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Cetak Soal</label>
                                                    <select name="cetak_soal_uas" class="form-control" required>
                                                        <?php if($item->cetak_soal_uas): ?>
                                                            <option value="<?php echo e($item->cetak_soal_uas); ?>"><?php echo e($item->cetak_soal_uas); ?>

                                                        </option><?php endif; ?>
                                                        <option value="YA">YA</option>
                                                        <option value="TIDAK">TIDAK</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Komentar UTS -->
                            <div class="modal fade" id="modalTambahKomentarUts<?php echo e($item->id_soal); ?>" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-red">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                    aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Komentar Validasi UTS</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Isi Komentar:</label>
                                                <textarea class="form-control" readonly cols="20"
                                                    rows="5"><?php echo e($item->komentar_uts); ?></textarea>
                                            </div>
                                            <button type="button" class="btn btn-default pull-right"
                                                data-dismiss="modal">Tutup</button>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Komentar UAS -->
                            <div class="modal fade" id="modalTambahKomentarUas<?php echo e($item->id_soal); ?>" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-red">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                    aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Komentar Validasi UAS</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Isi Komentar:</label>
                                                <textarea class="form-control" readonly cols="20"
                                                    rows="5"><?php echo e($item->komentar_uas); ?></textarea>
                                            </div>
                                            <button type="button" class="btn btn-default pull-right"
                                                data-dismiss="modal">Tutup</button>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosen/matakuliah/makul_diampu_dsn.blade.php ENDPATH**/ ?>