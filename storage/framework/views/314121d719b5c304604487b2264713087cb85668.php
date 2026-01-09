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
            <li><a href="<?php echo e(url('data_bap_gugusmutu')); ?>">BAP Perkuliahan</a></li>
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
                <a href="<?php echo e(url('data_bap_gugusmutu')); ?>" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-table"></i> Daftar Pertemuan & Validasi RPS</h3>
                <div class="box-tools pull-right">
                    <span class="label label-info"><?php echo e(count($data)); ?> Pertemuan</span>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="tabelBap" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-center align-middle" style="vertical-align: middle; width: 100px;">
                                    Pertemuan
                                </th>
                                <th class="text-center align-middle" style="vertical-align: middle;">
                                    Materi Kuliah
                                </th>
                                <th class="text-center align-middle" style="vertical-align: middle;">
                                    Materi Pembelajaran (RPS)
                                </th>
                                <th class="text-center align-middle" style="vertical-align: middle; width: 150px;">
                                    Kesesuaian RPS
                                </th>
                                <th class="text-center align-middle" style="vertical-align: middle; width: 220px;">
                                    Aksi
                                </th>
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
                                    <td>
                                        <small><?php echo e($item->materi_kuliah); ?></small>
                                    </td>
                                    <td>
                                        <small><?php echo e($item->materi_pembelajaran); ?></small>
                                    </td>
                                    <td class="text-center">
                                        <?php if($item->kesesuaian_rps == 'SESUAI'): ?>
                                            <span class="label label-success" style="font-size: 12px; padding: 5px 8px;">
                                                <i class="fa fa-check"></i> SESUAI
                                            </span>
                                        <?php elseif($item->kesesuaian_rps == 'TIDAK SESUAI'): ?>
                                            <span class="label label-danger" style="font-size: 12px; padding: 5px 8px;">
                                                <i class="fa fa-times"></i> TIDAK SESUAI
                                            </span>
                                        <?php else: ?>
                                            <span class="label label-warning" style="font-size: 12px;">BELUM DIVALIDASI</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group mb-2" style="margin-bottom: 5px;">
                                            <a href="/view_bap_gugusmutu/<?php echo e($item->id_bap); ?>" class="btn btn-primary btn-sm"
                                                title="Lihat Detail BAP" data-toggle="tooltip">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <?php if($item->komentar == null): ?>
                                                <button class="btn btn-info btn-sm" data-toggle="modal"
                                                    data-target="#modalTambahKomentar<?php echo e($item->id_rps); ?>" title="Tambah Komentar">
                                                    <i class="fa fa-comment-o"></i>
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-warning btn-sm" data-toggle="modal"
                                                    data-target="#modalTambahKomentar<?php echo e($item->id_rps); ?>" title="Lihat/Edit Komentar">
                                                    <i class="fa fa-comment"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        <div class="btn-group" style="margin-bottom: 5px;">
                                             <a href="/validasi_sesuai/<?php echo e($item->id_bap); ?>"
                                                class="btn btn-success btn-sm" title="Validasi Sesuai" data-toggle="tooltip">
                                                <i class="fa fa-check"></i>
                                            </a>
                                            <a href="/validasi_tidak_sesuai/<?php echo e($item->id_bap); ?>"
                                                class="btn btn-danger btn-sm" title="Validasi Tidak Sesuai" data-toggle="tooltip">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Komentar -->
                                <div class="modal fade" id="modalTambahKomentar<?php echo e($item->id_rps); ?>" tabindex="-1"
                                    aria-labelledby="modalTambahKomentar" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title"><i class="fa fa-comments"></i> Komentar RPS - Pertemuan Ke-<?php echo e($item->pertemuan); ?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/komentar_rps_makul/<?php echo e($item->id_rps); ?>"
                                                    method="post" enctype="multipart/form-data">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('put'); ?>
                                                    <div class="form-group">
                                                        <label>Komentar:</label>
                                                        <textarea class="form-control" name="komentar" rows="5" placeholder="Tulis komentar disini..."><?php echo e($item->komentar); ?></textarea>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default pull-left"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/gugusmutu/perkuliahan/cek_bap.blade.php ENDPATH**/ ?>