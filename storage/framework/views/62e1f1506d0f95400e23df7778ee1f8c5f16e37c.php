<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h4 class="box-title"><b>Edit Nilai PKL</b> </h4>
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
        <form action="<?php echo e(url('put_nilai_pkl')); ?>" method="post" enctype="multipart/form-data"
            name="autoSumForm">
            <?php echo e(csrf_field()); ?>

            <input type="hidden" name="id_settingrelasi_prausta" value="<?php echo e($id); ?>">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title"><b>Form Penilaian Pembimbing Lapangan</b> </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nilai Pembimbing Lapangan</label>
                                <font color="red-text">*</font>
                                <span>(tidak wajib untuk kelas karyawan)</span>
                                <input type="number" class="form-control" name="nilai_pembimbing_lapangan"
                                    value="<?php echo e($nilai_1); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title"><b>Form Penilaian Pembimbing</b> </h3>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="3%" align="center">No</th>
                                <th width="82%">
                                    <center>Parameter Penilaian</center>
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
                                    <td>
                                        <center><?php echo e($item->bobot); ?>%</center>
                                    </td>
                                    <td>
                                        <center>
                                            <input type="hidden" name="id_penilaian_prausta1[]"
                                                value="<?php echo e($item->id_trans_penilaian); ?>">
                                            <input type="number" name="nilai1[]" value="<?php echo e($item->nilai); ?>" required>
                                            <center>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title"><b>Form Penilaian Seminar</b> </h3>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="3%" align="center">No</th>
                                <th width="82%">
                                    <center>Parameter Penilaian</center>
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
                            <?php $__currentLoopData = $nilai_sem; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <center><?php echo e($no++); ?></center>
                                    </td>
                                    <td><?php echo e($item->komponen); ?></td>
                                    <td>
                                        <center><?php echo e($item->bobot); ?>%</center>
                                    </td>
                                    <td>
                                        <center>
                                            <input type="hidden" name="id_penilaian_prausta2[]"
                                                value="<?php echo e($item->id_trans_penilaian); ?>">
                                            <input type="number" name="nilai2[]" value="<?php echo e($item->nilai); ?>" required>
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/prakerin/edit_nilai_pkl.blade.php ENDPATH**/ ?>