
<div class="row">
    <div class="col-md-6">
        <div class="box box-widget widget-user">
            <div class="widget-user-header bg-aqua-active">
                <h3 class="widget-user-username"><?php echo e(Auth::user()->name); ?></h3>
                <h5 class="widget-user-desc">PraUSTA</h5>
            </div>
            <div class="widget-user-image">
                <img class="img-circle" src="/adminlte/img/default.jpg" alt="User Avatar">
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header"></h5>
                            <span class="description-text"></span>
                        </div>
                    </div>
                    <div class="col-sm-4 border-right">

                    </div>
                    <div class="col-sm-4">
                        <div class="description-block">
                            <h5 class="description-header"></h5>
                            <span class="description-text"></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Tahun Akademik</span>
                <span class="info-box-number"><?php echo e($tahun->periode_tahun); ?></span>
                <span class="info-box-number"><?php echo e($tipe->periode_tipe); ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-calendar-check-o"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Jadwal KRS</span>
                <span class="info-box-number">
                    <?php if($time->status == 0): ?>
                        Jadwal Belum ada
                    <?php elseif($time->status == 1): ?>
                        <?php echo e($time->waktu_awal); ?> s/d <?php echo e($time->waktu_akhir); ?>

                    <?php endif; ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Informasi Terbaru</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                            class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="products-list product-list-in-box">
                    <?php $__currentLoopData = $info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="item">
                            <div class="product-img">
                                <?php if($item->file != null): ?>
                                    
                                    <img class="img-circle"
                                        src="<?php echo e(asset('/data_file/' . $item->file)); ?>">
                                    <?php else: ?>
                                <?php endif; ?>

                            </div>
                            <div class="product-info">
                                <a href="/lihat/<?php echo e($item->id_informasi); ?>" class="product-title"><?php echo e($item->judul); ?>

                                    <span class="label label-info pull-right">
                                        <?php echo e(date('l, d F Y', strtotime($item->created_at))); ?><br>
                                        <?php echo e($item->created_at->diffForHumans()); ?>

                                    </span></a>
                                <span class="product-description">
                                    <?php echo e($item->deskripsi); ?>

                                </span>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
                <a href="/lihat_semua" class="uppercase">Lihat Semua Informasi</a>
            </div>
            <!-- /.box-footer -->
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/layouts/prausta_home.blade.php ENDPATH**/ ?>