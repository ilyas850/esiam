<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <section class="content-header">
        <h1>
            Input Nilai UTS
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="<?php echo e(url('makul_diampu_kprd')); ?>"> Data Matakuliah yang diampu</a></li>
            <li><a href="/cekmhs_dsn_kprd/<?php echo e($id); ?>"> Data List Mahasiswa</a></li>
            <li class="active">Data List Mahasiswa </li>
        </ol>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data List Mahasiswa</h3>
            </div>
            <form action="<?php echo e(url('save_nilai_UTS_kprd')); ?>" method="post">
                <?php echo e(csrf_field()); ?>

                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="4%">
                                    <center>No</center>
                                </th>
                                <th width="8%">
                                    <center>NIM </center>
                                </th>
                                <th width="20%">
                                    <center>Nama</center>
                                </th>
                                <th width="15%">
                                    <center>Program Studi</center>
                                </th>
                                <th width="8%">
                                    <center>Kelas</center>
                                </th>
                                <th width="8%">
                                    <center>Angkatan</center>
                                </th>
                                <th>
                                    <center>Nilai UTS</center>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php $__currentLoopData = $ck; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                        <center> <?php echo e($item->angkatan); ?></center>
                                    </td>
                                    <td>
                                            <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                                                <input type="hidden" name="id_student[]"
                                                    value="<?php echo e($item->id_student); ?>,<?php echo e($item->id_kurtrans); ?>">
                                                <input type="hidden" name="id_studentrecord[]"
                                                    value="<?php echo e($item->id_studentrecord); ?>">
                                                <input type="text" name="nilai_UTS[]"
                                                    value="<?php echo e($item->nilai_UTS != 0 ? $item->nilai_UTS : ''); ?>"
                                                    style="width: 60px; text-align: center;">
                                                <?php if($item->absen_uts == null): ?>
                                                    <span class="text-warning" style="position: absolute; left: calc(50% + 35px); font-size: 11px; white-space: nowrap; font-weight: bold;">⚠️ Belum Absen</span>
                                                <?php endif; ?>
                                            </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <br>
                    <input type="hidden" name="id_makul" value="<?php echo e($mkl); ?>">
                    <input type="hidden" name="id_prodi" value="<?php echo e($kprd); ?>">
                    <input type="hidden" name="id_kelas" value="<?php echo e($kkls); ?>">
                    <input type="hidden" name="id_kurperiode" value="<?php echo e($kuri); ?>">
                    
                    <button class="btn btn-info" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </section>
    
    <script>
        function myFunction() {
            var x = document.getElementById("Btn");
            x.disabled = true;
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/kaprodi/matakuliah/input_uts_dsn.blade.php ENDPATH**/ ?>