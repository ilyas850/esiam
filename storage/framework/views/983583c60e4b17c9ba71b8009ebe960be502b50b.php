<div class="row">
    <?php $__empty_1 = true; $__currentLoopData = $pedoman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-md-6">
            <div class="box box-solid" style="border-top: 3px solid #dd4b39; min-height: 210px;">
                <div class="box-body">
                    <div class="clearfix" style="margin-bottom: 12px;">
                        <span class="label bg-red pull-left" style="font-size: 12px; padding: 6px 10px;">
                            #<?php echo e(($pedoman->firstItem() ?: 1) + $index); ?>

                        </span>
                        <span class="label label-success pull-right" style="font-size: 12px; padding: 6px 10px;">
                            <?php echo e(strtoupper(pathinfo($item->file, PATHINFO_EXTENSION) ?: 'FILE')); ?>

                        </span>
                    </div>

                    <div style="display: flex; align-items: flex-start;">
                        <div
                            style="width: 52px; height: 52px; background: #fdf2f2; border-radius: 10px; text-align: center; line-height: 52px; margin-right: 15px;">
                            <i class="fa fa-file-text-o" style="font-size: 24px; color: #dd4b39;"></i>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 8px; color: #2c3e50; font-weight: 600; line-height: 1.4;">
                                <?php echo e($item->nama_pedoman); ?>

                            </h4>
                            <p style="margin: 0 0 12px; color: #777; min-height: 40px;">
                                Dokumen pedoman akademik aktif untuk referensi kaprodi.
                            </p>
                        </div>
                    </div>

                    <div
                        style="margin: 15px 0 12px; padding: 12px 14px; background: #f9fafc; border: 1px solid #eef1f5; border-radius: 8px;">
                        <div class="row">
                            <div class="col-xs-7">
                                <small style="display: block; color: #999; margin-bottom: 4px;">Tahun Akademik</small>
                                <span class="label label-primary" style="font-size: 12px;"><?php echo e($item->periode_tahun); ?></span>
                            </div>
                            <div class="col-xs-5 text-right">
                                <small style="display: block; color: #999; margin-bottom: 4px;">Jenis File</small>
                                <strong style="color: #444;"><?php echo e(strtoupper(pathinfo($item->file, PATHINFO_EXTENSION) ?: 'FILE')); ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix">
                        <a href="<?php echo e(url('download_pedoman_dsn_kprd/' . $item->id_pedomanakademik)); ?>" class="btn btn-warning">
                            <i class="fa fa-download"></i> Download Pedoman
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-sm-12">
            <div class="box box-default">
                <div class="box-body text-center" style="padding: 35px 15px; color: #777;">
                    <i class="fa fa-folder-open-o" style="font-size: 30px; margin-bottom: 10px; display: block;"></i>
                    Data pedoman tidak ditemukan.
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="row">
    <div class="col-sm-6">
        <div style="padding-top: 8px; color: #666;">
            Menampilkan <?php echo e($pedoman->firstItem() ?: 0); ?> sampai <?php echo e($pedoman->lastItem() ?: 0); ?> dari
            <?php echo e($pedoman->total()); ?> pedoman
        </div>
    </div>
    <div class="col-sm-6 text-right">
        <?php echo e($pedoman->appends(request()->only('q', 'per_page'))->links()); ?>

    </div>
</div>
<?php /**PATH /var/www/html/resources/views/kaprodi/partials/pedoman_akademik_list.blade.php ENDPATH**/ ?>