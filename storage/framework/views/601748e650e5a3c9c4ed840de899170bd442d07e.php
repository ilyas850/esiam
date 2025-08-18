<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Filter Periode Tahun Akademik - Semester</h3>
            </div>
            <form class="form" role="form" action="<?php echo e(url('filter_rekap_perkuliahan')); ?>" method="POST">
                <?php echo e(csrf_field()); ?>

                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="">Periode Tahun</label>
                            <select class="form-control" name="id_periodetahun" required>
                                <option></option>
                                <?php $__currentLoopData = $tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($thn->id_periodetahun); ?>"><?php echo e($thn->periode_tahun); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Semester</label>
                            <select class="form-control" name="id_periodetipe" required>
                                <option></option>
                                <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tipee->id_periodetipe); ?>"><?php echo e($tipee->periode_tipe); ?></option>
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
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Rekap Perkuliahan <b><?php echo e($namaperiodetahun); ?> - <?php echo e($namaperiodetipe); ?></b></h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Kode/Matakuliah</center>
                            </th>
                            <th>
                                <center>SKS (T/P)</center>
                            </th>
                            <th>
                                <center>Prodi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Dosen</center>
                            </th>
                            <th>
                                <center>Jumlah Pertemuan</center>
                            </th>
                            <th>
                                <center>BAP</center>
                            </th>
                            <th>
                                <center>Download</center>
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
                                <td><?php echo e($key->makul); ?></td>
                                <td>
                                    <center><?php echo e($key->sks); ?></center>
                                </td>
                                <td><?php echo e($key->prodi); ?></td>
                                <td><?php echo e($key->kelas); ?></td>
                                <td><?php echo e($key->nama); ?></td>
                                <td>
                                    <center>
                                        <?php echo e($key->jml_per); ?>

                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="cek_rekapan/<?php echo e($key->id_kurperiode); ?>" class="btn btn-info btn-xs"><i
                                                class="fa fa-eye"></i> Cek
                                        </a>
                                    </center>
                                </td>
                                <td align="center">
                                    <a href="/download_bap_dosen/<?php echo e($key->id_kurperiode); ?>" class="btn btn-danger btn-xs"
                                        title="klik untuk download BAP"><i class="fa fa-download"></i> BAP</a>
                                    <a href="/download_absensi_mhs/<?php echo e($key->id_kurperiode); ?>" class="btn btn-danger btn-xs"
                                        title="klik untuk download Absensi"><i class="fa fa-download"></i> Absen</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/sadmin/perkuliahan/rekap_perkuliahan.blade.php ENDPATH**/ ?>