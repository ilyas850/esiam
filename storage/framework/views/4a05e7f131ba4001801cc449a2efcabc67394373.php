<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Rencana Pembelajaran Semester Matakuliah</b></h3><br><br>
                <table width="100%">
                    <tr>
                        <td>Kode - Matakuliah</td>
                        <td>:</td>
                        <td><?php echo e($data->kode); ?> - <?php echo e($data->makul); ?></td>
                        <td>SKS</td>
                        <td>:</td>
                        <td><?php echo e($data->sks); ?> </td>
                    </tr>
                    <tr>
                        <td>Prodi</td>
                        <td>:</td>
                        <td><?php echo e($data->prodi); ?>

                        </td>
                        <td>Kelas</td>
                        <td>:</td>
                        <td><?php echo e($data->kelas); ?></td>
                    </tr>
                    <tr>
                        <td>Tahun Akademik</td>
                        <td>:</td>
                        <td><?php echo e($data->periode_tahun); ?> - <?php echo e($data->periode_tipe); ?></td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <form action="<?php echo e(url('udpate_rps_makul_dsn')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id_kurperiode" value="<?php echo e($id); ?>">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="10%">Pertemuan</th>
                                <th width="45%">Kemampuan Akhir yang Diharapkan</th>
                                <th width="45%">Materi Pembelajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>Pertemuan ke - <?php echo e($item->pertemuan); ?>

                                        <input type="hidden" name="id_rps[]" value="<?php echo e($item->id_rps); ?>">
                                        <input type="hidden" name="pertemuan[]" value="<?php echo e($item->pertemuan); ?>">
                                    </td>
                                    <td>
                                        <textarea type="text" class="form-control" name="kemampuan_akhir_direncanakan[]" rows="3" required><?php echo e($item->kemampuan_akhir_direncanakan); ?></textarea>
                                    </td>
                                    <td>
                                        <textarea type="text" class="form-control" name="materi_pembelajaran[]" rows="3" required><?php echo e($item->materi_pembelajaran); ?></textarea>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <button class="btn btn-info btn-block" type="submit">Simpan</button>
                </form>

            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosen/pengajaran/edit_form_rps.blade.php ENDPATH**/ ?>