<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Jadwal Kuliah</h3>
                        <div class="box-tools">
                            <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control pull-right"
                                    placeholder="Search">

                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-striped">
                            <tr>
                                <th>No</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Matakuliah</th>
                                <th>Ruangan</th>
                                <th>Dosen</th>
                                <th>Kehadiran</th>
                                <th>Persentase Absen</th>
                                <th>Cek Absen</th>
                            </tr>

                            <?php $no = 1; ?>
                            <?php $__currentLoopData = $jadwal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($no++); ?></td>
                                    <td><?php echo e($item->hari); ?></td>
                                    <td><?php echo e($item->jam); ?></td>
                                    <td><?php echo e($item->makul); ?></td>
                                    <td><?php echo e($item->nama_ruangan); ?></td>
                                    <td><?php echo e($item->nama); ?></td>
                                    <td align="center"><?php echo e($item->jml); ?> / <?php echo e($item->total); ?></td>
                                    <td align="center">
                                        <?php if($item->persentase <= 60): ?>
                                            <span class="label label-danger">
                                                <?php echo e($item->persentase); ?> %
                                            </span>
                                        <?php elseif($item->persentase <= 84): ?>
                                            <span class="label label-warning">
                                                <?php echo e($item->persentase); ?> %
                                            </span>
                                        <?php elseif($item->persentase >= 85 && $item->persentase < 100): ?>
                                            <span class="label label-success">
                                                <?php echo e($item->persentase); ?> %
                                            </span>
                                        <?php elseif($item->persentase == 100): ?>
                                            <span class="label label-info">
                                                <?php echo e($item->persentase); ?> %
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <center>
                                            <a href="/lihatabsen/<?php echo e($item->id_kurperiode); ?>"
                                                class="btn btn-info btn-xs">Lihat</a>
                                        </center>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/mhs/jadwal.blade.php ENDPATH**/ ?>