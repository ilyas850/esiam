<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <section class="content-header">
        <h1>
            Data Approve Dosen Pembimbing
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li class="active">Data KRS Mahasiswa pembimbing</li>
        </ol>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Approve KRS Dosen Pembimbing</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="form" role="form" action="<?php echo e(url('view_krs')); ?>" method="POST">
                        <?php echo e(csrf_field()); ?>

                        <div class="col-xs-4">
                            <select class="form-control" name="remark">
                                <option>-pilih status-</option>
                                <option value="1">Sudah divalidasi</option>
                                <option value="0">Belum divalidasi</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success ">Tampilkan</button>
                    </form>
                </div>
                <br>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>

                            <th>
                                <center>NIM</center>
                            </th>
                            <th>
                                <center>Nama Mahasiswa</center>
                            </th>
                            <th>
                                <center>Program Studi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Angkatan</center>
                            </th>
                            <th>
                                <center>Dosen Pembimbing</center>
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
                        <?php $__currentLoopData = $appr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <center><?php echo e($no++); ?></center>
                                </td>

                                <td>
                                    <center><?php echo e($app->nim); ?></center>
                                </td>
                                <td><?php echo e($app->nama); ?></td>
                                <td>
                                    <?php echo e($app->prodi); ?>

                                </td>
                                <td>
                                    <center>
                                        <?php echo e($app->kelas); ?>

                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <?php echo e($app->angkatan); ?>

                                    </center>
                                <td>
                                    <?php echo e($app->nama_dsn); ?>

                                </td>
                                <td>
                                    <center>
                                        <?php if($app->remark == 1): ?>
                                            <span class="badge bg-green">Valid</span>
                                        <?php elseif($app->remark == 0 or $app->remark == null): ?>
                                            <span class="badge bg-yellow">Belum</span>
                                        <?php endif; ?>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <?php if($app->remark == 1): ?>
                                            <a class="btn btn-danger btn-xs" href="/batal_krs_admin/<?php echo e($app->id_student); ?>"
                                                title="Klik untuk batal validasi"><i class="fa fa-close"></i>
                                            </a>
                                        <?php elseif($app->remark == 0): ?>
                                            <a class="btn btn-success btn-xs"
                                                href="/validasi_krs_admin/<?php echo e($app->id_student); ?>"
                                                title="Klik untuk validasi"><i class="fa fa-check"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a class="btn btn-info btn-xs" href="/cek_krs_admin/<?php echo e($app->id_student); ?>"
                                            title="Klik untuk cek KRS"><i class="fa fa-eye"></i></a>
                                        <a class="btn btn-warning btn-xs"
                                            href="/cek_makul_mengulang_admin/<?php echo e($app->id_student); ?>"><i
                                                class="fa fa-repeat" title="Klik untuk cek matakuliah mengulang"></i></a>
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/sadmin/approv.blade.php ENDPATH**/ ?>