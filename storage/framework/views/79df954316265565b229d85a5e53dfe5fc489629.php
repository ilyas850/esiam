<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <section class="content-header">
        <h1>
            Form Isi EDOM
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="<?php echo e(url('isi_edom')); ?>"><i class="fa fa-pencil-square-o"></i> Input EDOM</a></li>
            <li class="active">Isi Komentar EDOM</li>
        </ol>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .edom-comment-info {
            margin-bottom: 20px;
        }

        .edom-comment-info .info-box {
            min-height: 96px;
        }

        .edom-comment-info .info-box-icon {
            width: 76px;
            font-size: 30px;
            line-height: 96px;
        }

        .edom-comment-info .info-box-content {
            margin-left: 76px;
            padding-top: 13px;
        }

        .edom-comment-info .info-box-text,
        .edom-comment-info .info-box-number {
            overflow: visible;
            text-overflow: initial;
            white-space: normal;
        }

        .edom-comment-box textarea {
            min-height: 160px;
            resize: vertical;
        }

        .edom-comment-counter {
            color: #777;
            font-size: 12px;
        }

        @media (max-width: 767px) {
            .edom-comment-info .info-box {
                min-height: 82px;
            }

            .edom-comment-info .info-box-icon {
                line-height: 82px;
            }

            .edom-comment-box .btn {
                width: 100%;
                margin: 4px 0;
            }
        }
    </style>

    <section class="content">
        <div class="row edom-comment-info">
            <div class="col-sm-7">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text"><?php echo e($makul->kode); ?></span>
                        <span class="info-box-number"><?php echo e($makul->makul); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-user"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Dosen Pengampu</span>
                        <span class="info-box-number"><?php echo e($dosen ? $dosen->nama : '-'); ?></span>
                        <?php if($dosen && $dosen->akademik): ?>
                            <span class="text-muted"><?php echo e($dosen->akademik); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="callout callout-info">
            <h4><i class="fa fa-commenting-o"></i> Berikan Komentar Anda</h4>
            <p>Tuliskan masukan yang sopan dan membangun untuk membantu peningkatan proses perkuliahan. Komentar maksimal 1.000 karakter.</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-circle"></i> <?php echo e($errors->first('nilai_edom') ?: 'Komentar belum dapat disimpan. Silakan periksa kembali isian Anda.'); ?>

            </div>
        <?php endif; ?>

        <form action="<?php echo e(url('save_edom_kom')); ?>" method="post" id="edom-comment-form">
            <?php echo e(csrf_field()); ?>

            <input type="hidden" name="id_kurperiode" value="<?php echo e($kurper); ?>">
            <input type="hidden" name="id_kurtrans" value="<?php echo e($kurtr); ?>">
            <div class="box box-info edom-comment-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Komentar untuk <?php echo e($makul->makul); ?></h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="nilai_edom">Komentar</label>
                        <textarea id="nilai_edom" class="form-control" name="nilai_edom" rows="7" maxlength="1000" placeholder="Contoh: Materi disampaikan dengan jelas dan diskusi kelas sangat membantu pemahaman saya." required><?php echo e(old('nilai_edom')); ?></textarea>
                        <p class="help-block">Hindari menuliskan data pribadi atau kata-kata yang tidak pantas.</p>
                        <p class="edom-comment-counter text-right"><span id="edom-comment-count">0</span>/1000 karakter</p>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="<?php echo e(url('isi_edom')); ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                    <button id="save-comment-button" type="submit" class="btn btn-info pull-right">
                        <i class="fa fa-save"></i> Simpan Komentar
                    </button>
                </div>
            </div>
        </form>
    </section>

    <script>
        (function () {
            var comment = document.getElementById('nilai_edom');
            var counter = document.getElementById('edom-comment-count');

            if (!comment || !counter) {
                return;
            }

            function updateCounter() {
                counter.textContent = comment.value.length;
            }

            comment.addEventListener('input', updateCounter);
            updateCounter();
        })();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/mhs/edom/komentar.blade.php ENDPATH**/ ?>