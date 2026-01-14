<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Matakuliah <b> <?php echo e($nama_periodetahun); ?> - <?php echo e($nama_periodetipe); ?> </b></h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Kode - Matakuliah</center>
                            </th>
                            <th>
                                <center>Program Studi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Semester</center>
                            </th>
                            <th>
                                <center>Ruangan</center>
                            </th>
                            <th>
                                <center>Jadwal</center>
                            </th>
                            <th>
                                <center>Soal</center>
                            </th>
                            <th>
                                <center>Ket. UTS</center>
                            </th>
                            <th>
                                <center>Ket. UAS</center>
                            </th>
                            <th>
                                <center>Entri (Nilai/BAP)</center>
                            </th>
                            <th>
                                <center>Export</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $makul; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <center><?php echo e($no++); ?></center>
                                </td>
                                <td><?php echo e($item->kode); ?> - <?php echo e($item->makul); ?></td>
                                <td>
                                    <?php echo e($item->prodi); ?>

                                </td>
                                <td>
                                    <?php echo e($item->kelas); ?>

                                </td>
                                <td>
                                    <center><?php echo e($item->semester); ?></center>
                                </td>
                                <td><?php echo e($item->nama_ruangan); ?></td>
                                <td><?php echo e($item->hari); ?>, <?php echo e($item->jam); ?></td>
                                <td>
                                    <?php if($item->soal_uts == null): ?>
                                        <button class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#modalUploadSoalUts<?php echo e($item->id_kurperiode); ?>">
                                            <i class="fa fa-cloud-upload" title="Klik untuk upload soal uts"></i>
                                            UTS</button>
                                    <?php else: ?>
                                        <button class="btn btn-warning btn-xs" data-toggle="modal"
                                            data-target="#modalUploadSoalUts<?php echo e($item->id_kurperiode); ?>"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button><a
                                            href="/Soal Ujian/UTS/<?php echo e($item->id_kurperiode); ?>/<?php echo e($item->soal_uts); ?>"
                                            target="_blank" style="font: white">UTS</a>
                                    <?php endif; ?>
                                    <br>
                                    <?php if($item->soal_uas == null): ?>
                                        <button class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#modalUploadSoalUas<?php echo e($item->id_kurperiode); ?>"><i
                                                class="fa fa-cloud-upload" title="Klik untuk upload soal uas"></i>
                                            UAS</button>
                                    <?php else: ?>
                                        <button class="btn btn-warning btn-xs" data-toggle="modal"
                                            data-target="#modalUploadSoalUas<?php echo e($item->id_kurperiode); ?>"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button><a
                                            href="/Soal Ujian/UAS/<?php echo e($item->id_kurperiode); ?>/<?php echo e($item->soal_uas); ?>"
                                            target="_blank" style="font: white">UAS</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <center>
                                        <?php if($item->validasi_uts == 'BELUM' or $item->validasi_uts == null): ?>
                                            <?php if($item->komentar_uts == null): ?>
                                            <?php else: ?>
                                                <a class="btn btn-danger btn-xs" data-toggle="modal"
                                                    data-target="#modalTambahKomentarUts<?php echo e($item->id_soal); ?>">Komentar</a>
                                            <?php endif; ?>
                                        <?php elseif($item->validasi_uts == 'SUDAH'): ?>
                                            <span class="badge bg-blue">Valid</span>
                                        <?php endif; ?>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <?php if($item->validasi_uas == 'BELUM' or $item->validasi_uas == null): ?>
                                            <?php if($item->komentar_uas == null): ?>
                                            <?php else: ?>
                                                <a class="btn btn-danger btn-xs" data-toggle="modal"
                                                    data-target="#modalTambahKomentarUas<?php echo e($item->id_soal); ?>">Komentar</a>
                                            <?php endif; ?>
                                        <?php elseif($item->validasi_uas == 'SUDAH'): ?>
                                            <span class="badge bg-blue">Valid</span>
                                        <?php endif; ?>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <?php if($item->id_rps == null): ?>
                                            <a href="/entri_rps_dsnlr/<?php echo e($item->id_kurperiode); ?>"
                                                class="btn btn-success btn-xs">RPS</a>
                                        <?php elseif($item->id_rps != null): ?>
                                        <a href="cekmhs/<?php echo e($item->id_kurperiode); ?>" class="btn btn-info btn-xs"
                                            title="Klik untuk entri nilai">Nilai</a>
                                        </a>
                                        <a href="entri_bap_dsn/<?php echo e($item->id_kurperiode); ?>" class="btn btn-warning btn-xs"
                                            title="Klik untuk entri nilai">
                                            BAP</a>
                                            <a href="edit_rps_dsnlr/<?php echo e($item->id_kurperiode); ?>" class="btn btn-success btn-xs"
                                                title="Klik untuk edit RPS">
                                                Edit RPS </a>
                                        <?php endif; ?>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="/export_xlsnilai_dsn/<?php echo e($item->id_kurperiode); ?>"
                                            class="btn btn-success btn-xs"><i class="fa fa-file-excel-o"
                                                title="Klik untuk export nilai .xls">
                                            </i></a>
                                        <a href="/unduh_pdf_nilai_dsn/<?php echo e($item->id_kurperiode); ?>"
                                            class="btn btn-danger btn-xs"><i class="fa fa-file-pdf-o"
                                                title="Klik untuk export nilai .pdf">
                                            </i></a>
                                    </center>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalUploadSoalUts<?php echo e($item->id_kurperiode); ?>" tabindex="-1"
                                aria-labelledby="modalUploadSoalUts" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Upload Soal UTS <?php echo e($item->makul); ?></h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="<?php echo e(url('simpan_soal_uts_dsn_luar')); ?>" method="post"
                                                enctype="multipart/form-data">
                                                <?php echo e(csrf_field()); ?>

                                                <input type="hidden" name="id_kurperiode"
                                                    value="<?php echo e($item->id_kurperiode); ?>">
                                                <div class="form-group">
                                                    <label>Tipe Ujian</label>
                                                    <select name="tipe_ujian_uts" class="form-control" required>
                                                        <option value="<?php echo e($item->tipe_ujian_uts); ?>">
                                                            <?php echo e($item->tipe_ujian_uts); ?></option>
                                                        <option value="TATAP MUKA">TATAP MUKA</option>
                                                        <option value="TAKE HOME">TAKE HOME</option>
                                                        <option value="PROJECT">PROJECT</option>
                                                        <option value="PRAKTIKUM">PRAKTIKUM</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>File Soal UTS</label>
                                                    <input type="file" class="form-control" name="soal_uts" required>
                                                    <span>Max. size 4 mb dengan format (.pdf) atau (.doc)</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Cetak Soal</label>
                                                    <select name="cetak_soal_uts" class="form-control" required>
                                                        <option value="<?php echo e($item->cetak_soal_uts); ?>">
                                                            <?php echo e($item->cetak_soal_uts); ?></option>
                                                        <option value="YA">YA</option>
                                                        <option value="TIDAK">TIDAK</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="modalUploadSoalUas<?php echo e($item->id_kurperiode); ?>" tabindex="-1"
                                aria-labelledby="modalUploadSoalUas" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Upload Soal UAS <?php echo e($item->makul); ?></h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="<?php echo e(url('simpan_soal_uas_dsn_luar')); ?>" method="post"
                                                enctype="multipart/form-data">
                                                <?php echo e(csrf_field()); ?>

                                                <input type="hidden" name="id_kurperiode"
                                                    value="<?php echo e($item->id_kurperiode); ?>">
                                                <div class="form-group">
                                                    <label>Tipe Ujian</label>
                                                    <select name="tipe_ujian_uas" class="form-control" required>
                                                        <option value="<?php echo e($item->tipe_ujian_uas); ?>">
                                                            <?php echo e($item->tipe_ujian_uas); ?></option>
                                                        <option value="TATAP MUKA">TATAP MUKA</option>
                                                        <option value="TAKE HOME">TAKE HOME</option>
                                                        <option value="PROJECT">PROJECT</option>
                                                        <option value="PRAKTIKUM">PRAKTIKUM</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>File Soal UAS</label>
                                                    <input type="file" class="form-control" name="soal_uas">
                                                    <span>Max. size 4 mb dengan format (.pdf) atau (.doc)</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Cetak Soal</label>
                                                    <select name="cetak_soal_uas" class="form-control" required>
                                                        <option value="<?php echo e($item->cetak_soal_uas); ?>">
                                                            <?php echo e($item->cetak_soal_uas); ?></option>
                                                        <option value="YA">YA</option>
                                                        <option value="TIDAK">TIDAK</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="modalTambahKomentarUts<?php echo e($item->id_soal); ?>" tabindex="-1"
                                aria-labelledby="modalTambahKomentarUts" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Komentar</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <textarea class="form-control" name="komentar_uts" cols="20" rows="10"> <?php echo e($item->komentar_uts); ?> </textarea>
                                            </div>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="modalTambahKomentarUas<?php echo e($item->id_soal); ?>" tabindex="-1"
                                aria-labelledby="modalTambahKomentarUas" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Komentar</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <textarea class="form-control" name="komentar_uas" cols="20" rows="10"> <?php echo e($item->komentar_uas); ?> </textarea>
                                            </div>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Tutup</button>
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosenluar/makul_diampu.blade.php ENDPATH**/ ?>