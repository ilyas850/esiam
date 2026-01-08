<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <section class="content-header">
        <h1>
            Berita Acara Perkuliahan
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="<?php echo e(url('rekap_perkuliahan')); ?>"> Rekap perkuliahan</a></li>
            <li class="active">Cek BAP</li>
        </ol>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <?php if($message = Session::get('success')): ?>
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong><?php echo e($message); ?></strong>
            </div>
        <?php endif; ?>
        <div class="box box-info">
            <div class="box-header with-border">
                <table width="100%">
                    <tr>
                        <td>Matakuliah</td>
                        <td>:</td>
                        <td><?php echo e($key->makul); ?></td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td><?php echo e($key->prodi); ?></td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td><?php echo e($key->kelas); ?></td>
                        <td>Semester</td>
                        <td>:</td>
                        <td><?php echo e($key->semester); ?></td>
                    </tr>
                </table>
            </div>

            <div class="box-body">
                <a href="/cek_sum_absen_kprd/<?php echo e($key->id_kurperiode); ?>" class="btn btn-info">Absensi Perkuliahan</a>
                <a href="/cek_jurnal_bap_kprd/<?php echo e($key->id_kurperiode); ?>" class="btn btn-warning">Jurnal Perkuliahan</a>
                <br><br>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>

                            <th rowspan="2">
                                <center>Pertemuan</center>
                            </th>
                            <th colspan="2">
                                <center>Tanggal</center>
                            </th>
                            <th rowspan="2">
                                <center>Jam</center>
                            </th>
                            <th rowspan="2">
                                <center>Kurang Jam</center>
                            </th>
                            <th rowspan="2">
                                <center>Materi Kuliah</center>
                            </th>
                            <th colspan="3">
                                <center>Kuliah</center>
                            </th>
                            <th colspan="2">
                                <center>Absen Mahasiswa</center>
                            </th>
                            <th rowspan="2">
                                <center>Aksi</center>
                            </th>
                            <th rowspan="2">
                                <center>Validasi</center>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <center>Kuliah</center>
                            </th>
                            <th>
                                <center>Aktual</center>
                            </th>
                            <th>
                                <center>Tipe</center>
                            </th>
                            <th>
                                <center>Jenis</center>
                            </th>
                            <th>
                                <center>Metode</center>
                            </th>
                            <th>
                                <center>Hadir</center>
                            </th>
                            <th>
                                <center>Tidak</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <center>Ke-<?php echo e($item->pertemuan); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->tanggal->isoFormat('D-M-Y')); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->created_at->isoFormat('D-M-Y')); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->jam_mulai); ?> - <?php echo e($item->jam_selsai); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->kurang_jam); ?></center>
                                </td>
                                <td><?php echo e($item->materi_kuliah); ?></td>
                                <td>
                                    <center><?php echo e($item->tipe_kuliah); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->jenis_kuliah); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->metode_kuliah); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->hadir); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->tidak_hadir); ?></center>
                                </td>
                                <td>
                                    <center>
                                        <a href="/cek_view_bap_kprd/<?php echo e($item->id_bap); ?>" class="btn btn-info btn-xs"
                                            title="klik untuk lihat"> <i class="fa fa-eye"></i></a>

                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <?php if($item->tanggal_validasi != null): ?>
                                            <span class="badge bg-yellow">Valid</span>
                                        <?php elseif($item->tanggal_validasi == null): ?>
                                            <span class="badge bg-red">Belum</span>
                                        <?php endif; ?>
                                    </center>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/kaprodi/perkuliahan/cek_bap.blade.php ENDPATH**/ ?>