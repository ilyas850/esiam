<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Dashboard</a></li>
                <li><a href="#tab_3" data-toggle="tab">Pelaksanaan Akademik</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box box-widget widget-user-2">
                                <div class="widget-user-header bg-aqua-active">
                                    <div class="widget-user-image">
                                        <img class="img-circle" src="/adminlte/img/default.jpg" alt="User Avatar">
                                    </div>
                                    <h3 class="widget-user-username"><?php echo e(Auth::user()->name); ?></h3>
                                    <h5 class="widget-user-desc">Dosen</h5>
                                </div>
                                <div class="box-footer no-padding">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th style="width:30%">Nama</th>
                                                <td style="width:5%">:</td>
                                                <td><?php echo e($dsn->nama); ?>, <?php echo e($dsn->akademik); ?> </td>
                                            </tr>
                                            <tr>
                                                <th>NIK</th>
                                                <td>:</td>
                                                <td><?php echo e(Auth::user()->username); ?></td>
                                            </tr>
                                            <tr>
                                                <th>No HP</th>
                                                <td>:</td>
                                                <td><?php echo e($dsn->hp); ?></td>
                                            </tr>
                                            <tr>
                                                <th>E-Mail</th>
                                                <td>:</td>
                                                <td><?php echo e($dsn->email); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="small-box bg-aqua">
                                        <div class="inner">
                                            <h3><i class="fa fa-calendar"></i></h3>
                                            <p><?php echo e($tahun->periode_tahun); ?> <?php echo e($tipe->periode_tipe); ?></p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <a href="<?php echo e(asset('/Kalender Akademik/' . $tahun->file)); ?>" target="_blank"
                                            class="small-box-footer">Unduh Kalender Akademik <i
                                                class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h3><i class="fa fa-calendar-check-o"></i></h3>
                                            <p>
                                                <?php if($time->status == 0): ?>
                                                    Jadwal Belum ada
                                                <?php elseif($time->status == 1): ?>
                                                    <?php echo e(date(' d-m-Y', strtotime($time->waktu_awal))); ?> s/d
                                                    <?php echo e(date(' d-m-Y', strtotime($time->waktu_akhir))); ?>

                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <div class="small-box-footer">
                                            Jadwal KRS
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Informasi Terbaru</h3>
                                            <div class="box-tools pull-right">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                        class="fa fa-minus"></i>
                                                </button>
                                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                                        class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <ul class="products-list product-list-in-box">
                                                <?php $__currentLoopData = $info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="item">
                                                        <div class="product-img">
                                                            <img class="img-circle" src="/images/bell.jpg" alt="user">
                                                        </div>
                                                        <div class="product-info">
                                                            <a href="/lihat/<?php echo e($item->id_informasi); ?>"
                                                                class="product-title"><?php echo e($item->judul); ?>

                                                                <span class="label label-info pull-right">
                                                                    <?php echo e(date('l, d F Y', strtotime($item->created_at))); ?><br>
                                                                    <?php echo e($item->created_at->diffForHumans()); ?>

                                                                </span></a>
                                                            <span class="product-description"><?php echo e($item->deskripsi); ?></span>
                                                        </div>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                        <div class="box-footer text-center">
                                            <a href="/lihat_semua" class="uppercase">Lihat Semua Informasi</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_3">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ketercapaian Pembelajaran</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th width="10px">No</th>
                                            <th>Matakuliah</th>
                                            <th>SKS</th>
                                            <th>Prodi</th>
                                            <th>Kelas</th>
                                            <th>
                                                <center>Pertemuan</center>
                                            </th>
                                            <th>
                                                <center>Persentase Pembelajaran</center>
                                            </th>
                                            <th>
                                                <center>Kuliah Offline / Online</center>
                                            </th>
                                            <th>
                                                <center>Persentase Absensi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php $__currentLoopData = $data_akademik; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($no++); ?></td>
                                                <td><?php echo e($item->makul); ?></td>
                                                <td><?php echo e($item->sks); ?></td>
                                                <td><?php echo e($item->prodi); ?></td>
                                                <td><?php echo e($item->kelas); ?></td>
                                                <td align="center"><?php echo e($item->jml_per); ?> / 16</td>
                                                <td align="center">
                                                    <?php if($item->jml_per <= 7): ?>
                                                        <span class="label label-danger">
                                                            <?php echo e($item->persentase); ?> %</span>
                                                    <?php elseif($item->jml_per < 16): ?>
                                                        <span class="label label-warning">
                                                            <?php echo e($item->persentase); ?> %</span>
                                                    <?php elseif($item->jml_per = 16): ?>
                                                        <span class="label label-success">
                                                            <?php echo e($item->persentase); ?> %</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td align="center">
                                                    <span class="label label-info"><i class="fa fa-users"></i>
                                                        <?php echo e($item->jml_offline); ?> Offline</span>
                                                    <span class="label label-success"><i class="fa fa-wifi"></i>
                                                        <?php echo e($item->jml_online); ?> Online</span>
                                                </td>
                                                <td align="center">
                                                    <a href="/persentase_absensi_mhs_dsnlr/<?php echo e(Crypt::encryptString($item->id_kurperiode)); ?>"
                                                        class="btn btn-info btn-xs">Cek Absensi</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH /var/www/html/resources/views/layouts/dosenluar_home.blade.php ENDPATH**/ ?>