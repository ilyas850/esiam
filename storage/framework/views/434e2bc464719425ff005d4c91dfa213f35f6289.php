<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <section class="content-header">
        <h1>Kuisioner Dosen Pembimbing Akademik</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="<?php echo e(url('kuisioner')); ?>"><i class="fa fa-list-alt"></i> Kuisioner</a></li>
            <li class="active">Dosen PA</li>
        </ol>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .kuisioner-info { margin-bottom: 20px; }
        .kuisioner-info .info-box { min-height: 88px; }
        .kuisioner-info .info-box-icon { width: 70px; font-size: 27px; line-height: 88px; }
        .kuisioner-info .info-box-content { margin-left: 70px; padding-top: 12px; }
        .kuisioner-info .info-box-text, .kuisioner-info .info-box-number { overflow: visible; text-overflow: initial; white-space: normal; }
        .kuisioner-progress-text { margin-top: 7px; color: #777; font-size: 12px; }
        .kuisioner-question-row.is-answered { background: #effcf2 !important; box-shadow: inset 4px 0 0 #00a65a; }
        .kuisioner-question-number { color: #00a65a; font-weight: 700; }
        .kuisioner-rating-option { min-width: 104px; margin: 2px; padding: 7px 8px; font-size: 12px; }
        .kuisioner-rating-option input { display: none; }
        .kuisioner-rating-option.is-selected { background: #00a65a !important; border-color: #008d4c !important; box-shadow: 0 1px 3px rgba(0, 141, 76, .35); color: #fff !important; font-weight: 700; }
        .kuisioner-rating-option.is-selected:before { margin-right: 5px; content: '\2713'; }

        @media (max-width: 767px) {
            .kuisioner-info .info-box { min-height: 82px; }
            .kuisioner-info .info-box-icon { line-height: 82px; }
            .kuisioner-rating-option { display: block; width: 100%; margin: 4px 0; padding: 10px; text-align: left; }
            .kuisioner-table, .kuisioner-table tbody, .kuisioner-table tr, .kuisioner-table td { display: block; width: 100% !important; min-width: 0 !important; max-width: 100%; box-sizing: border-box; }
            .table-responsive { overflow-x: hidden; }
            .kuisioner-table thead { display: none; }
            .kuisioner-table tr.kuisioner-question-row { width: calc(100% - 24px) !important; margin: 12px; border: 1px solid #e5e5e5; border-radius: 4px; box-shadow: 0 1px 2px rgba(0, 0, 0, .05); }
            .kuisioner-table td { padding: 9px 12px !important; border: 0 !important; text-align: left !important; white-space: normal !important; overflow-wrap: break-word; word-break: break-word; }
            .kuisioner-table td:before { display: block; margin-bottom: 4px; color: #777; content: attr(data-label); font-size: 11px; font-weight: 700; text-transform: uppercase; }
            .kuisioner-table td.kuisioner-question-number:before { display: none; }
            #save-kuisioner-button { width: 100%; margin-top: 10px; }
        }
    </style>

    <section class="content">
        <div class="row kuisioner-info">
            <div class="col-sm-6"><div class="info-box"><span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span><div class="info-box-content"><span class="info-box-text">Dosen Pembimbing Akademik</span><span class="info-box-number"><?php echo e($nama_dsn); ?></span></div></div></div>
            <div class="col-sm-3"><div class="info-box"><span class="info-box-icon bg-blue"><i class="fa fa-graduation-cap"></i></span><div class="info-box-content"><span class="info-box-text">Program Studi</span><span class="info-box-number"><?php echo e($prodi); ?></span></div></div></div>
            <div class="col-sm-3"><div class="info-box"><span class="info-box-icon bg-green"><i class="fa fa-calendar"></i></span><div class="info-box-content"><span class="info-box-text">Periode</span><span class="info-box-number"><?php echo e($periodetipe); ?></span><span class="text-muted"><?php echo e($periodetahun); ?></span></div></div></div>
        </div>

        <div class="callout callout-info">
            <h4><i class="fa fa-info-circle"></i> Petunjuk Pengisian</h4>
            <p>Pilih satu nilai untuk setiap pernyataan. Semua pertanyaan wajib diisi sebelum kuisioner dapat disimpan.</p>
            <span class="label label-danger">1 Tidak Baik</span> <span class="label label-warning">2 Kurang Baik</span> <span class="label label-info">3 Baik</span> <span class="label label-success">4 Sangat Baik</span>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Mohon lengkapi seluruh pertanyaan sebelum menyimpan.</div>
        <?php endif; ?>

        <form action="<?php echo e(url('save_kuisioner_dsn_pa')); ?>" method="post" id="kuisioner-form">
            <?php echo e(csrf_field()); ?>

            <input type="hidden" name="id_dosen_pembimbing" value="<?php echo e($id_dsn); ?>">
            <input type="hidden" name="id_periodetahun" value="<?php echo e($idthn); ?>">
            <input type="hidden" name="id_periodetipe" value="<?php echo e($idtp); ?>">

            <div class="box box-info">
                <div class="box-header with-border"><h3 class="box-title">Pernyataan Penilaian Dosen PA</h3><div class="box-tools pull-right"><span class="label label-info"><?php echo e($questionnaireQuestionCount); ?> pertanyaan</span></div></div>
                <div class="box-body"><div class="progress progress-sm active"><div id="kuisioner-progress-bar" class="progress-bar progress-bar-aqua progress-bar-striped" style="width: 0%"></div></div><p id="kuisioner-progress-text" class="kuisioner-progress-text">0 dari <?php echo e($questionnaireQuestionCount); ?> pertanyaan terisi</p></div>
                <div class="table-responsive">
                    <table class="table table-hover kuisioner-table">
                        <thead><tr><th width="6%" class="text-center">No</th><th width="23%">Aspek</th><th>Komponen</th><th width="22%">Nilai</th></tr></thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $selectedValue = old('nilai.' . $item->id_kuisioner); ?>
                                <tr class="kuisioner-question-row">
                                    <td class="text-center kuisioner-question-number" data-label="Pertanyaan"><?php echo e($no++); ?></td>
                                    <td data-label="Aspek"><?php echo e($item->aspek_kuisioner); ?></td>
                                    <td data-label="Komponen"><?php echo e($item->komponen_kuisioner); ?></td>
                                    <td data-label="Nilai">
                                        <label class="btn btn-default btn-xs kuisioner-rating-option <?php echo e($selectedValue == $item->id_kuisioner . ',1' ? 'is-selected' : ''); ?>">
                                            <input class="kuisioner-answer" type="radio" name="nilai[<?php echo e($item->id_kuisioner); ?>]" value="<?php echo e($item->id_kuisioner); ?>,1" required <?php echo e($selectedValue == $item->id_kuisioner . ',1' ? 'checked' : ''); ?>> 1 Tidak Baik
                                        </label>
                                        <label class="btn btn-default btn-xs kuisioner-rating-option <?php echo e($selectedValue == $item->id_kuisioner . ',2' ? 'is-selected' : ''); ?>">
                                            <input class="kuisioner-answer" type="radio" name="nilai[<?php echo e($item->id_kuisioner); ?>]" value="<?php echo e($item->id_kuisioner); ?>,2" <?php echo e($selectedValue == $item->id_kuisioner . ',2' ? 'checked' : ''); ?>> 2 Kurang Baik
                                        </label>
                                        <label class="btn btn-default btn-xs kuisioner-rating-option <?php echo e($selectedValue == $item->id_kuisioner . ',3' ? 'is-selected' : ''); ?>">
                                            <input class="kuisioner-answer" type="radio" name="nilai[<?php echo e($item->id_kuisioner); ?>]" value="<?php echo e($item->id_kuisioner); ?>,3" <?php echo e($selectedValue == $item->id_kuisioner . ',3' ? 'checked' : ''); ?>> 3 Baik
                                        </label>
                                        <label class="btn btn-default btn-xs kuisioner-rating-option <?php echo e($selectedValue == $item->id_kuisioner . ',4' ? 'is-selected' : ''); ?>">
                                            <input class="kuisioner-answer" type="radio" name="nilai[<?php echo e($item->id_kuisioner); ?>]" value="<?php echo e($item->id_kuisioner); ?>,4" <?php echo e($selectedValue == $item->id_kuisioner . ',4' ? 'checked' : ''); ?>> 4 Sangat Baik
                                        </label>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer"><a href="<?php echo e(url('kuisioner')); ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali ke Kuisioner</a><button id="save-kuisioner-button" type="submit" class="btn btn-info pull-right" disabled><i class="fa fa-save"></i> Simpan Kuisioner (<span id="kuisioner-answer-count">0</span>/<?php echo e($questionnaireQuestionCount); ?>)</button></div>
            </div>
        </form>
    </section>

    <script>
        (function () {
            var total = <?php echo e($questionnaireQuestionCount); ?>, answers = document.querySelectorAll('.kuisioner-answer'), progressBar = document.getElementById('kuisioner-progress-bar'), progressText = document.getElementById('kuisioner-progress-text'), answerCount = document.getElementById('kuisioner-answer-count'), saveButton = document.getElementById('save-kuisioner-button');
            function updateProgress() {
                var answered = 0;
                Array.prototype.forEach.call(document.querySelectorAll('.kuisioner-question-row'), function (row) {
                    var selectedAnswer = row.querySelector('.kuisioner-answer:checked');

                    if (selectedAnswer) { answered++; }
                    row.classList.toggle('is-answered', selectedAnswer !== null);

                    Array.prototype.forEach.call(row.querySelectorAll('.kuisioner-rating-option'), function (option) {
                        option.classList.toggle('is-selected', option.querySelector('.kuisioner-answer:checked') !== null);
                    });
                });
                var percentage = total ? Math.round((answered / total) * 100) : 0;
                progressBar.style.width = percentage + '%'; progressText.textContent = answered + ' dari ' + total + ' pertanyaan terisi'; answerCount.textContent = answered; saveButton.disabled = answered !== total;
            }
            Array.prototype.forEach.call(answers, function (answer) { answer.addEventListener('change', updateProgress); });
            updateProgress();
        })();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/mhs/kuisioner/kuisioner_dsn_pa.blade.php ENDPATH**/ ?>