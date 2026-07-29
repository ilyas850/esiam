<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="callout callout-info" style="border-left: 5px solid #00c0ef; background-color: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h4 style="margin-top: 0; font-weight: 600;"><i class="icon fa fa-info-circle text-info"></i> Filter Nilai PraUSTA</h4>
            <p class="text-muted" style="margin-bottom: 0;">Pilih Periode Tahun Akademik, Semester, Program Studi, dan Tipe PraUSTA untuk menampilkan serta menginput daftar nilai mahasiswa.</p>
        </div>

        <div class="box box-info" style="box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter text-info"></i> <b>Form Filter Nilai PraUSTA</b></h3>
            </div>
            <form class="form" role="form" action="<?php echo e(url('filter_nilai_prausta')); ?>" method="POST">
                <?php echo e(csrf_field()); ?>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
                            <label><i class="fa fa-calendar text-info"></i> Periode Tahun</label>
                            <select name="id_periodetahun" class="form-control select2" style="width: 100%;" required>
                                <option value="">-- Pilih Periode Tahun --</option>
                                <?php $__currentLoopData = $tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($thn->id_periodetahun); ?>"><?php echo e($thn->periode_tahun); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
                            <label><i class="fa fa-clock-o text-info"></i> Periode Tipe</label>
                            <select name="id_periodetipe" class="form-control select2" style="width: 100%;" required>
                                <option value="">-- Pilih Periode Tipe --</option>
                                <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tp->id_periodetipe); ?>"><?php echo e($tp->periode_tipe); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
                            <label><i class="fa fa-graduation-cap text-info"></i> Program Studi</label>
                            <select name="kodeprodi" class="form-control select2" style="width: 100%;" required>
                                <option value="">-- Pilih Program Studi --</option>
                                <?php $__currentLoopData = $prodi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($prd->kodeprodi); ?>"><?php echo e($prd->prodi); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
                            <label><i class="fa fa-book text-info"></i> Tipe PraUSTA</label>
                            <select name="tipe_prausta" class="form-control select2" style="width: 100%;" required>
                                <option value="">-- Pilih Tipe PraUSTA --</option>
                                <option value="PKL">PKL</option>
                                <option value="SEMPRO & TA">SEMPRO & TA</option>
                                <option value="MAGANG 1">MAGANG 1</option>
                                <option value="MAGANG 2">MAGANG 2</option>
                                <option value="SEMPRO & SKRIPSI">SEMPRO & SKRIPSI</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer text-right" style="background-color: #f9fafc;">
                    <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i> Tampilkan Data Nilai</button>
                </div>
            </form>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                allowClear: true
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/nilai_prausta.blade.php ENDPATH**/ ?>