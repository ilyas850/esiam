<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <style>
        .questionnaire-intro {
            margin-bottom: 20px;
        }

        .questionnaire-card {
            min-height: 182px;
            margin-bottom: 20px;
            border-top-width: 3px;
            transition: box-shadow .2s ease, transform .2s ease;
        }

        .questionnaire-card:hover {
            box-shadow: 0 5px 14px rgba(0, 0, 0, .12);
            transform: translateY(-2px);
        }

        .questionnaire-card .box-body {
            padding: 20px;
        }

        .questionnaire-icon {
            float: left;
            width: 48px;
            height: 48px;
            margin: 0 15px 10px 0;
            border-radius: 50%;
            background: #00a7d0;
            color: #fff;
            font-size: 21px;
            line-height: 48px;
            text-align: center;
        }

        .questionnaire-title {
            min-height: 48px;
            margin: 0;
            color: #333;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.35;
        }

        .questionnaire-description {
            min-height: 40px;
            margin: 15px 0;
            color: #777;
            line-height: 1.5;
        }

        .questionnaire-action {
            min-width: 142px;
        }

        .questionnaire-status {
            margin: 0 0 14px;
        }

        .questionnaire-status .label {
            display: inline-block;
            margin-right: 7px;
            padding: 5px 8px;
            font-size: 12px;
        }

        .questionnaire-status-detail {
            color: #777;
            font-size: 12px;
        }

        @media (max-width: 767px) {
            .questionnaire-card {
                min-height: 0;
            }

            .questionnaire-description {
                min-height: 0;
            }

            .questionnaire-action {
                width: 100%;
            }
        }
    </style>

    <section class="content-header">
        <h1>
            Kuisioner Mahasiswa
            <small>Masukan Anda membantu meningkatkan kualitas layanan</small>
        </h1>
    </section>

    <section class="content">
        <div class="callout callout-info questionnaire-intro">
            <h4><i class="fa fa-info-circle"></i> Pilih kuisioner yang ingin diisi</h4>
            <p>Isi setiap kuisioner dengan jujur sesuai pengalaman Anda. Klik tombol <strong>Isi Kuisioner</strong> untuk memulai.</p>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-list-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Kuisioner</span>
                        <span class="info-box-number"><?php echo e($completionSummary['total']); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Sudah Selesai</span>
                        <span class="info-box-number"><?php echo e($completionSummary['completed']); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-pencil"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Perlu Diisi</span>
                        <span class="info-box-number"><?php echo e($completionSummary['remaining']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Daftar Kuisioner</h3>
                <div class="box-tools pull-right">
                    <span class="label label-info"><?php echo e(count($questionnaires)); ?> tersedia</span>
                </div>
            </div>
            <div class="box-body">
                <?php if(count($questionnaires)): ?>
                    <div class="row">
                        <?php $__currentLoopData = $questionnaires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $questionnaire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6">
                                <div class="box box-primary questionnaire-card">
                                    <div class="box-body">
                                        <div class="questionnaire-icon">
                                            <i class="fa <?php echo e($questionnaire['icon']); ?>"></i>
                                        </div>
                                        <h4 class="questionnaire-title"><?php echo e($questionnaire['title']); ?></h4>
                                        <div class="clearfix"></div>
                                        <p class="questionnaire-description"><?php echo e($questionnaire['description']); ?></p>
                                        <p class="questionnaire-status">
                                            <span class="label <?php echo e($questionnaire['is_complete'] ? 'label-success' : 'label-warning'); ?>">
                                                <i class="fa <?php echo e($questionnaire['is_complete'] ? 'fa-check' : 'fa-exclamation-circle'); ?>"></i>
                                                <?php echo e($questionnaire['status_text']); ?>

                                            </span>
                                            <span class="questionnaire-status-detail"><?php echo e($questionnaire['status_detail']); ?></span>
                                        </p>

                                        <?php if($questionnaire['url'] && !$questionnaire['is_complete']): ?>
                                            <a href="<?php echo e($questionnaire['url']); ?>" class="btn btn-info btn-sm questionnaire-action">
                                                <i class="fa fa-pencil"></i> <?php echo e($questionnaire['action_label']); ?>

                                            </a>
                                        <?php elseif($questionnaire['is_complete']): ?>
                                            <span class="btn btn-success btn-sm disabled questionnaire-action">
                                                <i class="fa fa-check"></i> <?php echo e($questionnaire['action_label']); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="fa fa-clock-o"></i> Belum tersedia</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted" style="padding: 30px 15px;">
                        <i class="fa fa-list-alt fa-3x"></i>
                        <h4>Belum ada kuisioner yang tersedia</h4>
                        <p>Silakan periksa kembali pada periode berikutnya.</p>
                    </div>
                <?php endif; ?>
                </div>
            </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/mhs/kuisioner/kuisioner_all.blade.php ENDPATH**/ ?>