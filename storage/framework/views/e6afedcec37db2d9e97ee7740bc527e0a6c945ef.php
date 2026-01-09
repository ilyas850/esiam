<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-clipboard"></i> Detail Berita Acara Perkuliahan
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-home"></i> Halaman Utama</a></li>
            <li><a href="<?php echo e(url('rekap_perkuliahan')); ?>">Rekap Perkuliahan</a></li>
            <li class="active">Cek BAP</li>
        </ol>
    </section>

    <section class="content">
        <?php if($message = Session::get('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-check"></i> <?php echo e($message); ?>

            </div>
        <?php endif; ?>

        <!-- Info Card -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-info-circle"></i> Informasi Mata Kuliah</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box bg-aqua">
                            <span class="info-box-icon"><i class="fa fa-book"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Mata Kuliah</span>
                                <span class="info-box-number"><?php echo e($bap->makul); ?></span>
                                <span class="progress-description">
                                    Kelas: <strong><?php echo e($bap->kelas); ?></strong> | Semester: <strong><?php echo e($bap->semester); ?></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="fa fa-graduation-cap"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Program Studi</span>
                                <span class="info-box-number"><?php echo e($bap->prodi); ?></span>
                                <span class="progress-description">
                                    Total Pertemuan: <strong><?php echo e(count($data)); ?></strong> dari 16
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="box box-default">
            <div class="box-body">
                <a href="<?php echo e(url('rekap_perkuliahan')); ?>" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
                <a href="/cek_sum_absen/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-info">
                    <i class="fa fa-list-alt"></i> Absensi Perkuliahan
                </a>
                <a href="/cek_jurnal_bap/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-warning">
                    <i class="fa fa-book"></i> Jurnal Perkuliahan
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-table"></i> Daftar Pertemuan</h3>
                <div class="box-tools pull-right">
                    <span class="label label-info"><?php echo e(count($data)); ?> Pertemuan</span>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="tabelBap" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="bg-primary">
                                <th rowspan="2" class="text-center align-middle" style="vertical-align: middle; width: 80px;">
                                    Pertemuan
                                </th>
                                <th colspan="2" class="text-center">Tanggal</th>
                                <th rowspan="2" class="text-center align-middle" style="vertical-align: middle;">Jam</th>
                                <th rowspan="2" class="text-center align-middle" style="vertical-align: middle; width: 80px;">
                                    <i class="fa fa-clock-o"></i> Kurang
                                </th>
                                <th rowspan="2" class="text-center align-middle" style="vertical-align: middle;">
                                    Materi Kuliah
                                </th>
                                <th colspan="2" class="text-center">Mode Kuliah</th>
                                <th rowspan="2" class="text-center align-middle" style="vertical-align: middle; width: 100px;">
                                    <i class="fa fa-users"></i> Kehadiran
                                </th>
                                <th rowspan="2" class="text-center align-middle" style="vertical-align: middle; width: 100px;">
                                    Aksi
                                </th>
                            </tr>
                            <tr class="bg-primary">
                                <th class="text-center"><i class="fa fa-calendar"></i> Kuliah</th>
                                <th class="text-center"><i class="fa fa-calendar-check-o"></i> Aktual</th>
                                <th class="text-center">Tipe</th>
                                <th class="text-center">Jenis</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-blue" style="font-size: 14px; padding: 8px 12px;">
                                            Ke-<?php echo e($item->pertemuan); ?>

                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php echo e(Carbon\Carbon::parse($item->tanggal)->format('d M Y')); ?>

                                    </td>
                                    <td class="text-center">
                                        <?php echo e(Carbon\Carbon::parse($item->created_at)->format('d M Y')); ?>

                                    </td>
                                    <td class="text-center">
                                        <span class="label label-default">
                                            <?php echo e(Carbon\Carbon::parse($item->jam_mulai)->format('H:i')); ?> -
                                            <?php echo e(Carbon\Carbon::parse($item->jam_selsai)->format('H:i')); ?>

                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            $kurangJam = Carbon\Carbon::parse($item->kurang_jam)->format('H:i');
                                        ?>
                                        <?php if($kurangJam != '00:00'): ?>
                                            <span class="label label-danger"><?php echo e($kurangJam); ?></span>
                                        <?php else: ?>
                                            <span class="label label-success"><?php echo e($kurangJam); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small><?php echo e(Str::limit($item->materi_kuliah, 50)); ?></small>
                                    </td>
                                    <td class="text-center">
                                        <?php if($item->tipe_kuliah == 'Online'): ?>
                                            <span class="label label-info"><i class="fa fa-wifi"></i> <?php echo e($item->tipe_kuliah); ?></span>
                                        <?php else: ?>
                                            <span class="label label-success"><i class="fa fa-building"></i> <?php echo e($item->tipe_kuliah); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="label label-default"><?php echo e($item->jenis_kuliah); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-success"><strong><?php echo e($item->hadir); ?></strong></span>
                                        <span class="text-muted">/</span>
                                        <span class="text-danger"><strong><?php echo e($item->tidak_hadir); ?></strong></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="/cek_view_bap/<?php echo e($item->id_bap); ?>" class="btn btn-primary btn-sm"
                                                title="Lihat Detail BAP" data-toggle="tooltip">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="/cek_absen_bap/<?php echo e($item->id_bap); ?>" class="btn btn-warning btn-sm"
                                                title="Lihat/Edit Absensi" data-toggle="tooltip">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="description-block border-right">
                            <span class="description-percentage text-green"><i class="fa fa-check-circle"></i></span>
                            <h5 class="description-header"><?php echo e(count($data)); ?> / 16</h5>
                            <span class="description-text">TOTAL PERTEMUAN</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="description-block border-right">
                            <span class="description-percentage text-info"><i class="fa fa-wifi"></i></span>
                            <h5 class="description-header">
                                <?php echo e(collect($data)->where('tipe_kuliah', 'Online')->count()); ?>

                            </h5>
                            <span class="description-text">PERTEMUAN ONLINE</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="description-block">
                            <span class="description-percentage text-success"><i class="fa fa-building"></i></span>
                            <h5 class="description-header">
                                <?php echo e(collect($data)->where('tipe_kuliah', 'Offline')->count()); ?>

                            </h5>
                            <span class="description-text">PERTEMUAN OFFLINE</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .info-box-number {
            font-size: 18px;
        }
        .progress-description {
            font-size: 13px;
        }
        .table > thead > tr > th {
            border-bottom: 2px solid #ddd;
        }
        .badge {
            border-radius: 4px;
        }
        .btn-group .btn {
            margin-right: 2px;
        }
        .description-header {
            font-size: 24px;
            font-weight: bold;
        }
        .box-footer .description-block {
            padding: 15px 0;
        }
        .align-middle {
            vertical-align: middle !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/sadmin/perkuliahan/cek_bap.blade.php ENDPATH**/ ?>