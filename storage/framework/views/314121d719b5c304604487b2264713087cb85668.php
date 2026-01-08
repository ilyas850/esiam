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

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>Pertemuan</center>
                            </th>
                            <th>
                                <center>Materi Kuliah</center>
                            </th>
                            <th>
                                <center>Materi Pembelajaran (RPS)</center>
                            </th>
                            <th>
                                <center>Kesesuaian RPS</center>
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
                                <td><?php echo e($item->materi_kuliah); ?></td>
                                <td><?php echo e($item->materi_pembelajaran); ?></td>
                                <td align="center">
                                    <?php if($item->kesesuaian_rps == 'SESUAI'): ?>
                                        <?php echo e($item->kesesuaian_rps); ?>

                                    <?php elseif($item->kesesuaian_rps == 'TIDAK SESUAI'): ?>
                                        <?php echo e($item->kesesuaian_rps); ?>

                                    <?php endif; ?>
                                </td>
                                <td>
                                    <center>
                                        <?php if($item->komentar == null): ?>
                                            <button class="btn btn-info btn-xs" data-toggle="modal"
                                                data-target="#modalTambahKomentar<?php echo e($item->id_rps); ?>">Komentar</button>
                                        <?php else: ?>
                                            <a class="btn btn-warning btn-xs" data-toggle="modal"
                                                data-target="#modalTambahKomentar<?php echo e($item->id_rps); ?>"> <i
                                                    class="fa fa-eye "></i> Lihat</a>
                                        <?php endif; ?>

                                        <a href="/validasi_sesuai/<?php echo e($item->id_bap); ?>"
                                            class="btn btn-success btn-xs">Sesuai</a>
                                        <a href="/validasi_tidak_sesuai/<?php echo e($item->id_bap); ?>"
                                            class="btn btn-danger btn-xs">Tidak</a>
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
                                            <form action="/komentar_rps_makul/<?php echo e($item->id_rps); ?>"
                                                method="post" enctype="multipart/form-data">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('put'); ?>
                                                <div class="form-group">
                                                    <textarea class="form-control" name="komentar" cols="20" rows="10"> <?php echo e($item->komentar); ?> </textarea>
                                                </div>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan
                                                </button>
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/gugusmutu/perkuliahan/cek_bap.blade.php ENDPATH**/ ?>