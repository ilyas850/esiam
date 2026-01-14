<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Persentase Kehadiran Mahasiswa</b></h3>
                <table width="100%">
                    <tr>
                        <td>Matakuliah</td>
                        <td>:</td>
                        <td><?php echo e($mk->kode); ?> - <?php echo e($mk->makul); ?></td>
                    </tr>
                    <tr>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td><?php echo e($mk->prodi); ?></td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td><?php echo e($mk->kelas); ?></td>
                    </tr>
                </table>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                            class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>Kelas</th>
                                <th>
                                    Angkatan
                                </th>
                                <th>
                                    <center>Pertemuan</center>
                                </th>
                                <th>
                                    <center>Persentase</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($no++); ?></td>
                                    <td><?php echo e($item->nim); ?></td>
                                    <td><?php echo e($item->nama); ?></td>
                                    <td><?php echo e($item->prodi); ?></td>
                                    <td><?php echo e($item->kelas); ?></td>
                                    <td><?php echo e($item->angkatan); ?></td>
                                    <td align="center"><?php echo e($item->jml); ?> / <?php echo e($item->total); ?></td>
                                    <td align="center">
                                        <?php if($item->persentase_mhs <= 60): ?>
                                            <span class="label label-danger">
                                                <?php echo e($item->persentase_mhs); ?> %
                                            </span>
                                        <?php elseif($item->persentase_mhs <= 84): ?>
                                            <span class="label label-warning">
                                                <?php echo e($item->persentase_mhs); ?> %
                                            </span>
                                        <?php elseif($item->persentase_mhs >= 85 && $item->persentase_mhs < 100): ?>
                                            <span class="label label-success">
                                                <?php echo e($item->persentase_mhs); ?> %
                                            </span>
                                        <?php elseif($item->persentase_mhs == 100): ?>
                                            <span class="label label-info">
                                                <?php echo e($item->persentase_mhs); ?> %
                                            </span>
                                        <?php endif; ?>


                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosen/mhs/persentase_absen.blade.php ENDPATH**/ ?>