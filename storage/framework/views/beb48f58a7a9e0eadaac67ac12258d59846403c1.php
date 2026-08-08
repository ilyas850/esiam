<?php $__env->startSection('side'); ?>

    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Pilih Tahun Akademik dan Semester</h3>
            </div>
            <div class="box-body">
                <form class="form" role="form" action="<?php echo e(url('filter_rekap_nilai_mhs')); ?>" method="POST">
                    <?php echo e(csrf_field()); ?>

                    <div class="row">
                        <div class="col-xs-3">
                            <label>Periode Tahun</label>
                            <select class="form-control" name="id_periodetahun" required>
                                <option></option>
                                <?php $__currentLoopData = $periode_tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tahun->id_periodetahun); ?>">
                                        <?php echo e($tahun->periode_tahun); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Semester</label>
                            <select class="form-control" name="id_periodetipe" required>
                                <option></option>
                                <?php $__currentLoopData = $periode_tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tipe->id_periodetipe); ?>">
                                        <?php echo e($tipe->periode_tipe); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-3">
                            <button type="submit" class="btn btn-success">Lihat</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Rekap Nilai Mahasiswa <b> <?php echo e($nama_tahun); ?> - <?php echo e($nama_tipe); ?> </b></h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th> <center> No</center></th>
                            <th><center>Kode/Matakuliah</center></th>
                            <th><center>SKS</center></th>
                            <th><center>Prodi</center></th>
                            <th><center>Kelas</center></th>
                            <th><center>Jumlah Mahasiswa</center></th>
                            <th><center>Dosen</center></th>
                            <th><center>Nilai</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td align="center"><?php echo e($no++); ?></td>
                                <td><?php echo e($key->kode); ?>/<?php echo e($key->makul); ?></td>
                                <td align="center"><?php echo e($key->akt_sks_teori + $key->akt_sks_praktek); ?></td>
                                <td align="center"><?php echo e($key->prodi); ?></td>
                                <td align="center"><?php echo e($key->kelas); ?></td>
                                <td align="center"><?php echo e($key->jml_mhs); ?></td>  
                                <td><?php echo e($key->nama); ?></td>
                                <td align="center">
                                    <a href="cek_rekap_nilai_mhs/<?php echo e($key->id_kurperiode); ?>" class="btn btn-info btn-xs">Cek</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/adminprodi/nilai/rekap_nilai_mhs.blade.php ENDPATH**/ ?>