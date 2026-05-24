<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <style>
            .jadwal-page .box-header {
                border-bottom: 1px solid #f0f2f5;
                padding-bottom: 18px;
            }

            .jadwal-page .jadwal-subtitle {
                color: #6b7785;
                margin: 6px 0 0;
                font-size: 13px;
            }

            .jadwal-page .jadwal-search {
                max-width: 320px;
            }

            .jadwal-page .jadwal-search .input-group {
                width: 100%;
            }

            .jadwal-page .jadwal-search .input-group-addon {
                background: #fff;
                border-color: #d2d6de;
                border-right: 0;
                border-radius: 20px 0 0 20px;
                color: #97a0ab;
                padding-left: 14px;
                padding-right: 10px;
            }

            .jadwal-page .jadwal-search .form-control {
                border-radius: 0 20px 20px 0;
                padding-left: 0;
                border-color: #d2d6de;
            }

            .jadwal-page .jadwal-search .form-control:focus {
                border-color: #3c8dbc;
            }

            .jadwal-page .info-box {
                margin-bottom: 15px;
                border-radius: 10px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            }

            .jadwal-page .info-box-content {
                padding-top: 12px;
                padding-bottom: 12px;
            }

            .jadwal-page .info-box-text {
                text-transform: uppercase;
                letter-spacing: .4px;
                font-size: 11px;
                color: #7b8794;
            }

            .jadwal-page .info-box-number {
                font-size: 22px;
                margin-top: 4px;
            }

            .jadwal-page .jadwal-table > tbody > tr > td,
            .jadwal-page .jadwal-table > thead > tr > th {
                vertical-align: middle;
            }

            .jadwal-page .course-cell {
                min-width: 240px;
            }

            .jadwal-page .course-title {
                display: block;
                font-weight: 600;
                color: #2c3b41;
            }

            .jadwal-page .course-meta {
                display: block;
                color: #8b97a3;
                font-size: 12px;
                margin-top: 2px;
            }

            .jadwal-page .attendance-value {
                font-weight: 600;
                color: #2c3b41;
            }

            .jadwal-page .attendance-note {
                display: block;
                color: #8b97a3;
                font-size: 11px;
                margin-top: 3px;
            }

            .jadwal-page .jadwal-mobile-list {
                display: none;
            }

            .jadwal-page .jadwal-card {
                border: 1px solid #e7eaee;
                border-radius: 12px;
                padding: 15px;
                margin-bottom: 15px;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            }

            .jadwal-page .jadwal-card-top {
                display: flex;
                justify-content: space-between;
                gap: 10px;
                align-items: flex-start;
                margin-bottom: 12px;
            }

            .jadwal-page .jadwal-card-title {
                font-size: 15px;
                font-weight: 600;
                color: #2c3b41;
                line-height: 1.45;
            }

            .jadwal-page .jadwal-card-code {
                color: #8b97a3;
                font-size: 12px;
                margin-top: 2px;
            }

            .jadwal-page .jadwal-card-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
                margin-bottom: 14px;
            }

            .jadwal-page .jadwal-card-item {
                background: #f8fafc;
                border-radius: 8px;
                padding: 10px 12px;
            }

            .jadwal-page .jadwal-card-label {
                display: block;
                color: #8b97a3;
                font-size: 11px;
                text-transform: uppercase;
                margin-bottom: 4px;
            }

            .jadwal-page .jadwal-card-value {
                display: block;
                color: #2c3b41;
                font-weight: 600;
                line-height: 1.45;
            }

            .jadwal-page .jadwal-card-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }

            .jadwal-page .empty-state {
                padding: 35px 20px;
                text-align: center;
                color: #7b8794;
            }

            .jadwal-page .empty-state .fa {
                font-size: 30px;
                margin-bottom: 10px;
                color: #b7c0cb;
            }

            @media (max-width: 767px) {
                .jadwal-page .box-header .pull-right {
                    float: none !important;
                    margin-top: 12px;
                }

                .jadwal-page .jadwal-search {
                    max-width: 100%;
                }

                .jadwal-page .jadwal-desktop-table {
                    display: none;
                }

                .jadwal-page .jadwal-mobile-list {
                    display: block;
                }

                .jadwal-page .jadwal-card-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="row jadwal-page">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <div class="pull-left">
                            <h3 class="box-title">Jadwal Kuliah</h3>
                            <p class="jadwal-subtitle">
                                Semester aktif: <?php echo e($summary['periode_label']); ?>. Pantau jadwal, dosen, ruangan, dan progres
                                kehadiran dalam satu tampilan.
                            </p>
                        </div>
                        <div class="pull-right">
                            <div class="jadwal-search">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <input type="text" id="jadwal-search" class="form-control"
                                        placeholder="Cari matakuliah, dosen, hari, atau ruangan">
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-book"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Matakuliah</span>
                                        <span class="info-box-number"><?php echo e($summary['total_makul']); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-green"><i class="fa fa-calendar-check-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Kehadiran</span>
                                        <span class="info-box-number"><?php echo e($summary['total_hadir']); ?> / <?php echo e($summary['total_pertemuan']); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-yellow"><i class="fa fa-line-chart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Rata-rata Absen</span>
                                        <span class="info-box-number"><?php echo e(number_format($summary['rata_persentase'], 2)); ?>%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-red"><i class="fa fa-bell-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Perlu Perhatian</span>
                                        <span class="info-box-number"><?php echo e($summary['warning_count']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="jadwal-desktop-table table-responsive">
                            <table class="table table-hover table-striped jadwal-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Waktu</th>
                                        <th>Matakuliah</th>
                                        <th>Ruangan</th>
                                        <th>Dosen</th>
                                        <th class="text-center">Kehadiran</th>
                                        <th class="text-center">Persentase</th>
                                        <th class="text-center" style="width: 90px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="jadwal-table-body">
                                    <?php $__empty_1 = true; $__currentLoopData = $jadwal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="jadwal-item" data-search="<?php echo e($item->search_text); ?>">
                                            <td><?php echo e($index + 1); ?></td>
                                            <td>
                                                <strong><?php echo e($item->hari ?: '-'); ?></strong>
                                                <span class="course-meta"><?php echo e($item->jam ?: '-'); ?></span>
                                            </td>
                                            <td class="course-cell">
                                                <span class="course-title"><?php echo e($item->makul); ?></span>
                                                <span class="course-meta"><?php echo e($item->kode ?: '-'); ?></span>
                                            </td>
                                            <td><?php echo e($item->nama_ruangan ?: '-'); ?></td>
                                            <td><?php echo e($item->nama ?: '-'); ?></td>
                                            <td class="text-center">
                                                <span class="attendance-value"><?php echo e($item->jml); ?> / <?php echo e($item->total); ?></span>
                                                <span class="attendance-note">Pertemuan hadir</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="label label-<?php echo e($item->persentase_class); ?>">
                                                    <?php echo e(number_format($item->persentase, 2)); ?>%
                                                </span>
                                                <span class="attendance-note"><?php echo e($item->persentase_text); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <a href="/lihatabsen/<?php echo e($item->id_kurperiode); ?>" class="btn btn-info btn-xs">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr id="jadwal-empty-row">
                                            <td colspan="8">
                                                <div class="empty-state">
                                                    <i class="fa fa-calendar-times-o"></i>
                                                    <div>Belum ada jadwal kuliah pada periode aktif.</div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr id="jadwal-not-found" style="display: none;">
                                        <td colspan="8">
                                            <div class="empty-state">
                                                <i class="fa fa-search"></i>
                                                <div>Data jadwal yang dicari tidak ditemukan.</div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="jadwal-mobile-list" id="jadwal-mobile-list">
                            <?php $__empty_1 = true; $__currentLoopData = $jadwal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="jadwal-card jadwal-item" data-search="<?php echo e($item->search_text); ?>">
                                    <div class="jadwal-card-top">
                                        <div>
                                            <div class="jadwal-card-title"><?php echo e($item->makul); ?></div>
                                            <div class="jadwal-card-code"><?php echo e($item->kode ?: '-'); ?></div>
                                        </div>
                                        <span class="label label-<?php echo e($item->persentase_class); ?>">
                                            <?php echo e(number_format($item->persentase, 2)); ?>%
                                        </span>
                                    </div>

                                    <div class="jadwal-card-grid">
                                        <div class="jadwal-card-item">
                                            <span class="jadwal-card-label">Hari</span>
                                            <span class="jadwal-card-value"><?php echo e($item->hari ?: '-'); ?></span>
                                        </div>
                                        <div class="jadwal-card-item">
                                            <span class="jadwal-card-label">Jam</span>
                                            <span class="jadwal-card-value"><?php echo e($item->jam ?: '-'); ?></span>
                                        </div>
                                        <div class="jadwal-card-item">
                                            <span class="jadwal-card-label">Ruangan</span>
                                            <span class="jadwal-card-value"><?php echo e($item->nama_ruangan ?: '-'); ?></span>
                                        </div>
                                        <div class="jadwal-card-item">
                                            <span class="jadwal-card-label">Dosen</span>
                                            <span class="jadwal-card-value"><?php echo e($item->nama ?: '-'); ?></span>
                                        </div>
                                        <div class="jadwal-card-item">
                                            <span class="jadwal-card-label">Kehadiran</span>
                                            <span class="jadwal-card-value"><?php echo e($item->jml); ?> / <?php echo e($item->total); ?> pertemuan</span>
                                        </div>
                                        <div class="jadwal-card-item">
                                            <span class="jadwal-card-label">Status</span>
                                            <span class="jadwal-card-value"><?php echo e($item->persentase_text); ?></span>
                                        </div>
                                    </div>

                                    <div class="jadwal-card-footer">
                                        <span class="attendance-note">Progress kehadiran semester aktif</span>
                                        <a href="/lihatabsen/<?php echo e($item->id_kurperiode); ?>" class="btn btn-info btn-xs">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="empty-state">
                                    <i class="fa fa-calendar-times-o"></i>
                                    <div>Belum ada jadwal kuliah pada periode aktif.</div>
                                </div>
                            <?php endif; ?>

                            <?php if($jadwal->count()): ?>
                                <div class="empty-state" id="jadwal-mobile-empty" style="display: none;">
                                    <i class="fa fa-search"></i>
                                    <div>Data jadwal yang dicari tidak ditemukan.</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(function() {
            var $search = $('#jadwal-search');
            var $items = $('.jadwal-item');
            var $desktopEmpty = $('#jadwal-not-found');
            var $mobileEmpty = $('#jadwal-mobile-empty');

            $search.on('keyup', function() {
                var keyword = $(this).val().toLowerCase().trim();
                var visibleCount = 0;

                $items.each(function() {
                    var $item = $(this);
                    var searchText = ($item.data('search') || '').toString();
                    var isMatch = keyword === '' || searchText.indexOf(keyword) !== -1;

                    $item.toggle(isMatch);

                    if (isMatch) {
                        visibleCount++;
                    }
                });

                if ($desktopEmpty.length) {
                    $desktopEmpty.toggle(visibleCount === 0);
                }

                if ($mobileEmpty.length) {
                    $mobileEmpty.toggle(visibleCount === 0);
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/mhs/jadwal.blade.php ENDPATH**/ ?>