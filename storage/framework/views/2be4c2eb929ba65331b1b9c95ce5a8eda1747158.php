<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">SK Mengajar Dosen</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th class="text-center">Tahun Akademik</th>
                            <th class="text-center">Program Studi</th>
                            <th width="20%" class="text-center">SK Mengajar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($no++); ?></td>
                                <td><?php echo e($item->periode_tahun); ?> - <?php echo e($item->periode_tipe); ?></td>
                                <td><?php echo e($item->prodi); ?></td>
                                <td class="text-center">
                                    <?php if($item->file): ?>
                                        <a href="<?php echo e(asset('SK-Mengajar/' . $item->file)); ?>" target="_blank" class="btn btn-xs btn-primary">
                                            <i class="fa fa-file-pdf-o"></i> Lihat File
                                        </a>
                                    <?php else: ?>
                                        <span class="label label-default">Tidak ada file</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosen/pengajaran/sk_pengajaran.blade.php ENDPATH**/ ?>