<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title"> <b> Data List Mahasiswa </b></h3>
                <table width="100%">
                    <tr>
                        <td>Tahun Akademik</td>
                        <td>:</td>
                        <td><?php echo e($tahun->periode_tahun); ?></td>
                        <td>Prodi</td>
                        <td>:</td>
                        <td><?php echo e($prodi->prodi); ?></td>
                    </tr>
                    <tr>
                        <td>Semester</td>
                        <td>:</td>
                        <td><?php echo e($tipe->periode_tipe); ?></td>
                        <td>Tipe PraUSTA</td>
                        <td>:</td>
                        <td><?php echo e($tp_prausta); ?></td>
                    </tr>
                </table>
            </div>
            <form action="<?php echo e(url('save_nilai_to_trans')); ?>" method="post">
                <?php echo e(csrf_field()); ?>

                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">
                                    <center>No</center>
                                </th>
                                <th width="10%">
                                    <center>NIM </center>
                                </th>
                                <th width="25%">
                                    <center>Nama</center>
                                </th>
                                <th width="15%">
                                    <center>Program Studi</center>
                                </th>
                                <th width="10%">
                                    <center>Kelas</center>
                                </th>
                                <th width="10%">
                                    <center>Angkatan</center>
                                </th>
                                <th>
                                    <center>Nilai Magang 2</center>
                                </th>
                                <th>
                                    <center>Nilai Transkrip</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <center><?php echo e($no++); ?></center>
                                    </td>
                                    <td>
                                        <center><?php echo e($item->nim); ?></center>
                                    </td>
                                    <td><?php echo e($item->nama); ?></td>
                                    <td><?php echo e($item->prodi); ?></td>
                                    <td>
                                        <center><?php echo e($item->kelas); ?></center>
                                    </td>
                                    <td>
                                        <center><?php echo e($item->angkatan); ?></center>
                                    </td>
                                    <td>
                                        <center>
                                            <?php if($item->nilai_huruf == 'A'): ?>
                                                A
                                                <input type="hidden" name="nilai_AKHIR[]"
                                                    value="<?php echo e($item->id_studentrecord); ?>,A">
                                            <?php elseif($item->nilai_huruf == 'B+'): ?>
                                                B+
                                                <input type="hidden" name="nilai_AKHIR[]"
                                                    value="<?php echo e($item->id_studentrecord); ?>,B+">
                                            <?php elseif($item->nilai_huruf == 'B'): ?>
                                                B
                                                <input type="hidden" name="nilai_AKHIR[]"
                                                    value="<?php echo e($item->id_studentrecord); ?>,B">
                                            <?php elseif($item->nilai_huruf == 'C+'): ?>
                                                C+
                                                <input type="hidden" name="nilai_AKHIR[]"
                                                    value="<?php echo e($item->id_studentrecord); ?>,C+">
                                            <?php elseif($item->nilai_huruf == 'C'): ?>
                                                C
                                                <input type="hidden" name="nilai_AKHIR[]"
                                                    value="<?php echo e($item->id_studentrecord); ?>,C">
                                            <?php elseif($item->nilai_huruf == 'D'): ?>
                                                D
                                                <input type="hidden" name="nilai_AKHIR[]"
                                                    value="<?php echo e($item->id_studentrecord); ?>,D">
                                            <?php elseif($item->nilai_huruf == 'E'): ?>
                                                E
                                                <input type="hidden" name="nilai_AKHIR[]"
                                                    value="<?php echo e($item->id_studentrecord); ?>,E">
                                            <?php endif; ?>
                                        </center>
                                    </td>
                                    <td align="center">
                                        <?php echo e($item->nilai_AKHIR); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <br>
                    <input class="btn btn-info btn-block" type="submit" name="submit" value="Simpan">
                </div>
            </form>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/filter/filter_nilai_magang2.blade.php ENDPATH**/ ?>