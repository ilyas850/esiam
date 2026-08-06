<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h4 class="box-title"><b>Edit Nilai Tugas Akhir</b></h4>
            </div>
            <div class="box-body">
                <table width="100%">
                    <tr>
                        <td style="width: 10%">Nama</td>
                        <td style="width: 2%">:</td>
                        <td style="width: 88%"><?php echo e($datadiri->nama); ?></td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td><?php echo e($datadiri->nim); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <form class="" action="<?php echo e(url('put_nilai_ta_dospem')); ?>" method="post" enctype="multipart/form-data"
            name="autoSumForm">
            <?php echo e(csrf_field()); ?>

            <input type="hidden" name="id_settingrelasi_prausta" value="<?php echo e($id); ?>">
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title"><b>Form Penilaian Dosen Pembimbing</b> </h3>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="3%" align="center">No</th>
                                <th width="25%">
                                    <center>Komponen Penilaian</center>
                                </th>
                                <th width="57%">
                                    <center>Acuan Penilaian</center>
                                </th>
                                <th width="10%">
                                    <center>Bobot (%)</center>
                                </th>
                                <th width="5%">
                                    <center>Nilai</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php $__currentLoopData = $nilai_pem; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <center><?php echo e($no++); ?></center>
                                    </td>
                                    <td><?php echo e($item->komponen); ?></td>
                                    <td><?php echo e($item->acuan); ?></td>
                                    <td>
                                        <center><?php echo e($item->bobot); ?>%</center>
                                    </td>
                                    <td>
                                        <center>
                                            <input type="hidden" name="id_trans_penilaian[]"
                                                value="<?php echo e($item->id_trans_penilaian); ?>">
                                            <input type="number" name="nilai[]" value="<?php echo e($item->nilai); ?>" required>
                                            <center>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <button type="submit" class="btn btn-info">Simpan</button>
        </form>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/ta/edit_nilai_ta.blade.php ENDPATH**/ ?>