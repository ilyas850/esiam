<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Kategori Penangguhan <b> <?php echo e($thn_aktif->periode_tahun); ?> -
                                <?php echo e($tp_aktif->periode_tipe); ?></b></h3>
                    </div>
                    <div class="box-body">
                        <form class="form" role="form" action="<?php echo e(url('pilih_ta_penangguhan')); ?>" method="POST">
                            <?php echo e(csrf_field()); ?>

                            <div class="row">
                                <div class="col-xs-5">
                                    <label>Periode tahun</label>
                                    <select class="form-control" name="id_periodetahun" required>
                                        <option></option>
                                        <?php $__currentLoopData = $tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key->id_periodetahun); ?>">
                                                <?php echo e($key->periode_tahun); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <label>Periode tipe</label>
                                    <select class="form-control" name="id_periodetipe" required>
                                        <option></option>
                                        <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($tipee->id_periodetipe); ?>"><?php echo e($tipee->periode_tipe); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-info ">Tampilkan</button>
                        </form>
                        <br>
                        <table id="example8" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <center> No</center>
                                    </th>
                                    <th>
                                        <center>Kategori</center>
                                    </th>
                                    <th>
                                        <center>Jumlah</center>
                                    </th>
                                    <th>
                                        <center>Aksi</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td align="center"><?php echo e($no++); ?></td>
                                        <td><?php echo e($item->kategori); ?></td>
                                        <td align="center"><?php echo e($item->jml_penangguhan); ?></td>
                                        <td align="center">
                                            <form action="<?php echo e(url('data_penangguhan_bauk')); ?>" method="POST">
                                                <?php echo e(csrf_field()); ?>

                                                <input type="hidden" name="id_penangguhan_kategori"
                                                    value="<?php echo e($item->id_penangguhan_kategori); ?>">
                                                <input type="hidden" name="id_periodetahun"
                                                    value="<?php echo e($thn_aktif->id_periodetahun); ?>">
                                                <input type="hidden" name="id_periodetipe"
                                                    value="<?php echo e($tp_aktif->id_periodetipe); ?>">
                                                <button type="submit" class="btn btn-info btn-xs">
                                                    Cek Data
                                                </button>
                                            </form>

                                            
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/bauk/penangguhan/kategori_penangguhan.blade.php ENDPATH**/ ?>