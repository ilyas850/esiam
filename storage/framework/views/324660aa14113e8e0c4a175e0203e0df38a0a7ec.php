<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <?php if(count($errors) > 0): ?>
            <div class="alert alert-dangeralert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-ban"></i> Validasi Upload Error</h4>
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
        <?php endif; ?>

            <form method="POST" action="<?php echo e(url('save_bap')); ?>" enctype="multipart/form-data">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id_kurperiode" value="<?php echo e($id); ?>">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-edit"></i> Form Input Berita Acara Perkuliahan</h3>
                    </div>

                    <div class="box-body">
                        <!-- SECTION 1: REFERENSI RPS -->
                        <h4 class="text-light-blue" style="border-bottom: 2px solid #ddd; padding-bottom: 5px; margin-top: 0;">
                            <i class="fa fa-book"></i> Referensi RPS
                        </h4>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><span class="text-red">*</span> Pilih Pertemuan RPS</label>
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#modalPilihRps">
                                                <i class="fa fa-list"></i> Pilih RPS
                                            </button>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Silahkan klik tombol Pilih RPS" disabled>
                                    </div>
                                    <input type="hidden" id="selectedRpsId" name="id_rps">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kemampuan Akhir yang Direncanakan</label>
                                    <textarea id="kemampuanAkhir" class="form-control" name="kemampuan_akhir_direncanakan" rows="4" readonly required style="background-color: #f4f4f4;"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Materi Pembelajaran (Sesuai RPS)</label>
                                    <textarea id="materiPembelajaran" class="form-control" name="materi_pembelajaran" rows="4" readonly required style="background-color: #f4f4f4;"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: WAKTU PELAKSANAAN -->
                        <h4 class="text-light-blue" style="border-bottom: 2px solid #ddd; padding-bottom: 5px; margin-top: 20px;">
                            <i class="fa fa-calendar"></i> Waktu Pelaksanaan
                        </h4>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group <?php echo e($errors->has('pertemuan') ? 'has-error' : ''); ?>">
                                    <label><span class="text-red">*</span> Pertemuan Ke-</label>
                                    <select class="form-control" name="pertemuan" required>
                                        <option value="">-- Pilih --</option>
                                        <?php $__currentLoopData = $nilai_pertemuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id_pertemuan); ?>">Pertemuan Ke-<?php echo e($item->id_pertemuan); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php if($errors->has('pertemuan')): ?>
                                        <span class="help-block"><?php echo e($errors->first('pertemuan')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group <?php echo e($errors->has('tanggal') ? 'has-error' : ''); ?>">
                                    <label><span class="text-red">*</span> Tanggal</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="date" class="form-control pull-right" name="tanggal" required>
                                    </div>
                                    <?php if($errors->has('tanggal')): ?>
                                        <span class="help-block"><?php echo e($errors->first('tanggal')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group <?php echo e($errors->has('jam_mulai') ? 'has-error' : ''); ?>">
                                    <label><span class="text-red">*</span> Jam Mulai</label>
                                    <select class="form-control" name="jam_mulai" required>
                                        <option value="">-- Pilih --</option>
                                        <?php $__currentLoopData = $jam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key->jam); ?>"><?php echo e($key->jam); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php if($errors->has('jam_mulai')): ?>
                                        <span class="help-block"><?php echo e($errors->first('jam_mulai')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group <?php echo e($errors->has('jam_selsai') ? 'has-error' : ''); ?>">
                                    <label><span class="text-red">*</span> Jam Selesai</label>
                                    <select class="form-control" name="jam_selsai" required>
                                        <option value="">-- Pilih --</option>
                                        <?php $__currentLoopData = $jam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key->jam); ?>"><?php echo e($key->jam); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php if($errors->has('jam_selsai')): ?>
                                        <span class="help-block"><?php echo e($errors->first('jam_selsai')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: DETAIL KULIAH -->
                        <h4 class="text-light-blue" style="border-bottom: 2px solid #ddd; padding-bottom: 5px; margin-top: 10px;">
                            <i class="fa fa-info-circle"></i> Detail Perkuliahan
                        </h4>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><span class="text-red">*</span> Jenis Kuliah</label>
                                    <select class="form-control" name="jenis_kuliah" required>
                                        <option value="Kuliah">Kuliah</option>
                                        <option value="UTS">UTS</option>
                                        <option value="UAS">UAS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><span class="text-red">*</span> Tipe Kuliah</label>
                                    <?php if($cek_mk->akt_sks_praktek == 0): ?>
                                        <select class="form-control" name="id_tipekuliah" required>
                                            <option value="1">Teori</option>
                                        </select>
                                    <?php elseif($cek_mk->akt_sks_praktek > 0): ?>
                                        <select class="form-control" name="id_tipekuliah" required>
                                            <option value="1">Teori</option>
                                            <option value="2">Praktikum</option>
                                            <option value="3">Teori + Praktikum</option>
                                        </select>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><span class="text-red">*</span> Metode</label>
                                    <select class="form-control" name="metode_kuliah" required>
                                        <option value="Offline">Offline</option>
                                        <option value="Online">Online</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><span class="text-red">*</span> Link Materi</label>
                                    <input type="text" class="form-control" name="link_materi" placeholder="https://..." required>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: KONTEN MATERI -->
                        <h4 class="text-light-blue" style="border-bottom: 2px solid #ddd; padding-bottom: 5px; margin-top: 10px;">
                            <i class="fa fa-file-text"></i> Konten Materi
                        </h4>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span class="text-red">*</span> Aktual Materi Pembelajaran</label>
                                    <textarea class="form-control" rows="4" name="materi_kuliah" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span class="text-red">*</span> Alasan Pembaharuan Materi</label>
                                    <textarea class="form-control" rows="4" name="alasan_pembaharuan_materi" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        <?php if($cek_mk->akt_sks_praktek > 0): ?> <span class="text-red">*</span> <?php endif; ?> 
                                        Aktual Materi Praktikum
                                    </label>
                                    <textarea class="form-control" rows="4" name="praktikum" <?php if($cek_mk->akt_sks_praktek > 0): ?> required <?php endif; ?>></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span class="text-red">*</span> Media Pembelajaran</label>
                                    <textarea class="form-control" rows="4" name="media_pembelajaran" required></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 5: UPLOAD BUKTI -->
                        <h4 class="text-light-blue" style="border-bottom: 2px solid #ddd; padding-bottom: 5px; margin-top: 10px;">
                            <i class="fa fa-cloud-upload"></i> Upload Bukti
                        </h4>

                        <div class="row">
                            <div class="col-md-6">
                                 <div class="form-group <?php echo e($errors->has('file_kuliah_tatapmuka') ? 'has-error' : ''); ?>">
                                    <label>Upload File Kuliah Tatap Muka</label>
                                    <input type="file" class="form-control" name="file_kuliah_tatapmuka">
                                    <span class="help-block">Max. size 2 mb dengan format .jpg .jpeg .png</span>
                                    <?php if($errors->has('file_kuliah_tatapmuka')): ?>
                                        <span class="help-block"><?php echo e($errors->first('file_kuliah_tatapmuka')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo e($errors->has('file_materi_tugas') ? 'has-error' : ''); ?>">
                                    <label>Upload File Materi Tugas</label>
                                    <input type="file" class="form-control" name="file_materi_tugas">
                                    <span class="help-block">Max. size 2 mb dengan format .jpg .jpeg .png</span>
                                    <?php if($errors->has('file_materi_tugas')): ?>
                                        <span class="help-block"><?php echo e($errors->first('file_materi_tugas')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Simpan BAP</button>
                        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-default btn-flat pull-right">Batal</a>
                    </div>
                </div>
            </form>

            <!-- MODAL PILIH RPS -->
            <div class="modal fade" id="modalPilihRps" tabindex="-1" role="dialog" aria-labelledby="modalPilihRpsLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="modalPilihRpsLabel">Pilih Pertemuan dari RPS</h4>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">Pilih</th>
                                            <th width="15%">Pertemuan</th>
                                            <th>Kemampuan Akhir yang Direncanakan</th>
                                            <th>Materi Pembelajaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $rps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="text-center">
                                                    <input type="radio" name="radio_rps" value="<?php echo e($item->id_rps); ?>" class="rps-radio">
                                                </td>
                                                <td>Ke-<?php echo e($item->pertemuan); ?></td>
                                                <td><?php echo e($item->kemampuan_akhir_direncanakan); ?></td>
                                                <td><?php echo e($item->materi_pembelajaran); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary" id="btnPilihRps">Gunakan Terpilih</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function () {
            // Ketika tombol "Gunakan Terpilih" di modal diklik
            $('#btnPilihRps').click(function() {
                var selected = $('input[name="radio_rps"]:checked');
                if (selected.length > 0) {
                    var row = selected.closest('tr');
                    var rpsId = selected.val();
                    var kemampuan = row.find('td:nth-child(3)').text();
                    var materi = row.find('td:nth-child(4)').text();

                    // Isi field di form utama
                    $('#selectedRpsId').val(rpsId);
                    $('#kemampuanAkhir').val(kemampuan);
                    $('#materiPembelajaran').val(materi);

                    // Tutup modal
                    $('#modalPilihRps').modal('hide');
                } else {
                    alert('Silahkan pilih salah satu pertemuan terlebih dahulu.');
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosen/form_bap.blade.php ENDPATH**/ ?>