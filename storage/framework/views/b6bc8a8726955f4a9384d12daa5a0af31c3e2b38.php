<?php $__env->startSection('side'); ?>

  <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
  <section class="content">
    <div class="box box-danger">
      <div class="box-header with-border">
        <h3 class="box-title">Silahkan Download Pedoman di Tabel ini</h3>
      </div>
      <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th><center>No</center></th>
              <th><center>Nama File</center></th>
              <!--<th><center>File</center></th>-->
              <th><center>Tahun Akademik</center></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; ?>
            <?php $__currentLoopData = $pedoman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keypdm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><center><?php echo e($no++); ?></center></td>
                <td><center><?php echo e($keypdm->nama_pedoman); ?></center></td>
                <!--<td><center><a href="<?php echo e(asset('/pedoman/'.$keypdm->file)); ?>" target="_blank"><?php echo e($keypdm->file); ?></a></center></td>-->
                <td><center>
                  <?php $__currentLoopData = $idhn; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($keypdm->id_periodetahun==$thn->id_periodetahun): ?>
                      <?php echo e($thn->periode_tahun); ?>

                    <?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </center></td>
                <td><center><a href="/download/<?php echo e($keypdm->id_pedomanakademik); ?>" class="btn btn-warning btn-xs">Download</a></center></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>

      </div>
    </div>
  </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/mhs/pedoman_akademik.blade.php ENDPATH**/ ?>