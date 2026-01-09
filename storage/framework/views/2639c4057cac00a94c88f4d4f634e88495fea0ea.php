<div class="row fade-in">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?php echo e($ti); ?></h3>
                <p>Mahasiswa Teknik Industri</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-gear"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?php echo e($trpl); ?></h3>
                <p>Mahasiswa TRPL</p>
            </div>
            <div class="icon">
                <i class="ion ion-code"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?php echo e($logs); ?></h3>
                <p>Mahasiswa Terapan Rekayasa Logistik</p>
            </div>
            <div class="icon">
                <i class="ion ion-cube"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?php echo e($fa); ?></h3>
                <p>Mahasiswa Farmasi</p>
            </div>
            <div class="icon">
                <i class="ion ion-medkit"></i>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <!-- Left Column: Tahun Akademik & Periode Tipe -->
    <div class="col-md-6 slide-in-left">
        <!-- Tahun Akademik Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-calendar text-blue"></i>
                <h3 class="box-title">Tahun Akademik</h3>
            </div>
            <div class="box-body">
                <!-- Add New Form -->
                <div class="well well-sm" style="background: #f4f8fa; border: none;">
                    <form class="form-inline" role="form" action="<?php echo e(url('add_ta')); ?>" method="POST">
                        <?php echo e(csrf_field()); ?>

                        <div class="form-group" style="width: 70%;">
                            <div class="input-group" style="width: 100%;">
                                <div class="input-group-addon"><i class="fa fa-plus"></i></div>
                                <input type="text" class="form-control" name="periode_tahun"
                                    placeholder="Contoh: T.A.2023/2024" required style="width: 100%;">
                            </div>
                        </div>
                        <input type="hidden" name="status" value="ACTIVE">
                        <button type="submit" class="btn btn-primary btn-flat pull-right" style="width: 28%;">
                            <i class="fa fa-save"></i> Tambah
                        </button>
                    </form>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Periode Tahun</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($item->periode_tahun); ?></strong></td>
                                    <td class="text-center">
                                        <?php if($item->status == 'ACTIVE'): ?>
                                            <span class="label label-success"><i class="fa fa-check"></i> AKTIF</span>
                                        <?php else: ?>
                                            <span class="label label-default">NON-AKTIF</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($item->status == 'NOT ACTIVE'): ?>
                                            <form method="POST" action="<?php echo e(url('change_ta_thn')); ?>">
                                                <input type="hidden" name="status" value="ACTIVE">
                                                <input type="hidden" name="id_periodetahun" value="<?php echo e($item->id_periodetahun); ?>">
                                                <?php echo e(csrf_field()); ?>

                                                <button type="submit" class="btn btn-success btn-xs btn-flat" title="Aktifkan">
                                                    <i class="fa fa-check-circle"></i> Aktifkan
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-default btn-xs btn-flat" disabled>
                                                <i class="fa fa-check"></i> Terpilih
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Periode Tipe Box -->
        <div class="box box-success">
            <div class="box-header with-border">
                <i class="fa fa-tag text-green"></i>
                <h3 class="box-title">Tipe Periode (Semester)</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Tipe Periode</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($item->periode_tipe); ?></strong></td>
                                    <td class="text-center">
                                        <?php if($item->status == 'ACTIVE'): ?>
                                            <span class="label label-success"><i class="fa fa-check"></i> AKTIF</span>
                                        <?php else: ?>
                                            <span class="label label-default">NON-AKTIF</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($item->status == 'ACTIVE'): ?>
                                            <button class="btn btn-default btn-xs btn-flat" disabled>
                                                <i class="fa fa-check"></i> Terpilih
                                            </button>
                                        <?php elseif($item->status == 'NOT ACTIVE'): ?>
                                            <form method="POST" action="<?php echo e(url('change_ta_tp')); ?>">
                                                <input type="hidden" name="status" value="ACTIVE">
                                                <input type="hidden" name="id_periodetipe" value="<?php echo e($item->id_periodetipe); ?>">
                                                <?php echo e(csrf_field()); ?>

                                                <button type="submit" class="btn btn-success btn-xs btn-flat" title="Aktifkan">
                                                    <i class="fa fa-check-circle"></i> Aktifkan
                                                </button>
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
    </div>

    <!-- Right Column: KRS Control -->
    <div class="col-md-6 slide-in-right">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-cogs"></i> Kontrol KRS</h3>
                <div class="box-tools pull-right">
                    <span class="label label-warning" style="font-size: 14px;">
                        <?php $__currentLoopData = $tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($key->status == 'ACTIVE'): ?> <?php echo e($key->periode_tahun); ?> <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        -
                        <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($key->status == 'ACTIVE'): ?> <?php echo e($key->periode_tipe); ?> <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </span>
                </div>
            </div>
            <div class="box-body" style="background: #f9f9f9;">
                <div class="text-center" style="margin-bottom: 20px;">
                    <h4>Status Pengisian KRS saat ini:</h4>
                    <?php if($time->status == 1): ?>
                        <span class="badge bg-green" style="font-size: 18px; padding: 10px 20px;">
                            <i class="fa fa-unlock"></i> DIBUKA
                        </span>
                    <?php else: ?>
                        <span class="badge bg-red" style="font-size: 18px; padding: 10px 20px;">
                            <i class="fa fa-lock"></i> DITUTUP
                        </span>
                    <?php endif; ?>
                </div>

                <hr>

                <?php if($time->status == 0): ?>
                    <!-- Form Buka KRS -->
                    <div id="krs-status-open">
                        <form method="POST" action="<?php echo e(url('save_krs_time')); ?>">
                            <?php echo e(csrf_field()); ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-muted">Waktu Mulai:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon bg-gray"><i class="fa fa-calendar-check-o"></i></span>
                                            <input type="text" class="form-control" value="<?php echo e($now); ?>" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-danger">Batas Akhir:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon bg-red"><i class="fa fa-calendar-times-o"></i></span>
                                            <input type="text" class="form-control" id="datepicker" name="waktu_akhir"
                                                value="<?php echo e($time->waktu_akhir); ?>" required placeholder="Pilih Tanggal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="status" value="1">
                            <input type="hidden" name="waktu_awal" value="<?php echo e($now); ?>">
                            <input type="hidden" name="id" value="<?php echo e($time->id); ?>">
                            <button type="submit" class="btn btn-success btn-lg btn-block btn-flat">
                                <i class="fa fa-unlock-alt"></i> BUKA PENGISIAN KRS
                            </button>
                        </form>
                    </div>
                <?php elseif($time->status == 1): ?>
                    <!-- Form Tutup KRS -->
                    <div id="krs-status-close">
                        <div class="callout callout-info" style="margin-bottom: 20px;">
                            <p><i class="fa fa-clock-o"></i> <strong>Waktu Aktif:</strong><br>
                            <?php echo e($time->waktu_awal); ?> s/d <?php echo e($time->waktu_akhir); ?></p>
                        </div>

                        <button type="button" class="btn btn-danger btn-lg btn-block btn-flat" data-toggle="modal" data-target="#modal-warning">
                            <i class="fa fa-lock"></i> TUTUP PENGISIAN KRS
                        </button>

                        <!-- Modal Konfirmasi -->
                        <div class="modal modal-danger fade" id="modal-warning">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title"><i class="fa fa-warning"></i> Konfirmasi Penutupan</h4>
                                    </div>
                                    <div class="modal-body text-center">
                                        <h3>Apakah Anda yakin?</h3>
                                        <p>Pengisian KRS akan dihentikan dan mahasiswa tidak dapat mengakses menu KRS lagi.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="POST" action="<?php echo e(url('delete_time_krs')); ?>">
                                            <?php echo e(csrf_field()); ?>

                                            <input type="hidden" name="status" value="0">
                                            <input type="hidden" name="id" value="<?php echo e($time->id); ?>">
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-outline"><i class="fa fa-check"></i> Ya, Tutup KRS</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Countdown Timer -->
         <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-hourglass-half"></i> Hitung Mundur</h3>
            </div>
            <div class="box-body bg-warning" style="background-color: #f39c12 !important; color: white;">
                <div id="waktumundur">
                    <?php if($time->status != 0): ?>
                        <span id="countdown"><i class="fa fa-spinner fa-spin"></i> Memuat waktu...</span>
                    <?php else: ?>
                        <h3><i class="fa fa-bed"></i> KRS Tidak Aktif</h3>
                        <p>Belum ada jadwal pengisian KRS yang sedang berjalan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script type='text/javascript'>
            //<![CDATA[
            var target_date = new Date("<?php echo e($time->waktu_akhir); ?>").getTime();
            var days, hours, minutes, seconds;
            var countdown = document.getElementById("countdown");
            var intervalId = setInterval(function () {
                var current_date = new Date().getTime();
                var seconds_left = (target_date - current_date) / 1000;

                if (seconds_left < 0) {
                    countdown.innerHTML = "<h3>WAKTU HABIS</h3>";
                    clearInterval(intervalId);
                    return;
                }

                days = parseInt(seconds_left / 86400);
                seconds_left = seconds_left % 86400;
                hours = parseInt(seconds_left / 3600);
                seconds_left = seconds_left % 3600;
                minutes = parseInt(seconds_left / 60);
                seconds = parseInt(seconds_left % 60);

                countdown.innerHTML = `
                    <div class="row">
                        <div class="col-xs-3 border-right">
                            <span style="font-size: 30px; font-weight: 800;">${days}</span><br>HARI
                        </div>
                        <div class="col-xs-3 border-right">
                            <span style="font-size: 30px; font-weight: 800;">${hours}</span><br>JAM
                        </div>
                        <div class="col-xs-3 border-right">
                            <span style="font-size: 30px; font-weight: 800;">${minutes}</span><br>MENIT
                        </div>
                        <div class="col-xs-3">
                            <span style="font-size: 30px; font-weight: 800;">${seconds}</span><br>DETIK
                        </div>
                    </div>
                    <div style="margin-top: 10px; font-size: 14px;">MENUJU PENUTUPAN KRS</div>
                `;
            }, 1000);
            //]]>
        </script>
        <style scoped="" type="text/css">
            #waktumundur {
                color: #fff;
                text-align: center;
                padding: 10px;
                font-family: 'Source Sans Pro', sans-serif;
            }
            .border-right {
                border-right: 1px solid rgba(255,255,255,0.3);
            }
        </style>
    </div>
</div>

    <?php /**PATH /var/www/html/resources/views/layouts/admin_home.blade.php ENDPATH**/ ?>