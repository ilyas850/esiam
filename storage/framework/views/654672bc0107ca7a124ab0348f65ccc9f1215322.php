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
                <a href="/input_bap/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-success">Input BAP</a>
                <a href="/sum_absen/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-info">Absensi Perkuliahan</a>
                <a href="/jurnal_bap/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-warning">Jurnal Perkuliahan</a>
                <br><br>
                <table id="example6" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>Pertemuan</center>
                            </th>
                            <th>
                                <center>Tanggal</center>
                            </th>
                            <th>
                                <center>Jam</center>
                            </th>
                            <th>
                                <center>Kurang Jam</center>
                            </th>
                            <th>
                                <center>Aktual Materi Pembelajaran</center>
                            </th>
                            <th>
                                <center>Alasan Pembaharuan Materi</center>
                            </th>
                            <th>
                                <center>Aktual Materi Praktikum</center>
                            </th>
                            <th>
                                <center>Kesesuaian RPS</center>
                            </th>
                            <th>
                                <center>Tipe Kuliah</center>
                            </th>
                            <th>
                                <center>Absensi <br> (Hadir/Tidak)</center>
                            </th>
                            <th>
                                <center>Absen</center>
                            </th>
                            <th>
                                <center>Dosen</center>
                            </th>
                            <th>
                                <center>Action</center>
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
                                    <center>
                                        <?php echo e(Carbon\Carbon::parse($item->jam_mulai)->format('H:i')); ?>-<?php echo e(Carbon\Carbon::parse($item->jam_selsai)->format('H:i')); ?>

                                    </center>
                                </td>
                                <td align="center">
                                    <?php if($item->kurang_jam != null): ?>
                                        <?php echo e(Carbon\Carbon::parse($item->kurang_jam)->format('H:i')); ?>

                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($item->materi_kuliah); ?></td>
                                <td><?php echo e($item->alasan_pembaharuan_materi); ?></td>
                                <td><?php echo e($item->praktikum); ?></td>
                                <td><b>
                                        <?php if($item->kesesuaian_rps == 'SESUAI'): ?>
                                            <span>&#10003;</span>
                                        <?php elseif($item->kesesuaian_rps == 'TIDAK SESUAI'): ?>
                                            <span>&#10007;</span>
                                        <?php endif; ?>
                                    </b>
                                    <?php if($item->komentar != null): ?>
                                        <a class="btn btn-warning btn-xs" data-toggle="modal"
                                            data-target="#modalTambahKomentar<?php echo e($item->id_rps); ?>"> <i
                                                class="fa fa-eye "></i> Lihat</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <center><?php echo e($item->tipe_kuliah); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->hadir); ?> / <?php echo e($item->tidak_hadir); ?></center>
                                </td>
                                <td>
                                    <center>
                                        <?php if($item->hadir != null && $item->tidak_hadir != null): ?>
                                            <a href="/edit_absen/<?php echo e($item->id_bap); ?>" class="btn btn-success btn-xs">
                                                Edit</a>
                                        <?php elseif($item->hadir == null && $item->tidak_hadir == null): ?>
                                            <a href="/entri_absen/<?php echo e($item->id_bap); ?>" class="btn btn-warning btn-xs">
                                                Entri</a>
                                        <?php endif; ?>
                                    </center>
                                </td>
                                <td>
                                    <?php echo e($item->nama); ?>

                                </td>
                                <td>
                                    <center>
                                        <a href="/view_bap/<?php echo e($item->id_bap); ?>" class="btn btn-info btn-xs"
                                            title="klik untuk lihat"> <i class="fa fa-eye"></i></a>
                                        <?php if($item->tanggal_validasi == '2001-01-01' or $item->tanggal_validasi == null): ?>
                                            <a href="/edit_bap/<?php echo e($item->id_bap); ?>" class="btn btn-success btn-xs"
                                                title="klik untuk edit"> <i class="fa fa-edit"></i></a>
                                            <a href="/delete_bap/<?php echo e($item->id_bap); ?>" class="btn btn-danger btn-xs"
                                                title="klik untuk hapus"> <i class="fa fa-trash"></i></a>
                                        <?php else: ?>
                                            <span class="badge bg-yellow">Valid</span>
                                        <?php endif; ?>
                                    </center>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalTambahKomentar<?php echo e($item->id_rps); ?>" tabindex="-1"
                                aria-labelledby="modalTambahKomentar" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Komentar RPS</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/komentar_rps_makul/<?php echo e($item->id_rps); ?>" method="post"
                                                enctype="multipart/form-data">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('put'); ?>
                                                <div class="form-group">
                                                    <textarea class="form-control" name="komentar" cols="20" rows="10" readonly> <?php echo e($item->komentar); ?> </textarea>
                                                </div>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Tutup</button>
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
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosen/bap.blade.php ENDPATH**/ ?>