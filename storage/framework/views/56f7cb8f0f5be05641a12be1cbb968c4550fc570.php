<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Kategori Pengajuan</h3>
                    </div>
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <center>No</center>
                                    </th>
                                    <th>
                                        <center>Kategori</center>
                                    </th>
                                    <th>
                                        <center>Jumlah</center>
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
                                        <td align="center">
                                            <center><?php echo e($no++); ?></center>
                                        </td>
                                        <td>
                                            <?php echo e($item->kategori); ?>

                                        </td>
                                        <td align="center">
                                            <?php echo e($item->jml_pengajuan); ?>

                                        </td>
                                        <td align="center">
                                            <center>
                                                <a class="btn btn-danger btn-xs"
                                                    href="/cek_trans_pengajuan/<?php echo e($item->id_kategori_pengajuan); ?>">Cek</a>
                                            <center>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/sadmin/pengajuan/trans_pengajuan.blade.php ENDPATH**/ ?>