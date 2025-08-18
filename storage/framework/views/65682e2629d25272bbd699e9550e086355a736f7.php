<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <section class="content-header">
        <h1>
            Data User Dosen
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>

            <li class="active">Data User Dosen</li>
        </ol>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data User Dosen Politeknik META Industri</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">
                                <center>No</center>
                            </th>
                            <th width="35%">
                                <center>Nama Dosen</center>
                            </th>
                            <th>
                                <center>NIK</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $user_dsn; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keydsn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <center><?php echo e($no++); ?></center>
                                </td>
                                <td><?php echo e($keydsn->nama); ?>, <?php echo e($keydsn->akademik); ?></td>
                                <td>
                                    <center><?php echo e($keydsn->nik); ?></center>
                                </td>
                                <td>
                                    <center>

                                        <?php if($keydsn->username == null): ?>
                                            <form action="<?php echo e(url('saveuser_dsn')); ?>" method="post">
                                                <input type="hidden" name="role" value="2">
                                                <input type="hidden" name="id_user" value="<?php echo e($keydsn->iddosen); ?>">
                                                <input type="hidden" name="username" value="<?php echo e($keydsn->nik); ?>">
                                                <input type="hidden" name="name" value="<?php echo e($keydsn->nama); ?>">
                                                <?php echo e(csrf_field()); ?>

                                                <button type="submit" class="btn btn-success btn-xs" data-toggle="tooltip"
                                                    data-placement="right">Generate</button>
                                            </form>
                                        <?php elseif($keydsn->username != null): ?>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-warning btn-xs">Pilih</button>
                                                <button type="button" class="btn btn-warning btn-xs dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <form method="POST" action="<?php echo e(url('resetuserdsn')); ?>">
                                                            <input type="hidden" name="password"
                                                                value="<?php echo e($keydsn->username); ?>">
                                                            <input type="hidden" name="id" value="<?php echo e($keydsn->id); ?>">
                                                            <?php echo e(csrf_field()); ?>

                                                            <button type="submit" class="btn btn-success btn-block btn-xs"
                                                                data-toggle="tooltip" data-placement="right">Reset</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="/hapususerdsn/<?php echo e($keydsn->id_user); ?>"
                                                            method="post">
                                                            <button class="btn btn-danger btn-block btn-xs"
                                                                title="klik untuk hapus" type="submit" name="submit"
                                                                onclick="return confirm('apakah anda yakin akan menghapus user ini?')">Hapus</button>
                                                            <?php echo e(csrf_field()); ?>

                                                            <input type="hidden" name="_method" value="DELETE">
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </center>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/sadmin/data_user_dosen.blade.php ENDPATH**/ ?>