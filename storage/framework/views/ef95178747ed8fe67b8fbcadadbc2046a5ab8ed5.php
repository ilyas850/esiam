<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data KAPRODI Politeknik META Industri</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success mr-5" data-toggle="modal" data-target="#addpsi">
                            <i class="fa fa-plus"></i> Input Data KAPRODI
                        </button>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="addpsi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="<?php echo e(url('post_kaprodi')); ?>" enctype="multipart/form-data">
                            <?php echo e(csrf_field()); ?>

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Kaprodi</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Dosen</label>
                                        <select class="form-control" name="id_dosen">
                                            <option>-pilih-</option>
                                            <?php $__currentLoopData = $dosen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keydsn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($keydsn->iddosen); ?>"><?php echo e($keydsn->nama); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Program Studi</label>
                                        <select class="form-control" name="id_prodi">
                                            <option>-pilih-</option>
                                            <?php $__currentLoopData = $pd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyprd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($keyprd->id_prodi); ?>,<?php echo e($keyprd->kodeprodi); ?>">
                                                    <?php echo e($keyprd->prodi); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>NIK</center>
                            </th>
                            <th>
                                <center>Nama Dosen</center>
                            </th>
                            <th>
                                <center>Program Studi</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $kaprodi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <center><?php echo e($no++); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->nik); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->nama); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->prodi); ?></center>
                                </td>
                                <td>
                                    <center>
                                        <button class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#modalUpdateKaprodi<?php echo e($key->id_kaprodi); ?>"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger btn-xs" data-toggle="modal"
                                            data-target="#modalHapusKaprodi<?php echo e($key->id_kaprodi); ?>"
                                            title="klik untuk hapus"><i class="fa fa-trash"></i></button>
                                    </center>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalUpdateKaprodi<?php echo e($key->id_kaprodi); ?>" tabindex="-1"
                                aria-labelledby="modalUpdateKaprodi" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Kaprodi</h5>
                                        </div>
                                        <div class="modal-body">
                                            <!--FORM UPDATE Tingkat-->
                                            <form action="/put_kaprodi/<?php echo e($key->id_kaprodi); ?>" method="post">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('put'); ?>
                                                <div class="form-group">
                                                    <label>Nama Dosen</label>
                                                    <select class="form-control" name="id_dosen">
                                                        <option value="<?php echo e($key->id_dosen); ?>"><?php echo e($key->nama); ?>

                                                        </option>
                                                        <?php $__currentLoopData = $dosen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keydsn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($keydsn->iddosen); ?>"><?php echo e($keydsn->nama); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Program studi</label>
                                                    <select class="form-control" name="id_prodi">
                                                        <option value="<?php echo e($key->id_prodi); ?>,<?php echo e($key->kodeprodi); ?>">
                                                            <?php echo e($key->prodi); ?>

                                                        </option>
                                                        <?php $__currentLoopData = $pd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyprd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option
                                                                value="<?php echo e($keyprd->id_prodi); ?>,<?php echo e($keyprd->kodeprodi); ?>">
                                                                <?php echo e($keyprd->prodi); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <input type="hidden" name="updated_by" value="<?php echo e(Auth::user()->name); ?>">
                                                <button type="submit" class="btn btn-primary">Perbarui Data</button>
                                            </form>
                                            <!--END FORM Tingkat-->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modalHapusKaprodi<?php echo e($key->id_kaprodi); ?>" tabindex="-1"
                                aria-labelledby="modalHapusKaprodi" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <h4 class="text-center">Apakah anda yakin menghapus data kaprodi ini ?</h4>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="<?php echo e(url('hapuskaprodi')); ?>" method="post">
                                                <?php echo e(csrf_field()); ?>

                                                
                                                <input type="hidden" name="id_kaprodi" value="<?php echo e($key->id_kaprodi); ?>">
                                                <input type="hidden" name="id_dosen" value="<?php echo e($key->id_dosen); ?>">
                                                <button type="submit" class="btn btn-primary">Hapus data!</button>
                                            </form>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Batal</button>
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/sadmin/datadosen/kaprodi.blade.php ENDPATH**/ ?>