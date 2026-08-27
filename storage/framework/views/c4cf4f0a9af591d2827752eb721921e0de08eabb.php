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
            <li class="active">Isi Form EDOM</li>
        </ol>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .edom-course-info {
            margin-bottom: 20px;
        }

        .edom-course-info .info-box {
            min-height: 96px;
        }

        .edom-course-info .info-box-icon {
            width: 76px;
            font-size: 30px;
            line-height: 96px;
        }

        .edom-course-info .info-box-content {
            margin-left: 76px;
            padding-top: 13px;
        }

        .edom-course-info .info-box-text {
            overflow: visible;
            text-overflow: initial;
            white-space: normal;
        }

        .edom-rating-option {
            min-width: 104px;
            margin: 2px;
            padding: 7px 8px;
            font-size: 12px;
        }

        .edom-rating-option input {
            display: none;
        }

        .edom-question-row.is-answered {
            background: #effcf2 !important;
            box-shadow: inset 4px 0 0 #00a65a;
        }

        .edom-question-row.is-answered .edom-question-number {
            color: #008d4c;
        }

        .edom-rating-option.is-selected {
            background: #00a65a !important;
            border-color: #008d4c !important;
            box-shadow: 0 1px 3px rgba(0, 141, 76, .35);
            color: #fff !important;
            font-weight: 700;
        }

        .edom-rating-option.is-selected:before {
            margin-right: 5px;
            content: '\2713';
        }

        .edom-question-number {
            color: #00a65a;
            font-weight: 700;
        }

        .edom-progress-text {
            margin-top: 7px;
            color: #777;
            font-size: 12px;
        }

        @media (max-width: 767px) {
            .edom-course-info .info-box {
                min-height: 82px;
            }

            .edom-course-info .info-box-icon {
                line-height: 82px;
            }

            .edom-rating-option {
                display: block;
                width: 100%;
                margin: 4px 0;
                padding: 10px;
                text-align: left;
            }

            .edom-question-table,
            .edom-question-table tbody,
            .edom-question-table tr,
            .edom-question-table td {
                display: block;
                width: 100% !important;
                min-width: 0 !important;
                max-width: 100%;
                box-sizing: border-box;
            }

            .edom-question-table {
                table-layout: fixed;
            }

            .table-responsive {
                overflow-x: hidden;
            }

            .edom-question-table thead {
                display: none;
            }

            .edom-question-table tr.edom-question-row {
                width: calc(100% - 24px) !important;
                margin: 12px;
                border: 1px solid #e5e5e5;
                border-radius: 4px;
                box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            }

            .edom-question-table td {
                padding: 9px 12px !important;
                border: 0 !important;
                text-align: left !important;
                white-space: normal !important;
                overflow-wrap: break-word;
                word-break: break-word;
            }

            .edom-question-table td * {
                max-width: 100%;
                white-space: normal !important;
                overflow-wrap: anywhere;
            }

            .edom-question-table td:before {
                display: block;
                margin-bottom: 4px;
                color: #777;
                content: attr(data-label);
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
            }

            .edom-question-table td.edom-question-number:before {
                display: none;
            }

            .edom-question-table td.edom-question-number {
                padding-bottom: 0 !important;
                color: #00a65a;
            }

            #save-edom-button {
                width: 100%;
                margin-top: 10px;
            }
        }
    </style>

    <section class="content">
        <div class="row edom-course-info">
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
                        <span class="info-box-number"><?php echo e($nama_dsn ?: '-'); ?></span>
                        <?php if($akademik): ?>
                            <span class="text-muted"><?php echo e($akademik); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="callout callout-info">
            <h4><i class="fa fa-info-circle"></i> Petunjuk Pengisian</h4>
            <p>Pilih satu nilai untuk setiap pernyataan. Semua pertanyaan wajib diisi sebelum jawaban dapat disimpan.</p>
            <span class="label label-danger">1 Tidak Baik</span>
            <span class="label label-warning">2 Kurang Baik</span>
            <span class="label label-info">3 Baik</span>
            <span class="label label-success">4 Sangat Baik</span>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-circle"></i> Mohon periksa kembali jawaban Anda. Semua pertanyaan harus terisi.
            </div>
        <?php endif; ?>

        <form action="<?php echo e(url('save_edom')); ?>" method="post" id="edom-form">
            <?php echo e(csrf_field()); ?>

            <input type="hidden" name="id_student" value="<?php echo e($ids); ?>">
            <input type="hidden" name="id_kurperiode" value="<?php echo e($kurper); ?>">
            <input type="hidden" name="id_kurtrans" value="<?php echo e($kurtr); ?>">

            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Pernyataan Penilaian</h3>
                    <div class="box-tools pull-right">
                        <span class="label label-info"><?php echo e($edomQuestionCount); ?> pertanyaan</span>
                    </div>
                </div>
                <div class="box-body">
                    <div class="progress progress-sm active">
                        <div id="edom-progress-bar" class="progress-bar progress-bar-aqua progress-bar-striped" style="width: 0%"></div>
                    </div>
                    <p id="edom-progress-text" class="edom-progress-text">0 dari <?php echo e($edomQuestionCount); ?> pertanyaan terisi</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover edom-question-table">
                        <thead>
                            <tr>
                                <th width="6%" class="text-center">No</th>
                                <th width="18%">Aspek</th>
                                <th>Pernyataan</th>
                                <th width="45%" class="text-center">Penilaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php $__currentLoopData = $edom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $selectedValue = old('nilai_edom.' . $key->id_edom); ?>
                                <tr class="edom-question-row" data-question="<?php echo e($key->id_edom); ?>">
                                    <td class="text-center edom-question-number" data-label="Pertanyaan"><?php echo e($no++); ?></td>
                                    <td data-label="Aspek"><?php echo e($key->type); ?></td>
                                    <td data-label="Pernyataan"><?php echo e($key->description); ?></td>
                                    <td class="text-center" data-label="Penilaian">
                                        <label class="btn btn-default btn-xs edom-rating-option <?php echo e($selectedValue == $key->id_edom . ',1' ? 'active' : ''); ?>">
                                            <input class="edom-answer" type="radio" name="nilai_edom[<?php echo e($key->id_edom); ?>]" value="<?php echo e($key->id_edom); ?>,1" required <?php echo e($selectedValue == $key->id_edom . ',1' ? 'checked' : ''); ?>> 1 Tidak Baik
                                        </label>
                                        <label class="btn btn-default btn-xs edom-rating-option <?php echo e($selectedValue == $key->id_edom . ',2' ? 'active' : ''); ?>">
                                            <input class="edom-answer" type="radio" name="nilai_edom[<?php echo e($key->id_edom); ?>]" value="<?php echo e($key->id_edom); ?>,2" <?php echo e($selectedValue == $key->id_edom . ',2' ? 'checked' : ''); ?>> 2 Kurang Baik
                                        </label>
                                        <label class="btn btn-default btn-xs edom-rating-option <?php echo e($selectedValue == $key->id_edom . ',3' ? 'active' : ''); ?>">
                                            <input class="edom-answer" type="radio" name="nilai_edom[<?php echo e($key->id_edom); ?>]" value="<?php echo e($key->id_edom); ?>,3" <?php echo e($selectedValue == $key->id_edom . ',3' ? 'checked' : ''); ?>> 3 Baik
                                        </label>
                                        <label class="btn btn-default btn-xs edom-rating-option <?php echo e($selectedValue == $key->id_edom . ',4' ? 'active' : ''); ?>">
                                            <input class="edom-answer" type="radio" name="nilai_edom[<?php echo e($key->id_edom); ?>]" value="<?php echo e($key->id_edom); ?>,4" <?php echo e($selectedValue == $key->id_edom . ',4' ? 'checked' : ''); ?>> 4 Sangat Baik
                                        </label>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <a href="<?php echo e(url('isi_edom')); ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                    <button id="save-edom-button" type="submit" class="btn btn-info pull-right" disabled>
                        <i class="fa fa-save"></i> Simpan Jawaban (<span id="edom-answer-count">0</span>/<?php echo e($edomQuestionCount); ?>)
                    </button>
                </div>
            </div>
        </form>
    </section>

    <script>
        (function () {
            var total = <?php echo e($edomQuestionCount); ?>;
            var answers = document.querySelectorAll('.edom-answer');
            var progressBar = document.getElementById('edom-progress-bar');
            var progressText = document.getElementById('edom-progress-text');
            var answerCount = document.getElementById('edom-answer-count');
            var saveButton = document.getElementById('save-edom-button');

            function updateProgress() {
                var answered = document.querySelectorAll('.edom-answer:checked').length;
                var percentage = total ? Math.round((answered / total) * 100) : 0;

                progressBar.style.width = percentage + '%';
                progressText.textContent = answered + ' dari ' + total + ' pertanyaan terisi';
                answerCount.textContent = answered;
                saveButton.disabled = answered !== total;

                Array.prototype.forEach.call(document.querySelectorAll('.edom-question-row'), function (row) {
                    var selectedAnswer = row.querySelector('.edom-answer:checked');

                    row.classList.toggle('is-answered', selectedAnswer !== null);

                    Array.prototype.forEach.call(row.querySelectorAll('.edom-rating-option'), function (option) {
                        option.classList.toggle('is-selected', option.querySelector('.edom-answer:checked') !== null);
                    });
                });
            }

            Array.prototype.forEach.call(answers, function (answer) {
                answer.addEventListener('change', updateProgress);
            });

            updateProgress();
        })();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/mhs/edom/form_edom.blade.php ENDPATH**/ ?>