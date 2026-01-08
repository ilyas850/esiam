<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Silahkan Filter</h3>
            </div>
            <form class="form" role="form" action="<?php echo e(url('cari_mhs_aktif_admin')); ?>" method="POST">
                <?php echo e(csrf_field()); ?>

                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="">Periode Tahun</label>
                            <select class="form-control" name="id_periodetahun">
                                <option></option>
                                <?php $__currentLoopData = $tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($thn->id_periodetahun); ?>"><?php echo e($thn->periode_tahun); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Periode Tipe</label>
                            <select class="form-control" name="id_periodetipe" required>
                                <option></option>
                                <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tipee->id_periodetipe); ?>"><?php echo e($tipee->periode_tipe); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Program Studi</label>
                            <select class="form-control" name="kodeprodi" required>
                                <option></option>
                                <?php $__currentLoopData = $prodi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($prd->kodeprodi); ?>"><?php echo e($prd->prodi); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-info">Cari Mahasiswa</button>
                </div>
            </form>
        </div>

        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Data mahasiswa aktif all Prodi</h3>
            </div>
            <div class="box-body">

                <a href="<?php echo e(url('export_data_mhs_admin')); ?>" class="btn btn-success">Export Excel</a>
                <br><br>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>NIM</center>
                            </th>
                            <th>
                                <center>Nama Mahasiswa</center>
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
                                <center>Intake</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <center><?php echo e($no++); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->nim); ?></center>
                                </td>
                                <td><?php echo e($key->nama); ?></td>
                                <td>
                                    <center><?php echo e($key->prodi); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->kelas); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->angkatan); ?></center>
                                </td>
                                <td align="center">
                                    <?php echo e($key->intake); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/sadmin/datamahasiswa/data_mhs_aktif.blade.php ENDPATH**/ ?>