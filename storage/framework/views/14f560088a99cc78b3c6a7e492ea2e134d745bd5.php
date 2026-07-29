<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Admin Prodi Politeknik META Industri</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success mr-5" data-toggle="modal" data-target="#addpsi">
                            <i class="fa fa-plus"></i> Input Data Admin Prodi
                        </button>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="addpsi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="<?php echo e(url('post_adminprodi')); ?>" enctype="multipart/form-data">
                            <?php echo e(csrf_field()); ?>

                            <input type="hidden" name="role" value="9">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Admin Prodi</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Staff</label>
                                        <select class="form-control" name="id_user" required>
                                            <option>-pilih-</option>
                                            <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keystf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($keystf->idstaff); ?>,<?php echo e($keystf->nama); ?>">
                                                    <?php echo e($keystf->nama); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="text" name="password" class="form-control" required>
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
                                <center>Nama Staff</center>
                            </th>
                            <th>
                                <center>Username</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <center><?php echo e($no++); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->nama); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->username); ?></center>
                                </td>
                                <td>
                                    <center>
                                        <button class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#modalUpdateAdminprodi<?php echo e($key->id); ?>"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                        <a href="/hapusadminprodi/<?php echo e($key->id); ?>" class="btn btn-danger btn-xs"
                                            onclick="return confirm('apakah anda yakin akan menghapus user ini?')"><i
                                                class="fa fa-trash"></i></a>
                                        
                                    </center>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalUpdateAdminprodi<?php echo e($key->id); ?>" tabindex="-1"
                                aria-labelledby="modalUpdateAdminprodi" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Kaprodi</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/put_adminprodi/<?php echo e($key->id); ?>" method="post">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('put'); ?>
                                                <input type="hidden" name="updated_by" value="<?php echo e(Auth::user()->name); ?>">
                                                <div class="form-group">
                                                    <label>Nama Staff</label>
                                                    <select class="form-control" name="id_user">
                                                        <option value="<?php echo e($key->idstaff); ?>,<?php echo e($key->nama); ?>">
                                                            <?php echo e($key->nama); ?></option>
                                                        <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keystf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($keystf->idstaff); ?>,<?php echo e($keystf->nama); ?>">
                                                                <?php echo e($keystf->nama); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Username</label>
                                                    <input type="text" name="username" class="form-control"
                                                        value="<?php echo e($key->username); ?>">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Perbarui Data</button>
                                            </form>
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/sadmin/user/adminprodi.blade.php ENDPATH**/ ?>