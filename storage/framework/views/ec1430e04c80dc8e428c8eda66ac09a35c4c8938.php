<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content_header'); ?>
    <section class="content-header">
        <h1>
            Input EDOM
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li class="active">Input EDOM</li>
        </ol>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <style>
        .edom-summary .info-box {
            min-height: 88px;
        }

        .edom-summary .info-box-icon {
            width: 72px;
            font-size: 28px;
            line-height: 88px;
        }

        .edom-summary .info-box-content {
            margin-left: 72px;
            padding-top: 14px;
        }

        .edom-status {
            display: inline-block;
            min-width: 96px;
            margin-bottom: 7px;
            padding: 5px 7px;
            font-size: 12px;
            text-align: center;
        }

        .edom-course-name {
            color: #333;
            font-weight: 600;
        }

        .edom-course-code {
            display: inline-block;
            margin-top: 3px;
            color: #777;
            font-size: 12px;
        }

        .edom-action {
            min-width: 118px;
        }

        .edom-back-questionnaire {
            background-color: #fff;
            border-color: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .18);
            color: #0073b7 !important;
            font-weight: 700;
            margin-top: 8px;
        }

        .edom-back-questionnaire:hover,
        .edom-back-questionnaire:focus {
            background-color: #f4f4f4;
            border-color: #fff;
            color: #005a8d !important;
        }

        @media (max-width: 767px) {
            .edom-action {
                width: 100%;
                margin-bottom: 5px;
            }

            .edom-back-questionnaire {
                width: 100%;
                margin-top: 12px;
            }

            .edom-course-table,
            .edom-course-table tbody,
            .edom-course-table tr,
            .edom-course-table td {
                display: block;
                width: 100%;
            }

            .edom-course-table thead {
                display: none;
            }

            .edom-course-table tr {
                margin: 12px;
                border: 1px solid #e5e5e5;
                border-radius: 4px;
                box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            }

            .edom-course-table td {
                padding: 9px 12px !important;
                border: 0 !important;
                text-align: left !important;
            }

            .edom-course-table td:before {
                display: block;
                margin-bottom: 4px;
                color: #777;
                content: attr(data-label);
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
            }

            .edom-course-table td:first-child:before {
                display: none;
            }

            .edom-course-table td:first-child {
                padding-bottom: 0 !important;
                color: #00a65a;
                font-weight: 700;
            }
        }
    </style>

    <section class="content">
        <div class="callout callout-info">
            <div class="row">
                <div class="col-sm-8">
                    <h4><i class="fa fa-info-circle"></i> Lengkapi EDOM Anda</h4>
                    <p>Form EDOM wajib diisi untuk setiap mata kuliah. Komentar bersifat opsional dan dapat diisi sebagai masukan tambahan.</p>
                </div>
                <div class="col-sm-4 text-right">
                    <a href="<?php echo e(url('kuisioner')); ?>" class="btn btn-default edom-back-questionnaire">
                        <i class="fa fa-list-alt"></i> Kembali ke Kuisioner
                    </a>
                </div>
            </div>
        </div>

        <div class="row edom-summary">
            <div class="col-sm-4">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Mata Kuliah</span>
                        <span class="info-box-number"><?php echo e($edomSummary['total']); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Form EDOM Sudah Diisi</span>
                        <span class="info-box-number"><?php echo e($edomSummary['completed']); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-pencil"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Form EDOM Belum Diisi</span>
                        <span class="info-box-number"><?php echo e($edomSummary['remaining']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Daftar EDOM Mata Kuliah</h3>
                        <div class="box-tools">
                            <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
                                <input type="text" id="edom-search" class="form-control pull-right" placeholder="Cari mata kuliah">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <?php if(count($edom)): ?>
                            <table class="table table-hover table-striped edom-course-table">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th>Mata Kuliah</th>
                                        <th width="20%">Dosen</th>
                                        <th width="17%" class="text-center">Form EDOM</th>
                                        <th width="17%" class="text-center">Komentar <small class="text-muted">(opsional)</small></th>
                                    </tr>
                                </thead>
                                <tbody id="edom-course-list">
                                    <?php $no = 1; ?>
                                    <?php $__currentLoopData = $edom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr data-edom-search="<?php echo e(strtolower($item->kode . ' ' . $item->makul . ' ' . $item->nama)); ?>">
                                            <td class="text-center" data-label="Mata Kuliah"><?php echo e($no++); ?></td>
                                            <td data-label="Mata Kuliah">
                                                <span class="edom-course-name"><?php echo e($item->makul); ?></span><br>
                                                <span class="edom-course-code"><?php echo e($item->kode); ?></span>
                                            </td>
                                            <td data-label="Dosen"><?php echo e($item->nama ?: '-'); ?></td>
                                            <td class="text-center" data-label="Form EDOM">
                                                <span class="label edom-status <?php echo e($item->form_completed ? 'label-success' : 'label-warning'); ?>">
                                                    <i class="fa <?php echo e($item->form_completed ? 'fa-check' : 'fa-pencil'); ?>"></i>
                                                    <?php echo e($item->form_completed ? 'Sudah diisi' : 'Belum diisi'); ?>

                                                </span><br>
                                                <?php if($item->form_completed): ?>
                                                    <span class="btn btn-success btn-xs disabled edom-action"><i class="fa fa-check"></i> Form Selesai</span>
                                                <?php else: ?>
                                                    <form action="<?php echo e(url('form_edom')); ?>" method="post">
                                                        <input type="hidden" name="id_student" value="<?php echo e($item->id_student); ?>">
                                                        <input type="hidden" name="id_kurperiode" value="<?php echo e($item->id_kurperiode); ?>">
                                                        <input type="hidden" name="id_kurtrans" value="<?php echo e($item->id_kurtrans); ?>">
                                                        <input type="hidden" name="id_makul" value="<?php echo e($item->id_makul); ?>">
                                                        <input type="hidden" name="id_dosen" value="<?php echo e($item->id_dosen); ?>">
                                                        <?php echo e(csrf_field()); ?>

                                                        <button type="submit" class="btn btn-info btn-xs edom-action"><i class="fa fa-pencil"></i> Isi Form</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center" data-label="Komentar">
                                                <span class="label edom-status <?php echo e($item->comment_completed ? 'label-success' : 'label-warning'); ?>">
                                                    <i class="fa <?php echo e($item->comment_completed ? 'fa-check' : 'fa-comment-o'); ?>"></i>
                                                    <?php echo e($item->comment_completed ? 'Sudah diisi' : 'Belum diisi'); ?>

                                                </span><br>
                                                <?php if($item->comment_completed): ?>
                                                    <span class="btn btn-success btn-xs disabled edom-action"><i class="fa fa-check"></i> Komentar Selesai</span>
                                                <?php else: ?>
                                                    <form action="<?php echo e(url('edom_kom')); ?>" method="post">
                                                        <input type="hidden" name="id_student" value="<?php echo e($item->id_student); ?>">
                                                        <input type="hidden" name="id_kurperiode" value="<?php echo e($item->id_kurperiode); ?>">
                                                        <input type="hidden" name="id_kurtrans" value="<?php echo e($item->id_kurtrans); ?>">
                                                        <input type="hidden" name="id_makul" value="<?php echo e($item->id_makul); ?>">
                                                        <input type="hidden" name="id_dosen" value="<?php echo e($item->id_dosen); ?>">
                                                        <?php echo e(csrf_field()); ?>

                                                        <button type="submit" class="btn btn-default btn-xs edom-action"><i class="fa fa-comment-o"></i> Isi Komentar</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="text-center text-muted" style="padding: 35px 15px;">
                                <i class="fa fa-book fa-3x"></i>
                                <h4>Belum ada mata kuliah untuk EDOM</h4>
                                <p>Mata kuliah yang diambil pada periode aktif akan muncul di halaman ini.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>

    <script>
        (function () {
            var search = document.getElementById('edom-search');
            var rows = document.querySelectorAll('#edom-course-list tr');

            if (!search) {
                return;
            }

            search.addEventListener('input', function () {
                var keyword = this.value.toLowerCase();

                Array.prototype.forEach.call(rows, function (row) {
                    row.style.display = row.getAttribute('data-edom-search').indexOf(keyword) > -1 ? '' : 'none';
                });
            });
        })();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/mhs/edom/isi_edom.blade.php ENDPATH**/ ?>