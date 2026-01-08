<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                        <td><?php echo e($bap->makul); ?></td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td><?php echo e($bap->prodi); ?></td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td><?php echo e($bap->kelas); ?></td>
                        <td>Semester</td>
                        <td>:</td>
                        <td><?php echo e($bap->semester); ?></td>
                    </tr>
                </table>
            </div>

            <div class="box-body">
                <a href="/cek_sum_absen/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-info">Absensi Perkuliahan</a>
                <a href="/cek_jurnal_bap/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-warning">Jurnal Perkuliahan</a>
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
                            <th colspan="2">
                                <center>Kuliah</center>
                            </th>
                            <th rowspan="2">
                                <center>Absen Mahasiswa <br> Hadir / Tidak </center>
                            </th>
                            <th rowspan="2">
                                <center>Aksi</center>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <center>Ke-<?php echo e($item->pertemuan); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e(Carbon\Carbon::parse($item->tanggal)->format('d-m-Y')); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e(Carbon\Carbon::parse($item->created_at)->format('d-m-Y')); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e(Carbon\Carbon::parse($item->jam_mulai)->format('H:i')); ?> -
                                        <?php echo e(Carbon\Carbon::parse($item->jam_selsai)->format('H:i')); ?>

                                    </center>
                                </td>
                                <td>
                                    <center><?php echo e(Carbon\Carbon::parse($item->kurang_jam)->format('H:i')); ?></center>
                                </td>
                                <td><?php echo e($item->materi_kuliah); ?></td>
                                <td>
                                    <center><?php echo e($item->tipe_kuliah); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->jenis_kuliah); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->hadir); ?> / <?php echo e($item->tidak_hadir); ?></center>
                                </td>
                                <td>
                                    <center>
                                        <a href="/cek_view_bap/<?php echo e($item->id_bap); ?>" class="btn btn-info btn-xs"
                                            title="klik untuk lihat BAP"> <i class="fa fa-eye"></i></a>
                                        <a href="/cek_absen_bap/<?php echo e($item->id_bap); ?>" class="btn btn-warning btn-xs"
                                            title="klik untuk lihat absensi"><i class="fa fa-edit"></i></a>
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/sadmin/perkuliahan/cek_bap.blade.php ENDPATH**/ ?>