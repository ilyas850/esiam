<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Filter Kurikulum Lama</h3>
            </div>
            <form class="form" role="form" action="<?php echo e(url('filter_konversi')); ?>" method="POST">
                <?php echo e(csrf_field()); ?>

                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-2">
                            <label>Kurikulum</label>
                            <select class="form-control" name="id_kurikulum" required>
                                <option></option>
                                <?php $__currentLoopData = $kurikulum; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $krlm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($krlm->id_kurikulum); ?>"><?php echo e($krlm->nama_kurikulum); ?> -
                                        <?php if($krlm->remark == 1): ?>
                                            Ganjil
                                        <?php elseif($krlm->remark == 2): ?>
                                            Genap
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <div class="col-xs-4">
                            <label>Program Studi</label>
                            <select class="form-control" name="id_prodi" required>
                                <option></option>
                                <?php $__currentLoopData = $prodi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($prd->id_prodi); ?>"><?php echo e($prd->prodi); ?> - <?php echo e($prd->konsentrasi); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Angkatan</label>
                            <select class="form-control" name="id_angkatan" required>
                                <option></option>
                                <?php $__currentLoopData = $angkatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $angk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($angk->idangkatan); ?>"><?php echo e($angk->angkatan); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Kelas</label>
                            <select class="form-control" name="idkelas" required>
                                <option></option>
                                <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($kls->idkelas); ?>"><?php echo e($kls->kelas); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">Filter</button>
                </div>
            </form>
        </div>
    </section>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/sadmin/konversi/filter_konversi.blade.php ENDPATH**/ ?>