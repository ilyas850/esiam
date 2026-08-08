<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Master Matakuliah</h3>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalTambahMakul">
                    Tambah Matakuliah
                </button>

                <br><br>

                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Kode</center>
                            </th>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                <center>SKS Teori</center>
                            </th>
                            <th>
                                <center>SKS Praktek</center>
                            </th>
                            <th>
                                <center>Total SKS</center>
                            </th>
                            <th>
                                <center>Status</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td align="center"><?php echo e($no++); ?></td>
                                <td><?php echo e($item->kode); ?></td>
                                <td><?php echo e($item->makul); ?></td>
                                <td align="center"><?php echo e($item->akt_sks_teori); ?></td>
                                <td align="center"><?php echo e($item->akt_sks_praktek); ?></td>
                                <td align="center"><?php echo e($item->akt_sks_teori + $item->akt_sks_praktek); ?></td>
                                <td align="center">
                                    <?php if($item->active == 1): ?>
                                        <span class="label label-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="label label-danger">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td align="center">
                                    <button class="btn btn-success btn-xs" data-toggle="modal"
                                        data-target="#modalUpdateMakul<?php echo e($item->idmakul); ?>" title="klik untuk edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-xs" data-toggle="modal"
                                        data-target="#modalHapusMakul<?php echo e($item->idmakul); ?>" title="klik untuk hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalUpdateMakul<?php echo e($item->idmakul); ?>" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="<?php echo e(url('update_makul/' . $item->idmakul)); ?>" method="post">
                                        <?php echo e(csrf_field()); ?>

                                        <?php echo e(method_field('put')); ?>

                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title">Update Matakuliah</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Kode Matakuliah</label>
                                                    <input type="text" class="form-control" name="kode"
                                                        value="<?php echo e($item->kode); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Nama Matakuliah</label>
                                                    <input type="text" class="form-control" name="makul"
                                                        value="<?php echo e($item->makul); ?>" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>SKS Teori</label>
                                                            <input type="number" min="0" step="0.5"
                                                                class="form-control" name="akt_sks_teori"
                                                                value="<?php echo e($item->akt_sks_teori); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>SKS Praktek</label>
                                                            <input type="number" min="0" step="0.5"
                                                                class="form-control" name="akt_sks_praktek"
                                                                value="<?php echo e($item->akt_sks_praktek); ?>" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select class="form-control" name="active" required>
                                                        <option value="1" <?php echo e($item->active == 1 ? 'selected' : ''); ?>>
                                                            Aktif
                                                        </option>
                                                        <option value="0" <?php echo e($item->active == 0 ? 'selected' : ''); ?>>
                                                            Tidak Aktif
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="modal fade" id="modalHapusMakul<?php echo e($item->idmakul); ?>" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="<?php echo e(url('delete_makul')); ?>" method="post">
                                        <?php echo e(csrf_field()); ?>

                                        <input type="hidden" name="idmakul" value="<?php echo e($item->idmakul); ?>">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title">Nonaktifkan Matakuliah</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>Anda yakin akan menonaktifkan matakuliah <b><?php echo e($item->makul); ?></b>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-danger">Nonaktifkan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalTambahMakul" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?php echo e(url('simpan_makul')); ?>" method="post">
                <?php echo e(csrf_field()); ?>

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Tambah Matakuliah</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Kode Matakuliah</label>
                            <input type="text" class="form-control" name="kode" placeholder="Masukan kode matakuliah"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Nama Matakuliah</label>
                            <input type="text" class="form-control" name="makul"
                                placeholder="Masukan nama matakuliah" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>SKS Teori</label>
                                    <input type="number" min="0" step="0.5" class="form-control"
                                        name="akt_sks_teori" value="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>SKS Praktek</label>
                                    <input type="number" min="0" step="0.5" class="form-control"
                                        name="akt_sks_praktek" value="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="active" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/sadmin/masterakademik/master_makul.blade.php ENDPATH**/ ?>