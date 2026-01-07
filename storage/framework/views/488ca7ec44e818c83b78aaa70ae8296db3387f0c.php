<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        

        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">PraUSTA Mahasiswa</h3>
            </div>
            <form class="form" role="form" action="<?php echo e(url('filter_nilai_prausta')); ?>" method="POST">
                <?php echo e(csrf_field()); ?>

                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label>Periode Tahun</label>
                            <select name="id_periodetahun" class="form-control" required>
                                <option></option>
                                <?php $__currentLoopData = $tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($thn->id_periodetahun); ?>"><?php echo e($thn->periode_tahun); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Periode Tipe</label>
                            <select name="id_periodetipe" class="form-control" required>
                                <option></option>
                                <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tp->id_periodetipe); ?>"><?php echo e($tp->periode_tipe); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Prodi</label>
                            <select name="kodeprodi" class="form-control" required>
                                <option></option>
                                <?php $__currentLoopData = $prodi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($prd->kodeprodi); ?>"><?php echo e($prd->prodi); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Tipe PraUSTA</label>
                            <select name="tipe_prausta" class="form-control" required>
                                <option value=""></option>
                                <option value="PKL">PKL</option>
                                <option value="SEMPRO & TA">SEMPRO & TA</option>
                                <option value="MAGANG 1">MAGANG 1</option>
                                <option value="MAGANG 2">MAGANG 2</option>
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/nilai_prausta.blade.php ENDPATH**/ ?>