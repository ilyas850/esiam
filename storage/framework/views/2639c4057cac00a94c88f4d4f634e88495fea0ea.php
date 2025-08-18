<div class="row fade-in">
    <div class="col-md-4 col-md-12">
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?php echo e($ti); ?> orang</h3>
                <p>Mahasiswa Teknik Industri</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-md-12">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?php echo e($tk); ?> orang</h3>
                <p>Mahasiswa Teknologi Rekayasa Perangkat Lunak</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-md-12">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?php echo e($fa); ?> orang</h3>
                <p>Mahasiswa Farmasi</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-12 slide-in-left">
        <div class="box box-info enhanced-animations">
            <div class="box-header with-border">
                <span class="fa fa-calendar"></span>
                <h3 class="box-title">Tambah Tahun Akademik Aktif</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="form" role="form" action="<?php echo e(url('add_ta')); ?>" method="POST">
                        <?php echo e(csrf_field()); ?>

                        <div class="col-xs-7">
                            <input type="text" class="form-control enhanced-animations" name="periode_tahun"
                                placeholder="T.A.2019/2020" required id="periode-input">
                        </div>
                        <input type="hidden" name="status" value="ACTIVE">
                        <button type="submit" class="btn btn-info"
                            data-tooltip="Klik untuk menambah tahun akademik baru">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="box box-info enhanced-animations">
            <div class="box-header">
                <h3 class="box-title">Data Periode Tahun</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Periode Tahun</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($item->periode_tahun); ?></td>
                                <td><?php echo e($item->status); ?></td>
                                <td>
                                    <?php if($item->status == 'ACTIVE'): ?>
                                        <span class="badge bg-yellow">AKTIF</span>
                                    <?php elseif($item->status == 'NOT ACTIVE'): ?>
                                        <form method="POST" action="<?php echo e(url('change_ta_thn')); ?>">
                                            <input type="hidden" name="status" value="ACTIVE">
                                            <input type="hidden" name="id_periodetahun" value="<?php echo e($item->id_periodetahun); ?>">
                                            <?php echo e(csrf_field()); ?>

                                            <button type="submit" class="btn btn-info btn-xs"
                                                data-tooltip="Aktifkan periode ini">Aktifkan</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-md-12 slide-in-right">
        <div class="box box-info enhanced-animations">
            <div class="box-header">
                <h3 class="box-title">Data Periode Tipe</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Periode Tipe</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($item->periode_tipe); ?></td>
                            <td><?php echo e($item->status); ?></td>
                            <td>
                                <?php if($item->status == 'ACTIVE'): ?>
                                    <span class="badge bg-yellow">AKTIF</span>
                                <?php elseif($item->status == 'NOT ACTIVE'): ?>
                                    <form method="POST" action="<?php echo e(url('change_ta_tp')); ?>">
                                        <input type="hidden" name="status" value="ACTIVE">
                                        <input type="hidden" name="id_periodetipe" value="<?php echo e($item->id_periodetipe); ?>">
                                        <?php echo e(csrf_field()); ?>

                                        <button type="submit" class="btn btn-info btn-xs"
                                            data-tooltip="Aktifkan tipe periode ini">Aktifkan</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
            </div>
        </div>
        <div class="box box-info enhanced-animations">
            <div class="box-header with-border">
                <span class="fa fa-calendar-check-o"></span>
                <h3 class="box-title"><b>KRS Periode <?php $__currentLoopData = $tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($key->status == 'ACTIVE'): ?>
                        <?php echo e($key->periode_tahun); ?>

                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($key->status == 'ACTIVE'): ?>
                                <?php echo e($key->periode_tipe); ?>

                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </b></h3>
            </div>
            <div class="box-body">
                <?php if($time->status == 0): ?>
                    <div id="krs-status-open" style="display: block;">
                        <form method="POST" action="<?php echo e(url('save_krs_time')); ?>" id="krs-open-form">
                            <?php echo e(csrf_field()); ?>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Waktu Awal KRS:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control" id="datepicker3" value="<?php echo e($now); ?>"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Atur Waktu Akhir KRS:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input type="text" class="form-control enhanced-animations" id="datepicker"
                                            name="waktu_akhir" value="<?php echo e($time->waktu_akhir); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="status" value="1">
                            <input type="hidden" name="waktu_awal" value="<?php echo e($now); ?>">
                            <input type="hidden" name="id" value="<?php echo e($time->id); ?>">
                            <button type="submit" class="btn btn-info btn-lg btn-block"
                                data-tooltip="Mulai periode pengisian KRS">
                                <i class="fa fa-unlock"></i> KRS Dibuka
                            </button>
                        </form>
                    </div>
                <?php elseif($time->status == 1): ?>
                    <div id="krs-status-close">
                        <form method="POST" action="<?php echo e(url('delete_time_krs')); ?>" id="krs-close-form">
                            <?php echo e(csrf_field()); ?>

                            <div class="form-group">
                                <label>Hentikan Waktu Pengisian KRS:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right"
                                        value="<?php echo e($time->waktu_awal); ?> sampai <?php echo e($time->waktu_akhir); ?>" readonly>
                                </div>
                            </div>
                            <input type="hidden" name="status" value="0">
                            <input type="hidden" name="id" value="<?php echo e($time->id); ?>">
                            <button type="button" class="btn btn-warning btn-lg btn-block" data-toggle="modal"
                                data-target="#modal-warning" data-tooltip="Tutup periode pengisian KRS">
                                <i class="fa fa-lock"></i> Tutup Pengisian KRS
                            </button>
                            <div class="modal modal-warning fade" id="modal-warning">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Peringatan</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah anda yakin akan menutup pengisian KRS ?&hellip;</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline pull-left"
                                                data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-outline">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <div class="box box-info enhanced-animations">
            <div class="box-header with-border">
                <span class="glyphicon glyphicon-info-sign"></span>
                <h3 class="box-title">Informasi Pengisian KRS</h3>
            </div>
            <div class="box-body">
                <div id="waktumundur">
                    <?php if($time->status != 0): ?>
                        <span id="countdown">Loading countdown...</span>
                    <?php else: ?>
                        Belum ada info perwalian
                    <?php endif; ?>
                </div>
            </div>
            <script type='text/javascript'>
                //<![CDATA[
                var target_date = new Date("<?php echo e($time->waktu_akhir); ?>").getTime();
                var days, hours, minutes, seconds;
                var countdown = document.getElementById("countdown");
                setInterval(function () {
                    var current_date = new Date().getTime();
                    var seconds_left = (target_date - current_date) / 1000;
                    days = parseInt(seconds_left / 86400);
                    seconds_left = seconds_left % 86400;
                    hours = parseInt(seconds_left / 3600);
                    seconds_left = seconds_left % 3600;
                    minutes = parseInt(seconds_left / 60);
                    seconds = parseInt(seconds_left % 60);
                    countdown.innerHTML = days + " <span class=\'digit\'>hari</span> " + hours +
                        " <span class=\'digit\'>jam</span> " + minutes + " <span class=\'digit\'>menit</span> " + seconds +
                        " <span class=\'digit\'>detik menuju</span> <span class=\'judul\'>Penutupan Pengisian KRS</span>";
                }, 1000);
                //]]>
            </script>
        </div>
        <style scoped="" type="text/css">
            #waktumundur {
                background: #31266b;
                color: #fec503;
                font-size: 100%;
                text-transform: uppercase;
                text-align: center;
                padding: 20px 0;
                font-weight: bold;
                border-radius: 5px;
                line-height: 1.8em;
                font-family: Arial, sans-serif;
            }

            .digit {
                color: white
            }

            .judul {
                color: white
            }
        </style>
    </div>

    <?php /**PATH /var/www/html/resources/views/layouts/admin_home.blade.php ENDPATH**/ ?>