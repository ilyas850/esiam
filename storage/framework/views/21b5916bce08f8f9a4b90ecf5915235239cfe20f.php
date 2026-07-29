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
                                <th rowspan="2">
                                    <center>No</center>
                                </th>
                                <th rowspan="2">
                                    <center>NIM </center>
                                </th>
                                <th rowspan="2">
                                    <center>Nama</center>
                                </th>
                                <th rowspan="2">
                                    <center>Program Studi</center>
                                </th>
                                <th rowspan="2">
                                    <center>Kelas</center>
                                </th>
                                <th rowspan="2">
                                    <center>Angkatan</center>
                                </th>
                                <th colspan="2">
                                    <center>Nilai Sempro (40%)</center>
                                </th>
                                <th colspan="2">
                                    <center>Nilai TA (60%)</center>
                                </th>
                                <th colspan="2">
                                    <center>Nilai Akhir</center>
                                </th>
                                <th rowspan="2">
                                    <center>Nilai Transkrip</center>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <center>Angka</center>
                                </th>
                                <th>
                                    <center>Huruf</center>
                                </th>
                                <th>
                                    <center>Angka</center>
                                </th>
                                <th>
                                    <center>Huruf</center>
                                </th>
                                <th>
                                    <center>Angka</center>
                                </th>
                                <th>
                                    <center>Huruf</center>
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
                                    <td align="center"><?php echo e($item->nilai_angka_sempro); ?></td>
                                    <td align="center"><?php echo e($item->nilai_sempro); ?></td>
                                    <td align="center"><?php echo e($item->nilai_angka_ta); ?></td>
                                    <td align="center"><?php echo e($item->nilai_ta); ?></td>
                                    <td align="center"><?php echo e($item->NILAI_AKHIR); ?></td>
                                    <td align="center">
                                        <?php if($item->NILAI_AKHIR >= 80): ?>
                                            A
                                            <input type="hidden" name="nilai_AKHIR[]"
                                                value="<?php echo e($item->id_studentrecord); ?>,A">
                                        <?php elseif($item->NILAI_AKHIR >= 75): ?>
                                            B+
                                            <input type="hidden" name="nilai_AKHIR[]"
                                                value="<?php echo e($item->id_studentrecord); ?>,B+">
                                        <?php elseif($item->NILAI_AKHIR >= 70): ?>
                                            B
                                            <input type="hidden" name="nilai_AKHIR[]"
                                                value="<?php echo e($item->id_studentrecord); ?>,B">
                                        <?php elseif($item->NILAI_AKHIR >= 65): ?>
                                            C+
                                            <input type="hidden" name="nilai_AKHIR[]"
                                                value="<?php echo e($item->id_studentrecord); ?>,C+">
                                        <?php elseif($item->NILAI_AKHIR >= 60): ?>
                                            C
                                            <input type="hidden" name="nilai_AKHIR[]"
                                                value="<?php echo e($item->id_studentrecord); ?>,C">
                                        <?php elseif($item->NILAI_AKHIR >= 50): ?>
                                            D
                                            <input type="hidden" name="nilai_AKHIR[]"
                                                value="<?php echo e($item->id_studentrecord); ?>,D">
                                        <?php elseif($item->NILAI_AKHIR >= 0): ?>
                                            E
                                            <input type="hidden" name="nilai_AKHIR[]"
                                                value="<?php echo e($item->id_studentrecord); ?>,E">
                                        <?php endif; ?>
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/filter/filter_nilai_sempro_ta.blade.php ENDPATH**/ ?>