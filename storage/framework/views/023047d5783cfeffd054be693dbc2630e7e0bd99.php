<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Absensi Mahasiswa</h3>
            </div>
            <form action="<?php echo e(url('save_edit_absensi_dsn')); ?>" method="post">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id_bap" value="<?php echo e($id); ?>">
                <input type="hidden" name="id_kurperiode" value="<?php echo e($idk); ?>">
                <div class="box-body">
                    <div class="form-group">
                        <div class="callout callout-warning">
                            <p>Remark : Centang untuk mahasiswa yang hadir</p>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <center>No</center>
                                </th>
                                <th>
                                    <center>NIM </center>
                                </th>
                                <th>
                                    <center>Nama</center>
                                </th>
                                <th>
                                    <center>Program Studi</center>
                                </th>
                                <th>
                                    <center>Kelas</center>
                                </th>
                                <th>
                                    <center>Angkatan</center>
                                </th>
                                <th>
                                    <center>Hadir</center>
                                </th>
                                <th>
                                    <center>Alpa</center>
                                </th>
                                <th>
                                    <center>Izin</center>
                                </th>
                                <th>
                                    <center>Sakit</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php $__currentLoopData = $abs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                    <td align="center">
                                        <div class="radio">
                                            <label>
                                                <?php if($item->absensi == 'ABSEN'): ?>
                                                    <input type="radio"
                                                        name="absensi_radio[<?php echo e($item->id_studentrecord); ?>]"
                                                        value="<?php echo e($item->id_studentrecord); ?>,ABSEN" checked>
                                                <?php else: ?>
                                                    <input type="radio"
                                                        name="absensi_radio[<?php echo e($item->id_studentrecord); ?>]"
                                                        value="<?php echo e($item->id_studentrecord); ?>,ABSEN">
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td align="center">
                                        <div class="radio">
                                            <label>
                                                <?php if($item->absensi == 'ALFA'): ?>
                                                    <input type="radio"
                                                        name="absensi_radio[<?php echo e($item->id_studentrecord); ?>]"
                                                        value="<?php echo e($item->id_studentrecord); ?>,ALFA" checked>
                                                <?php else: ?>
                                                    <input type="radio"
                                                        name="absensi_radio[<?php echo e($item->id_studentrecord); ?>]"
                                                        value="<?php echo e($item->id_studentrecord); ?>,ALFA">
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td align="center">
                                        <div class="radio">
                                            <label>
                                                <?php if($item->absensi == 'IZIN'): ?>
                                                    <input type="radio"
                                                        name="absensi_radio[<?php echo e($item->id_studentrecord); ?>]"
                                                        value="<?php echo e($item->id_studentrecord); ?>,IZIN" checked>
                                                <?php else: ?>
                                                    <input type="radio"
                                                        name="absensi_radio[<?php echo e($item->id_studentrecord); ?>]"
                                                        value="<?php echo e($item->id_studentrecord); ?>,IZIN">
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td align="center">
                                        <div class="radio">
                                            <label>
                                                <?php if($item->absensi == 'SAKIT'): ?>
                                                    <input type="radio"
                                                        name="absensi_radio[<?php echo e($item->id_studentrecord); ?>]"
                                                        value="<?php echo e($item->id_studentrecord); ?>,SAKIT" checked>
                                                <?php else: ?>
                                                    <input type="radio"
                                                        name="absensi_radio[<?php echo e($item->id_studentrecord); ?>]"
                                                        value="<?php echo e($item->id_studentrecord); ?>,SAKIT">
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <br>
                    <button id="simpan" class="btn btn-success btn-block" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosenluar/edit_absen.blade.php ENDPATH**/ ?>