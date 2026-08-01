<?php $__env->startSection('side'); ?>

  <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="content">
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Data Nilai Mahasiswa</h3>
        </div>
        <div class="box-body">
            <table id="example1" class="table">
                <thead>
                    <tr>
                        <th width="4%"><center>No</center></th>
                        <th width="8%"><center>NIM </center></th>
                        <th width="20%"><center>Nama</center></th>
                        <th width="15%"><center>Program Studi</center></th>
                        <th width="8%"><center>Kelas</center></th>
                        <th width="8%"><center>Angkatan</center></th>
                        <th><center>Nilai KAT</center></th>
                        <th><center>Nilai UTS</center></th>
                        <th><center>Nilai UAS</center></th>
                        <th><center>Nilai AKHIR</center></th>
                        <th><center>Nilai HURUF</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; ?>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                          <td><center><?php echo e($no++); ?></center></td>
                          <td><center><?php echo e($item->nim); ?></center></td>
                          <td><?php echo e($item->nama); ?></td>
                          <td><?php echo e($item->prodi); ?></td>
                          <td><center><?php echo e($item->kelas); ?></center></td>
                          <td><center><?php echo e($item->angkatan); ?>  </center></td>
                          <td><center><?php echo e($item->nilai_KAT); ?></center></td>
                          <td><center><?php echo e($item->nilai_UTS); ?></center></td>
                          <td><center><?php echo e($item->nilai_UAS); ?></center></td>
                          <td><center><?php echo e($item->nilai_AKHIR_angka); ?></center></td>
                          <td><center><?php echo e($item->nilai_AKHIR); ?></center></td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/kaprodi/master/cek_nilai_mhs.blade.php ENDPATH**/ ?>