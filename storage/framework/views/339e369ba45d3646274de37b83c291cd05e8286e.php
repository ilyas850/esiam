<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <table width="100%">
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td><?php echo e($mhs->nama); ?></td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td><?php echo e($mhs->prodi); ?></td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td> <?php echo e($mhs->nim); ?></td>

                        <td>Kelas</td>
                        <td>:</td>
                        <td><?php echo e($mhs->kelas); ?></td>
                    </tr>
                </table>
            </div>
            <div class="box-header">
                <h3 class="box-title">Rekapan Nilai Mahasiswa</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                <center>SKS</center>
                            </th>
                            <th>
                                <center>Nilai Akhir</center>
                            </th>
                            <th>
                                <center>Semester</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td align="center"><?php echo e($no++); ?></td>
                                <td><?php echo e($item->makul); ?></td>
                                <td align="center"><?php echo e($item->sks); ?></td>
                                <td align="center"><?php echo e($item->nilai_AKHIR); ?></td>
                                <td align="center"><?php echo e($item->semester); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/kaprodi/nilai/cek_rekap_nilai_mhs.blade.php ENDPATH**/ ?>